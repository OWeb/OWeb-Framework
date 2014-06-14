<?php
/**
 * @author      Oliver de Cramer (oliverde8 at gmail.com)
 * @copyright    GNU GENERAL PUBLIC LICENSE
 *                     Version 3, 29 June 2007
 *
 * PHP version 5.3 and above
 *
 * LICENSE: This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see {http://www.gnu.org/licenses/}.
 */
namespace Model\programs;

use Model\programs\Categories;
use Model\articles\Artciles;
use Model\programs\exception\ProgramNotFound;
use OWeb\manage\Extensions;
use OWeb\utils\Singleton;

/**
 * Description of Programs
 *
 * @author oliverde8
 */
class Programs extends Singleton
{

    private $ext_connection;

    private $categories;
    private $articles;

    private $programs = array();

    static public function getInstance(){
        $class = self::getInstanceNull();

        if($class == null){
            $obj = new Programs(Categories::getInstance(), Artciles::getInstance());
            self::setInstance($obj);
            return $obj;
        }
    }

    /**
     *
     * @param Categories $cat
     * @param Artciles $art
     */
    function __construct(Categories $cat, Artciles $art)
    {
        $this->ext_connection = Extensions::getInstance()->getExtension('db\Connection');
        $this->categories = $cat;
        $this->articles = $art;
    }

    /**
     * Recovers the Program with the demanded ID
     * @param $id
     * @return Program
     * @throws exception\ProgramNotFound thrown if this program doesn't exist or there is a problem with the DB
     */
    public function getprogram($id)
    {

        if (isset($this->programs[$id]))
            return $this->programs[$id];
        else {

            try {

                $programs = $this->getProgramsArray(array($id));

                if(isset($programs[$id])){
                    return $programs[$id];
                }else{
                    throw new ProgramNotFound("Couldn't get Program with id : $id . NO such program", 0);
                }

            } catch (\Exception $ex) {
                throw new ProgramNotFound("Couldn't get Program with id : $id . ERROR", 0, $ex);
            }
        }
    }

    public function getNewestPrograms($start, $nbElement){

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix = $this->ext_connection->get_prefix();

            $sql2 = "SELECT id_prog
                        FROM " . $prefix . "program p
                    ORDER BY date DESC
                    LIMIT $start, $nbElement";

            if ($sql = $connection->query($sql2)) {

                $programs = array();
                while ($result = $sql->fetchObject()) {
                    $programs[] = $result->id_prog;
                }

                return $this->getProgramsArray($programs);

            }else{
                throw new ProgramNotFound("Couldn't get Newest Programs. SQL ERROR2 => ".$sql2, 0);
            }

        }catch (\Exception $ex) {
            throw new ProgramNotFound("Couldn't get Newest Programs. SQL ERROR", 0, $ex);
        }
        return array();
    }

    public function getLatestUpdatedPrograms($start, $nbElement){

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix = $this->ext_connection->get_prefix();

            $sql2 = "SELECT id_prog
                        FROM ".$prefix."program p, ".$prefix."program_version v, ".$prefix."program_revision r
                        WHERE p.id_prog = v.prog_id
                        AND v.id_version = r.version_id
                        ORDER BY r.date DESC";

            if ($sql = $connection->query($sql2)) {
				$added = array();
                $programs = array();
				$i = 0;
				$result = $sql->fetchObject();
                while ($result  && $i < $start+$nbElement ) {
					if(!isset($added[$result->id_prog]) && $i >= $start){
						$programs[] = $result->id_prog;
						$added[$result->id_prog] = true;
						$i++;
					}
					$result = $sql->fetchObject();
                }

                return $this->getProgramsArray($programs);
            }else{
                throw new ProgramNotFound("Couldn't get Latest Updated Programs : SQL ERROR2 => ".$sql2, 0);
            }

        }catch (\Exception $ex) {
            throw new ProgramNotFound("Couldn't get  Latest Updated  Program : SQL ERROR", 0, $ex);
        }
        return array();
    }

    /**
     * @param CategorieElement $cat The category of the program wanted
     * @param int $start
     * @param int $nbELement Maximum number of elements to return
     * @param bool $rec Should the program search be recursive in the categories
     * @return array(Program) Array of the programs found
     * @throws exception\ProgramNotFound If there is a SQL error
     */
    public function getPrograms(\Model\programs\CategorieElement $cat, $start, $nbELement, $rec = true){

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix = $this->ext_connection->get_prefix();

            $cs = $cat->getChildrens();


            if ($rec && !empty($cs)) {
                $cat_parents = $cat->getRecuriveChildrensIds();

                $sql2 = "SELECT id_prog
                            FROM " . $prefix . "program p
                            WHERE cat_id IN ($cat_parents)
                                OR id_prog IN (SELECT pcm.category_id
														FROM " . $prefix . "program_category_category pcm
								 						WHERE pcm.category_id = ".$cat->getId()." )
                        ORDER BY date DESC
                        LIMIT $start, $nbELement";
            } else
                $sql2 = "SELECT id_prog
                            FROM " . $prefix . "program p
                            WHERE cat_id = " . $cat->getId() . "
                                OR id_prog IN (SELECT pcm.prog_id
														FROM " . $prefix . "program_category_category pcm
														WHERE pcm.category_id = ".$cat->getId()." )
                        ORDER BY date DESC
                        LIMIT $start, $nbELement";


            if ($sql = $connection->query($sql2)) {

                $programs = array();
                while ($result = $sql->fetchObject()) {
                    $programs[] = $result->id_prog;
                }

                return $this->getProgramsArray($programs);

            }else{
                throw new ProgramNotFound("Couldn't get Program of Category : " . $cat->getId() . " . SQL ERROR2 => ".$sql2, 0);
            }

        }catch (\Exception $ex) {
            throw new ProgramNotFound("Couldn't get Program of Category : " . $cat->getId() . " . SQL ERROR", 0, $ex);
        }
        return array();
    }

    /**
     * Recovers the Progams of the Array of id.
     *
     * @param array(int) $ids The array of id.
     * @return array(Program) The programs that was found
     * @throws exception\ProgramNotFound If there is a SQL error
     */
    public function getProgramsArray($ids){

        $idString = "";
        foreach($ids as $id){
            $idString .=$id.',';
        }
        $idString = substr($idString, 0, strlen($idString)-1);

        try {
            $connection = $this->ext_connection->get_Connection();
            $prefix = $this->ext_connection->get_prefix();

            $sql2 = "SELECT *
                    FROM " . $prefix . "program p," . $prefix . "program_description d
                    WHERE prog_id IN (" . $idString . ")
                        AND prog_id = id_prog
                ORDER BY date DESC";

            $programs = array();
            if ($sql = $connection->query($sql2)) {

                while ($result = $sql->fetchObject()) {

                    if (isset($this->programs[$result->id_prog])) {
                        $programs[$result->id_prog] = $this->programs[$result->id_prog];
                    }else if(isset($programs[$result->id_prog])){
                        $programs[$result->id_prog]->addLanguage($result->lang, $result->short_desc, $result->vshort_desc);
                    }else {
                        $article = null;
                        try {
                            $article = $this->articles->getArticle($result->article_id);
                        } catch (\Exception $ex) {
                        }

                        $programs[$result->id_prog] =
                            new \Model\programs\Program    (
                                $result->id_prog,
                                $result->name,
                                $result->img,
                                $result->front_page == 1,
                                $this->categories->getElement($result->cat_id),
                                $article,
                                $result->date,
                                $result->gallery_path
                            );
                        $programs[$result->id_prog]->addLanguage($result->lang, $result->short_desc, $result->vshort_desc);
                    }
                }
            } else {
                throw new ProgramNotFound("Couldn't get Program of List : ".$idString." SQL ERROR2 "+$sql2, 0);
            }

            //Main program component gather, now let's get the rest of it.

            //First second categories
            $sql = "SELECT * FROM ".$prefix."program_category_category
                        WHERE prog_id IN (".$idString.")";

            if ($sql = $connection->query($sql)) {

                while ($result = $sql->fetchObject()) {
                    if(isset($programs[$result->prog_id])){
                        $programs[$result->prog_id]->addCategory($this->categories->getElement($result->category_id));
                    }
                }
            } else {
                throw new ProgramNotFound("Couldn't get Program of List : ".$idString." Error gathering secondary Categories", 0);
            }

            //Second secondary articles
            $sql2 = "SELECT * FROM ".$prefix."program_article
                        WHERE prog_id IN (".$idString.")";

            if ($sql = $connection->query($sql2)) {

                while ($result = $sql->fetchObject()) {
                    if(isset($programs[$result->prog_id])){
                        $programs[$result->prog_id]->addArticle($this->articles->getArticle($result->article_id));
                    }
                }
            } else {
                throw new ProgramNotFound("Couldn't get Program of List : ".$idString." Error gathering secondary Articles. ".$sql2, 0);
            }

            //Third loading versions
            $sql2 = "SELECT * FROM ".$prefix."program_version, ".$prefix."program_revision r
                        WHERE prog_id IN (".$idString.")
                            AND id_version = r.version_id
						ORDER BY  r.date DESC";

            if ($sql = $connection->query($sql2)) {

                $versions = array();

                while ($result = $sql->fetchObject()) {
                    if(isset($programs[$result->prog_id])){

                        if(!isset($versions[$result->id_version])){
                            $versions[$result->id_version] = new Version($result->id_version, $result->name, $result->desc);
                            $programs[$result->prog_id]->addVersion($versions[$result->id_version]);
                        }
                        $version = $versions[$result->id_version];
                        $version->addRevision(new Revision($result->revision_name, $result->date, $result->description, $result->dwld, $result->isBeta == 1));
                    }
                }
            } else {
                throw new ProgramNotFound("Couldn't get Program of List : ".$idString." Error gathering Revisions and Versions. ".$sql2, 0);
            }

            //Lest register the new programs in to the buffer
            foreach($programs as $id=>$program){
                $this->programs[$id] = $program;
            }


            return $programs;

        } catch (\Exception $ex) {
            throw new ProgramNotFound("Couldn't get Program of List : ".$idString." . SQL ERROR", 0, $ex);
        }

    }

}

?>

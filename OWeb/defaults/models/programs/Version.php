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


class Version {

    private $id;
    private $name;
    private $versionName;

    private $lastRealeseDate = 0;
    private $lastRealese;

    private $description;

    private $revisions = array();


    function __construct($id, $name, $description)
    {
        $this->description = $description;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $versionName
     */
    public function setVersionName($versionName)
    {
        $this->versionName = $versionName;
    }

    /**
     * @return string
     */
    public function getVersionName()
    {
        return $this->versionName;
    }

    public function addRevision(Revision $rev){
        $this->revisions[] = $rev;

        if($this->lastRealeseDate < $rev->getDate()){
            $this->lastRealeseDate = $rev->getDate();
            $this->lastRealese = $rev;
        }
    }

    /**
     * @return mixed
     */
    public function getLastRealese()
    {
        return $this->lastRealese;
    }

    public function getAllRevisions(){
        return $this->revisions;
    }

    public function getDownload(){
        $more = "";
        if(is_numeric($this->getDownloadLink())){
            return new \Model\downloads\Download($this->getDownloadLink());
        }else{
            $link = $this->getDownloadLink();
        }
    }

}
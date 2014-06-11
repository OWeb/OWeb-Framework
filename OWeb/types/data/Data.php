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

namespace OWeb\types\data;


/**
 * Represent a data that can be saved in a file
 *
 * Class Data
 * @package OWeb\types\data
 * @author oliverde8
 */
class Data {

    private $dataCategorie;
    private $dataId;
    private $fileName;
    private $path;

    function __construct($dataCategory, $dataId, $fileName=null){

        $this->dataId = $dataId;
        $this->dataCategorie = $dataCategory;

        $md5 = md5($dataId);
        if($fileName == null)
            $this->fileName = $md5.'.odata';

        $this->path = "/tmp/".$dataCategory."/".self::generatePathFromId($md5);

        if(!is_dir($this->path))
            mkdir($this->path, 0, true);
    }

    /**
     * @param $id string
     * @return string
     */
    public static function generatePathFromId($id){

        $path = "";

        for($i = 1; $i < strlen($id); $i = $i+2){

            $path .= $id[$i-1].$id[$i].'/';

        }

        return $path;
    }

    /**
     * @param $data string
     */
    public function saveData($data){
        file_put_contents(\OWEB_DIR_DATA.$this->path.$this->fileName, $data);
    }

    /**
     * @return string
     */
    public function getData(){
        if(file_exists($this->path.$this->fileName)){
            return file_get_contents(\OWEB_DIR_DATA.$this->path.$this->fileName);
        }
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return \OWEB_DIR_DATA.$this->path;
    }

    /**
     * @return string
     */
    public function getFile(){
        return \OWEB_DIR_DATA.$this->path.$this->fileName;
    }

    /**
     * @return string
     */
    public function getDataCategorie()
    {
        return $this->dataCategorie;
    }

    /**
     * @return string
     */
    public function getDataId()
    {
        return $this->dataId;
    }

    /**
     * @return string
     */
    public function getUrl(){
        return \OWEB_WWW_DATA.$this->path.$this->fileName;
    }


}
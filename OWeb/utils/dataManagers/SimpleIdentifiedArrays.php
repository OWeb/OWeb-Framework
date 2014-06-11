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

namespace OWeb\utils\dataManagers;


use OWeb\utils\dataManagers\simpleIdentifiedArrays\Settings;

class SimpleIdentifiedArrays {

    protected $elementPerFile;

    protected $path;
    protected $id;
    protected $name;
    protected $decomposed_id = array();
    protected $supPath;
    protected $array;

    protected $settings;

    function __construct($name, $id){
        $this->id = $id;
        $this->name = $name;
        $this->settings = new Settings();
    }

    public function setPermissionAgentName($name){
        $this->settings->PermissionAgentName = $name;
    }

    public function setPrivate($bool){
        $this->settings->private = $bool;
    }

    public function setElementPerFile($nb){
        $this->settings->elementPerFile = $nb;
    }

    public function start(){
        //check file and create sub file.
        if(is_dir(OWEB_DIR_DATA)){
            $path = OWEB_DIR_DATA.'/'.'SimpleIdentifiedArrays'.'/'.$this->name;
            if(!is_dir($path)){
                mkdir(OWEB_DIR_DATA.'/'.'SimpleIdentifiedArrays'.'/'.$this->name);
                $data = $this->generateSettingsData();
                file_put_contents($path.'/data.oweb', $data);
            }else{
                $this->settings = $this->getSettings(OWEB_DIR_DATA.'/'.'SimpleIdentifiedArrays'.'/'.$this->name);
            }
            $this->decomposeId($this->id);
            $this->findPath($this->decomposed_id);
        }
    }

    protected function generateSettingsData(){
        return serialize($this->settings);
    }

    protected function getSettings($path){
        return unserialize(file_get_contents($path));
    }

    protected function decomposeId($id){
        $this->decomposed_id = array();
        do{
            $i = $id/10;
            $this->decomposed_id[] = $id - ($i*10);
            $id = $i;
        }while($id > 0);
        $this->decomposed_id = array_reverse($this->decomposed_id);
    }

    protected function findPath($decomposed_id){

        $size = sizeof($decomposed_id);
        $path = 0;
        for($i=0; $i < $size; $i++){
            $path .= '/'.$decomposed_id[$i];
        }
        $this->supPath = $this->path.$path;
    }


    public function addData($data){

        //Incoming data, where to put this s***
        //Let's see what data we saved before in the db
        $info = $this->loadInfo();
        if($info == null){

        }
    }

    public function loadInfo(){
        if(file_exists($this->supPath.'/info.xml')){
            return simplexml_load_file($this->supPath.'/info.xml');
        }else{
            return null;
        }
    }
}
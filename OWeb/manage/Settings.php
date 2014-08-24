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

namespace OWeb\manage;


use OWeb\Exception;
use OWeb\OWeb;

class Settings {

    private $_mainSettingFile = 'config.xml';

    private $_settingFiles = array();

    public function loadMainSettings(){
        return $this->loadFile($this->_mainSettingFile);
    }

    /**
     * Loads a different configuration file on top of the existing one
     *
     * @param $file
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function loadFile($file){

        if(!isset($this->_settingFiles[$file])){
            $f = array();

            if(file_exists(OWEB_DIR_CONFIG.'/'.$file))
                try{
                    $f = simplexml_load_file(OWEB_DIR_CONFIG.'/'.$file);
                }catch(\Exception $ex){
                    throw new Exception("Failed to load Settings file : '$file'", 0, $ex);
                }
            $this->_settingFiles[$file] = $f;
        }
        return $this->_settingFiles[$file];
    }



} 
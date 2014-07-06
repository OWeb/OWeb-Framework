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

    private $_mainSettingFile = 'config.ini';

    private $_settingFiles = array();

    private $_fileSettings;

    private $_classSetings;


    /**
     * The Setting array for you, in the default file or the file you asked it to check
     *
     * @param String $asker The namen or Object of the one who asks for the settings
     *
     * @return array()
     */
    public function getSetting($asker){

        if(is_string($asker))
            $name = $asker;
        else if(is_object($asker))
            $name = get_class($asker);

        if(!isset($this->_classSetings[$name])){

            $autoLoader = OWeb::getInstance()->getAutoLoader();

            $path = $autoLoader->getEquivalentPath($asker, 'config');

            $mainConfig = $this->loadFile($this->_mainSettingFile);

            $otherSettings = array();
            if($path != null){
                $otherSettings = $this->loadFile($path);
            }

            if(!isset($mainConfig[$name]))
                $mainConfig[$name] = array();

            $this->_classSetings[$name] = array_merge($mainConfig[$name], $otherSettings);
        }
        return $this->_classSetings[$name];
    }

    /**
     * Recoveres the value of the setting for that class(object)
     *
     * @param mixed $asker The object or class that asks the Information
     * @param $asked The name of the information to get
     *
     * @return mixed If information available string if not false
     */
    public function getDefSettingValue($asker, $asked){
        $settings = $this->getSetting($asker);
        return isset($settings[$asked]) ? $settings[$asked] : null;
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
    private function loadFile($file){

        if(!isset($this->_settingFiles[$file])){
            $f = array();
            if(file_exists($file))
                try{
                    $f = parse_ini_file($file, true);
                }catch(\Exception $ex){
                    throw new Exception("Failed to load Settings file : '$file'", 0, $ex);
                }
            $this->_settingFiles[$file] = $f;
        }
        return $this->_settingFiles[$file];
    }



} 
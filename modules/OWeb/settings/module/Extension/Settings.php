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

namespace OWeb\settings\module\Extension;

use OWeb\AutoLoader;
use OWeb\OWeb;
use OWeb\types\extension\Extension;

class Settings extends Extension{

    protected $_classSettings = array();


    protected function init()
    {
    }

    protected function ready()
    {

    }

    public function getClassSetting($class, $fileName = null){
        if(is_object($class))
            $class = get_class($class);

        if(!isset($this->_classSettings[$class])){
            $path = AutoLoader::getInstance()->getClassInfo($class)->explodedName;

            if ($fileName) {
                $settings = OWeb::getInstance()->getManageSettings()->loadFile($fileName)->class;
            }else {
                $settings = OWeb::getInstance()->getManageSettings()->loadMainSettings()->class;
            }
            $currentLevel = $settings;

            foreach($path as $name){
                if(isset($currentLevel->$name))
                    $currentLevel = $currentLevel->$name;
                else {
                    $this->_classSettings[$class] = null;
                }
            }
            $this->_classSettings[$class] = $currentLevel;
        }
        return $this->_classSettings[$class];
    }
}
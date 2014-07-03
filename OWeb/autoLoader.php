<?php
/**
 * @author       Oliver de Cramer (oliverde8 at gmail.com)
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

namespace OWeb;

//Need to be able to send exceptions
require_once __DIR__ . '/Exception.php';

//Nee to be able to store information about the file that were loaded
require_once __DIR__ . '/types/autoloader/FileInformation.php';

//Need to be able to store information about the classes that were loaded
require_once __DIR__ . '/types/autoloader/ClassInformation.php';


use OWeb\types\autoloader\ClassInformation;

class AutoLoader
{

    /**
     * @var AutoLoader Instance of running OWeb
     */
    private static $instance = null;

    /**
     * List of all the module pathes that the autoloader must work for module loading
     *
     * @var String[]
     */
    private $modulePathes;

    /**
     * @var ClassInformation[]
     */
    private $_classInfos = array();

    /**
     * Creating the autoload and registering it so that it works
     */
    public function __construct()
    {
        //Checking if There is an older instance. If yes Exception.
        if (self::$instance != null) {
            throw new Exception("A second instance of the autoloader can't be created");
        } else {
            self::$instance = $this;
        }

        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * Get the autoloader
     *
     * @return AutoLoader
     */
    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Handles the autoloading
     *
     * @var String name of the class to load
     *
     * @throws Exception if the file couldn't be loaded
     */
    public function autoload($class)
    {
        try {
            $this->loadClass($class);
        } catch (\Exception $ex) {
            throw new \OWeb\Exception("[AutoLoad]The class '$class' couldn't be loaded", 0, $ex);
        }
    }

    /**
     * Will include the class to the runtime
     *
     * @param $class
     *
     * @throws Exception if the file couldn't be found
     */
    protected function loadClass($class)
    {
        $info = $this->getClassInfo($class);

        if($info->modulePath == null){
            //OWeb framework main class, easy to load.
            $path = __DIR__ .'/../'. $info->relativePath . '.php';
            if(file_exists($path)){
                require_once $path;

                $info->fullPath = $path;
            }else
                throw new Exception('[AutoLoad]The OWeb FrameWork class : '.$class.' couldn\'t be find at : '.$path);
        }else{

            $found = false;
            foreach($this->modulePathes as $path){
                $file = $path.$info->modulePath.$info->relativePath.'.php';
                if(file_exists($file)){
                    require_once $path;

                    $found = true;
                    $info->fullPath = $path;
                }
                $info->possiblePaths[] = $file;
            }

            if(!$found){
                throw new Exception('[AutoLoad]The class : '.$class.' couldn\'t be find at : '.var_dump($info->possiblePaths[]));
            }
        }

    }

    /**
     * @param $class
     *
     * @return ClassInformation
     */
    public function getClassInfo($class)
    {
        if (!isset($this->_classInfos[$class])) {

            $nameStructure = explode('\\', $class);

            $classInfo = new ClassInformation();

            $classInfo->className = $class;

            $moduleFound = false;

            $firstPath = '';

            foreach ($nameStructure as $part) {
                if (!$moduleFound)
                    //If it has key word module then it is part of a module.
                    if ($part != 'module') {
                        $firstPath .= '/' . $part;
                    } else {
                        //Module found so separate pathes to module path and relative to module path
                        $moduleFound           = true;
                        $classInfo->modulePath = $firstPath;
                        $firstPath             = '';
                    }
                else {
                    $firstPath .= '/' . $part;
                }
            }

            $classInfo->relativePath = $firstPath;

            $this->_classInfos[$class] = $classInfo;
        }

        return $this->_classInfos[$class];
    }

} 
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

namespace OWeb\manage;


use OWeb\Exception;
use OWeb\types\event\CoreEvents;
use OWeb\types\extension\Extension as TypeExtension;

/**
 * Handles the extensions for OWeb
 *
 * @package OWeb\manage
 */
class Extension implements CoreEvents
{

    /**
     * @var TypeExtension[] loaded extension
     */
    private $_extensions = array();

    /**
     * @var TypeExtension[] List of plugins that will need to be initialized
     */
    private $_toInitialize = array();

    /**
     * @var Dispatcher
     */
    private $_dispatcher;

    /**
     * @var bool Did the initialization got called
     */
    private $_initialized = false;

    function __construct(Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->registerEvent(CoreEvents::name_OWeb_init, $this);
    }


    /**
     * Initialize the extensions that have been created before the initialisation
     */
    public function OWeb_init()
    {
        $this->_initialized = true;

        foreach ($this->_toInitialize as $extension) {

            $this->initExtension($extension);

        }
        $this->_toInitialize = array();
    }

    /**
     * Returns the object of the extension
     *
     * @param $extensionName The name of the extension to get
     *
     * @return TypeExtension | bool The extension or false if couldn't get it
     */
    public function getExtension($moduleName, $extensionName = null)
    {
        if ($extensionName == null) {
            $extensionName = $moduleName;
        } else {
            $extensionName = $moduleName . '\\module\\Extension\\' . $extensionName;
        }

        if (!isset($this->_extensions[$extensionName])) {
            $extension = $this->createExtension($extensionName);
            $this->registerExtension($extension, $extensionName);

            if ($extension == false) {
                return false;
            } else if ($this->_initialized) {
                $extension->OWeb_Init();
            } else {
                $this->_toInitialize[] = $extension;
            }

        } else if ($this->_initialized && !$this->_extensions[$extensionName]->isInitialized()) {
            $this->initExtension($this->_extensions[$extensionName]);
        }

        return $this->_extensions[$extensionName];
    }

    /**
     * @param string $extensionName
     *
     * @return TypeExtension | bool
     */
    protected function createExtension($extensionName)
    {

        $this->_dispatcher->dispatchEvent('OWeb_Extension_preCreateExtension', $extensionName);

        try {
            if(!class_exists($extensionName)){
                throw new \OWeb\types\extension\Exception('Unknown Extension : '.$extensionName);
            }
            $extension = new $extensionName($this);

            $this->_dispatcher->dispatchEvent('OWeb_Extension_postCreateExtension', $extensionName, $extension);

            return $extension;

        } catch (Exception $ex) {
            $this->_dispatcher->dispatchEvent('OWeb_Extension_postCreateExtension', $extensionName, null);
            return false;
        }
    }

    /**
     * @param TypeExtension $extension
     */
    protected function initExtension($extension)
    {
        $this->_dispatcher->dispatchEvent('OWeb_Extension_preInitExtension', $extension);

        $extension->OWeb_Init();

        $this->_dispatcher->dispatchEvent('OWeb_Extension_postInitExtension', $extension);
    }

    /**
     * Register the extension to the Extension manager.
     * It is here that we will check all the parents of the extension to Register for every parents it has.
     *
     * @param String $extension The Extension Object
     * @param Extension $name THe name of the Extension
     * @throws \OWeb\Exception	If the extension has been already loaded.
     */
    protected function registerExtension($extension, $name){

        $subExtensions = array();

        $subExtensions[$name] = $extension;
        $parent = get_parent_class($extension);

        while($parent != "" && $parent != "OWeb\\types\\Extension" ){
            if(isset($this->extensions[$parent])){
                throw new \OWeb\Exception("The plugin '$name' has already been registered as : ".get_class($this->extensions[$parent]));
            }
            $subExtensions[$parent] = $extension;
            $parent = get_parent_class($parent);
        }
        $this->obj_extension[$name] = $extension;
        $this->_extensions = array_merge($this->_extensions, $subExtensions);
    }
}
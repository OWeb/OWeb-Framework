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

namespace OWeb\types\extension;

use OWeb\manage\Extension as ExtensionManager;

abstract class Extension
{
    private $_initialized = false;

    private $_dependence;

    private $_params = array();

    private $_actions = array();

    private $_aliases = array();

    protected $extension;


    final function __construct(ExtensionManager $extension)
    {
        $this->extension = $extension;
    }

    final public function OWeb_Init()
    {
        $this->init();

        $this->_initialized = true;

        if (!empty($this->_dependence)) {
            foreach ($this->_dependence as $dep) {

                try {
                    $ext = $this->extension->getExtension($dep->name);
                    if (!$ext) {
                        throw new \OWeb\Exception("");
                    } else {
                        $name2 = \str_replace('\\', '_', $dep->name);
                        $name = "ext_" . $name2;
                        $this->$name = $ext;
                    }
                } catch (\OWeb\Exception $exception) {
                    throw new \OWeb\Exception("Couldn't load extension : " . $dep->name . ".The extension " . get_class($this) . " needs it to work", 0, $exception);
                }
            }
        }
        $this->ready();
    }

    abstract protected function init();

    abstract protected function ready();


    protected function addDependance($plugin_name)
    {
        $dep = new Depandence($plugin_name);
        $this->_dependence[] = $dep;
    }

    /**
     * Registers an action that the controller might do
     *
     * @param $action   string the name of the action
     * @param $nom_func The function to call when the action is executed
     */
    protected function addAction($action, $nom_func)
    {
        $this->_actions[$action] = $nom_func;
    }

    /**
     * Resets all actions
     */
    protected function resetActionsAll()
    {
        $this->_actions = array();
    }

    /**
     * Removes an action from the action list
     *
     * @param $actionName string The action name to be removed
     */
    protected function removeAction($actionName)
    {
        if (isset($this->_actions[$actionName]))
            unset($this->_actions[$actionName]);
    }

    /**
     * Executes the action
     * This will call the function registered earlier
     *
     * @param $actionName String the name of the action to execute
     *
     * @return mixed
     */
    public function doAction($actionName)
    {
        if (isset($this->_actions[$actionName]))
            return call_user_func_array(array($this, $this->_actions[$actionName]), array());
    }

    /**
     * Automatically loads parameters throught PHP get and Post variables
     */
    public function loadParams()
    {
        $this->_params = \OWeb\OWeb::getInstance()->get_get();
    }

    /**
     * Thiw will activate the usage f the configuration files.
     */
    protected function initSettings()
    {
        $this->initRecSettings(get_class($this));
    }

    public function addAlias($aliasName, $funcName)
    {
        $this->_aliases[$aliasName] = $funcName;
    }

    public function getAlias($name)
    {
        return isset($this->_aliases[$name]) ? $this->_aliases[$name] : null;
    }

    public function isInitialized(){
        return $this->_initialized;
    }
} 
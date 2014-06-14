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
namespace OWeb\types;

use OWeb\manage\Extensions;
use OWeb\types\extension\Depandence;

/**
 * Description of Extension
 *
 * @author De Cramer Oliver
 */
abstract class Extension extends \OWeb\utils\Singleton
{

    private $dependence;

    private $params = array();

    private $actions = array();

    private $aliases = array();

    protected $settings = array();

    abstract protected function init();

    public function OWeb_Init()
    {
        if (!empty($this->dependence)) {
            foreach ($this->dependence as $dep) {

                try {
                    $ext = Extensions::getInstance()->getExtension($dep->name);
                    if (!$ext) {
                        throw new \OWeb\Exception("");
                    } else {
                        $name2       = \str_replace('\\', '_', $dep->name);
                        $name        = "ext_" . $name2;
                        $this->$name = $ext;
                    }
                } catch (\OWeb\Exception $exception) {
                    throw new \OWeb\Exception("Impossible de charger l'extension : " . $dep->name . ". L'extention " . get_class(
                        $this
                    ) . " en a basoin pour fonctionner", 0, $exception);
                }
            }
        }
        $this->init();
    }

    public function add_Event($Nomevent, $fonction)
    {
        \OWeb\manage\Events::getInstance()->registerEvent($Nomevent, $this, $fonction);
    }

    protected function addDependance($plugin_name)
    {
        $dep                = new Depandence($plugin_name);
        $this->dependence[] = $dep;
    }

    /**
     * Registers an action that the controller might do
     *
     * @param $action   string the name of the action
     * @param $nom_func The function to call when the action is executed
     */
    protected function addAction($action, $nom_func)
    {
        $this->actions[$action] = $nom_func;
    }

    /**
     * Resets all actions
     */
    protected function resetActionsAll()
    {
        $this->actions = array();
    }

    /**
     * Removes an action from the action list
     *
     * @param $actionName string The action name to be removed
     */
    protected function removeAction($actionName)
    {
        if (isset($this->actions[$actionName]))
            unset($this->actions[$actionName]);
    }

    /**
     * Executes the action
     * This will call the function registered earlier
     *
     * @param $actionName String the name of the action to execute
     */
    public function doAction($actionName)
    {
        if (isset($this->actions[$actionName]))
            return call_user_func_array(array($this, $this->actions[$actionName]), array());
    }

    /**
     * Automatically loads parameters throught PHP get and Post variables
     */
    public function loadParams()
    {
        $this->params = \OWeb\OWeb::getInstance()->get_get();
    }

    /**
     * Thiw will activate the usage f the configuration files.
     */
    protected function initSettings()
    {
        $this->initRecSettings(get_class($this));
    }


    private function initRecSettings($name)
    {
        $settingManager = \OWeb\manage\Settings::getInstance();

        $this->settings = array_merge(
            $this->settings,
            $settingManager->getSetting($name, $this->get_exploded_nameOf($name))
        );

        $parent = get_parent_class($name);

        if ($parent != 'OWeb\types\Extension' && $parent != '\OWeb\types\Extension')
            $this->initRecSettings($parent);
    }

    public function addAlias($aliasName, $funcName)
    {
        $this->aliases[$aliasName] = $funcName;
    }

    public function getAlias($name)
    {
        return isset($this->aliases[$name]) ? $this->aliases[$name] : null;
    }

    /**
     * Gets the value of a parameter
     *
     * @param $paramName String The name of the parameter of whom the value is asked
     *
     * @return mixed The value of the parameter or if parameter doesn't exist Null
     */
    public function getParam($paramName)
    {
        if (isset($this->params[$paramName]))
            return $this->params[$paramName];
        else
            return null;
    }

    public function getParams()
    {
        return $this->params;
    }
}

?>

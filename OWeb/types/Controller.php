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


use OWeb\Exception;
use OWeb\OWeb;
use OWeb\web\displayMode\module\Model\UrlInterface;
use OWeb\web\header\module\Extension\Header;
use OWeb\web\header\module\Model\HeaderInterface;

abstract class Controller implements HeaderInterface, UrlInterface
{

    const ACTION_GET = 1;
    const ACTION_POST = 2;
    const ACTION_DOUBLE = 3;
    const ACTION_CUSTOM = 4;

    private static $instance = array();

    /**
     * @var The action mode of the controller
     */
    private $_actionMode;

    /**
     * @var bool if the controller is the primart controller
     */
    private $_isPrimaryController;

    /**
     * @var TemplateController If the controller needs to appear in an template
     */
    protected $templateController = null;

    /**
     * @var \SplDoublyLinkedList List of all the dependences
     */
    protected $dependence;

    /**
     * @var array The settings
     */
    protected $settings = array();

    /**
     * @var array
     */
    private $_params = array();

    /**
     * @var array
     */
    private $_actions = array();

    /**
     * The path to the view file to load
     *
     * @var null
     */
    private $_viewFile = null;

    public static function getInstance($new = false)
    {
        $class = get_called_class();

        if (isset(self::$instance[$class]) || $new) {
            /** @var Controller $controller */
            $controller = new $class();
            self::$instance[$class] = $controller;
            $controller->init();
        }

        return self::$instance[$class];
    }

    /**
     * Creating a controller for OWeb
     *
     * @param bool $primary Is this the primary Controller
     */
    function __construct($primary = false)
    {
        //Default action mode is get
        $this->_actionMode = self::ACTION_GET;

        $this->_isPrimaryController = $primary;

        $this->dependence = new \SplDoublyLinkedList();

        $this->addDependance('OWeb\web\header\module\Extension\Header');
    }

    /**
     * Called after construction when OWeb is ready.
     *
     * @return void
     */
    abstract public function init();

    /**
     * Called before displaying the window
     *
     * @return mixed
     */
    abstract protected function onDisplay();

    /**
     * Allows you to apply a Template to this controller.
     *
     * @param $ctr The name of the template controller or the object it self
     */
    public function applyTemplateController($ctr)
    {
        if ($ctr instanceof TemplateController) {
            $this->templateController = $ctr;

            //array_merge($this->settings, $this->templateController->getSettings());

            /*$lang = $this->templateController->getLanguageStrings();
            if($lang != null)
                if($this->language == null)
                    $this->language = new Language ();
            $this->language->merge($lang);*/
        } else {
            $this->templateController = SubViews::getInstance()->getSubView($ctr);
        }
    }

    /**
     * Will allow an extension to be loaded and give easy acces to the extension tools
     *
     * @param string $extension_name
     *
     * @throws \OWeb\Exception
     */
    protected function addDependance($extension_name)
    {
        try {
            if (is_object($extension_name)) {
                $ext = $extension_name;
                $extension_name = get_class($extension_name);
            } else
                $ext = OWeb::getInstance()->getManageExtensions()->getExtension($extension_name);

            if ($ext) {
                $this->dependence->push($ext);
            } else {
                throw new Exception("The extension: " . $extension_name . " Couldn't be loaded. The controller " . get_class($this) . " needs it to work");
            }
        } catch (Exception $exception) {
            throw new Exception("The extension: " . $extension_name . " Couldn't be loaded. The controller " . get_class($this) . " needs it to work", 0, $exception);
        }
    }

    public function getDependences()
    {
        return $this->dependence;
    }

    /**
     * Handles call to alias functions to the extensions the controller depends on
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     * @throws \OWeb\Exception
     */
    public function __call($name, $arguments)
    {
        for ($this->dependence->rewind(); $this->dependence->valid(); $this->dependence->next()) {
            $current = $this->dependence->current();
            $alias = $current->getAlias($name);
            if ($alias != null) {
                return call_user_func_array(array($current, $alias), $arguments);
            }
        }
        throw new \OWeb\Exception("The function: " . $name . " doesen't exist and couldn't be find in any extension to whom the plugin depends", 0);
    }


    /**
     * Automatically loads parameters throught PHP get and Post variables
     */
    public function loadParams()
    {
        switch ($this->_actionMode) {
            case self::ACTION_DOUBLE :
                $a = array_merge(OWeb::getInstance()->getPost(), OWeb::getInstance()->getGet());
                break;
            case self::ACTION_GET :
                $a = OWeb::getInstance()->getGet();
                break;
            case self::ACTION_POST :
                $a = OWeb::getInstance()->getPost();
                break;
        }
        $this->_params = $a;
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
     * Adds a parameter manually to the Controller. This is used if the controller is used as a SubView
     *
     * @param $paramName String The name of the parameter
     * @param $value mixed THe value of the parameter
     * @return $this
     */
    public function addParams($paramName, $value) {
        $this->_params[$paramName] = $value;
        return $this;
    }

    /**
     * Gets the value of a parameter
     *
     * @param $paramName String The name of the parameter of whom the value is asked
     * @return mixed The value of the parameter or if parameter doesn't exist Null
     */
    public function getParam($paramName) {
        if (isset($this->_params[$paramName]))
            return $this->_params[$paramName];
        else
            return null;
    }

    public function getParams(){
        return $this->_params;
    }

    /**
     * Registers an event to whom this controller needs to respond
     *
     * @param $eventName String THe name of the event to whom it will respond
     */
    protected function registerEvent($eventName)
    {
        OWeb::getInstance()->getManageEvents()->registerEvent($eventName, $this);
    }

    /**
     * This will read the settings file and get the data that for this controller
     */
    protected function initSettings(){
        $this->initRecSettings(get_class($this));
    }

    private function initRecSettings($name){
        $settingManager = OWeb::getInstance()->getManageSettings();
        $this->settings = array_merge($this->settings, $settingManager->getSetting($name));

        $parent = get_parent_class($name);

        if ($parent != 'OWeb\types\Controller' && $parent != '\OWeb\types\Controller')
            $this->initRecSettings($parent);
    }

    public function display($ctr = null){
        if($this->templateController == null){
            $this->prepareView($ctr);
            $this->forceDisplay($ctr);
        }else{
            $this->templateController->setCtrToShow($this);
            $this->templateController->prepareView();
            $this->prepareView();
            $this->templateController->templatedisplay($this);
        }
    }

    public function prepareView($ctr = null){
        if ($ctr == null)
            $ctr = get_class($this);

        $this->_viewFile = $this->prepareViewRec($ctr);

        //Second we ask our controller to prepare anything needed to be show in the page
        $this->onDisplay();
    }

    /**
     * Find the view file
     *
     * @param null $ctr
     *
     * @return mixed
     *
     * @throws \OWeb\Exception
     */
    private function prepareViewRec($ctr = null){

        $file = OWeb::getInstance()->getAutoLoader()->getEquivalentPath($ctr, 'view');

        if($file == null && $ctr != 'OWeb\types\Controller'){
            return $this->prepareViewRec(get_parent_class($ctr));
        }elseif($file == null){
            throw new Exception('View file couldn\'t be found for controller : '.get_class($this));
        }

        return $file;
    }

    /**
     * Displays the controllers view
     *
     * @param null $ctr The name the controller that made the call. It might be an parent controller
     */
    public function forceDisplay($ctr = null) {

        if($this->_viewFile == null){
            echo $this->_viewFile;
            $this->prepareView($ctr);
        }

        include $this->_viewFile;
    }
} 
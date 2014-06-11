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
use OWeb\manage\Languages;
use OWeb\manage\SubViews;

/**
 * Is among the main bricks of OWeb,
 * It can be used as subView or as a main Page. Never create a Controller by yourself, OWeb will create them for you.
 * To use a Controller as a sub view just use the SubViews manager!
 *
 * @author De Cramer Oliver
 */
abstract class Controller extends NamedClass implements Configurable, InterfaceExtensionDependable
{

    const ACTION_GET    = 1;
    const ACTION_POST   = 2;
    const ACTION_DOUBLE = 3;
    const ACTION_CUSTOM = 4;

    protected $action_mode;

    protected $dependence;

    private $params = array();
    private $actions = array();

    private $language;

    public $view = null;

    private $primaryController = false;

    protected $templateController = null;

    protected $settings = array();

    protected $viewReady = false;

    abstract public function init();

    abstract public function onDisplay();

    /**
     * @param bool $primary Is this a primary controller or a subview?
     */
    final function __construct($primary = false)
    {
        $this->action_mode       = self::ACTION_GET;
        $this->language          = new \OWeb\types\Language();
        $this->primaryController = $primary;
        $this->dependence        = new \SplDoublyLinkedList();
        $this->addDependance('core\url\Generator');
    }

    final function initController()
    {
        $this->init();
    }

    /**
     * Allows you to apply a Template to this controller.
     *
     * @param $ctr The name of the template controller or the object it self
     */
    public function applyTemplateController($ctr)
    {
        if ($ctr instanceof TemplateController) {
            $this->templateController = $ctr;

            array_merge($this->settings, $this->templateController->getSettings());

            $lang = $this->templateController->getLanguageStrings();
            if ($lang != null)
                if ($this->language == null)
                    $this->language = new Language ();
            $this->language->merge($lang);
        } else {
            $this->templateController = SubViews::getInstance()->getSubView($ctr);
        }
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


    protected function addDependance($extension_name)
    {
        try {
            if (is_object($extension_name)) {
                $ext            = $extension_name;
                $extension_name = get_class($extension_name);
            } else
                $ext = Extensions::getInstance()->getExtension($extension_name);

            if (!$ext) {
                throw new \OWeb\Exception("");
            } else {
                $this->dependence->push($ext);
            }
        } catch (\OWeb\Exception $exception) {
            throw new \OWeb\Exception("The extension: " . $extension_name . " Couldn't be loaded. L'The controller " . get_class(
                $this
            ) . " needs it to work", 0, $exception);
        }
    }

    public function __call($name, $arguments)
    {
        for ($this->dependence->rewind(); $this->dependence->valid(); $this->dependence->next()) {
            $current = $this->dependence->current();
            $alias   = $current->getAlias($name);
            if ($alias != null) {
                return call_user_func_array(array($current, $alias), $arguments);
            }
        }
        throw new \OWeb\Exception("The function: " . $name . " doesen't exist and couldn't be find in any extension to whom the plugin depends", 0);
    }

    public function getDependences()
    {
        return $this->dependence;
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
     * Registers an event to whom this controller needs to respond
     *
     * @param $eventName String THe name of the event to whom it will respond
     * @param $funcName  The name of the function to call when the event happens
     */
    protected function registerEvent($eventName, $funcName)
    {
        \OWeb\manage\events::getInstance()->registerEvent($eventName, $this, $funcName);
    }

    /**
     * Automatically loads parameters throught PHP get and Post variables
     */
    public function loadParams()
    {
        switch ($this->action_mode) {
            case self::ACTION_DOUBLE :
                $a = array_merge(\OWeb\OWeb::getInstance()->get_post(), \OWeb\OWeb::getInstance()->get_get());
                break;
            case self::ACTION_GET :
                $a = \OWeb\OWeb::getInstance()->get_get();
                break;
            case self::ACTION_POST :
                $a = \OWeb\OWeb::getInstance()->get_post();
                break;
        }
        $this->params = $a;
    }

    /**
     * Adds a parameter manually to the Controller. This is used if the controller is used as a SubView
     *
     * @param $paramName String The name of the parameter
     * @param $value     mixed THe value of the parameter
     *
     * @return $this
     */
    public function addParams($paramName, $value)
    {
        $this->params[$paramName] = $value;

        return $this;
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

    /**
     * This will activate the usage of the language file
     * This means the controller will support multi language.
     */
    /*protected function InitLanguageFile() {
            $this->language->init($this);
    }
*/
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

        if ($parent != 'OWeb\types\Controller' && $parent != '\OWeb\types\Controller')
            $this->initRecSettings($parent);
    }

    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Thiw will activate the usage f the configuration files.
     */
    protected function InitLanguageFile()
    {
        if ($this->language == null)
            $this->language = new Language();

        $this->InitRecLanguageFile(get_class($this));
    }


    private function InitRecLanguageFile($name, Language $lang = null)
    {
        $lManager = \OWeb\manage\Languages::getInstance();

        $l = $lManager->getLanguage($name, $this->get_exploded_nameOf($name));

        if ($lang == null) {
            $this->language = clone $l;
            $lang           = $this->language;
        } else {
            $lang->merge($l);
        }

        $parent = get_parent_class($name);

        if ($parent != 'OWeb\types\Controller' && $parent != '\OWeb\types\Controller')
            $this->InitRecLanguageFile($parent, $lang);
    }

    public function getLanguageStrings()
    {
        return $this->language;
    }

    /**
     * If LanguageFile is initialised then this will return the text in the current language
     *
     * @param $name String The name of the text demanded
     *
     * @return string The text demanded in the correct language
     */
    protected function getLangString($name)
    {
        return $this->language->get($name);
    }

    /**
     * If LanguageFile is initialised then this will return the text in the current language
     *
     * @param $name String The name of the text demanded
     *
     * @return string The text demanded in the correct language
     */
    protected function l($name)
    {
        return $this->language->get($name);
    }

    /**
     * @return String The current language
     */
    protected function getLang()
    {
        return $this->language->getLang();
    }

    /**
     * @return boolean
     */
    public function isPrimaryController()
    {
        return $this->primaryController;
    }


    public function prepareView($ctr = null)
    {
        if ($ctr == null)
            $ctr = get_class($this);

        //Getting the path to the controller
        $path = self::get_relative_pathOf($ctr);

        $path1 = OWEB_DIR_VIEWS . '/' . $this->get_exploded_numOf($ctr, 0);
        $path2 = OWEB_DIR_MAIN . '/defaults/views' . '/' . $this->get_exploded_numOf($ctr, 0);

        if (file_exists($path1 . $path)) {
            $path = $path1 . $path;
        } else if (file_exists($path2 . $path)) {
            $path = $path2 . $path;
        } else {
            if ($ctr != '\OWeb\types\Controller') {
                //Well this controller doesn't have a View let's see if the parent has a nice view to display
                $this->prepareView(get_parent_class($ctr));

                return;
            } else {
                throw new \OWeb\manage\exceptions\Controller('Couldn\'t Find View of controller in : \'' . $path1 . $path . '\' , \'' . $path2 . $path . '\'.');
            }
        }

        $this->viewReady = true;

        //First we create the Default view
        $this->view = $this->getView($path);

        $this->view->setDependences($this->dependence);

        //Second we ask our controller to prepare anything needed to be show in the page
        $this->onDisplay();
    }

    /**
     * Displays the controllers view
     *
     * @param null $ctr The name the controller that made the call. It might be an parent controller
     *
     * @throws \OWeb\manage\exceptions\Controller
     */
    public function forceDisplay($ctr = null)
    {
        if (!$this->viewReady) {
            $this->prepareView($ctr);
        }
        //Maintenant on fait l'affichage
        $this->view->display();
    }

    public function display($ctr = null)
    {

        if ($this->templateController == null) {
            $this->prepareView($ctr);
            $this->forceDisplay($ctr);
        } else {
            $this->templateController->setCtrToShow($this);
            $this->templateController->prepareView();
            $this->prepareView();
            $this->templateController->templatedisplay($this);
        }
    }

    protected function getView($path)
    {
        return new \OWeb\types\View(get_class($this), $path, $this->language);
    }

    /**
     * @return null
     */
    public function getTemplateController()
    {
        return $this->templateController;
    }

}

?>

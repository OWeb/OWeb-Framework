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
use OWeb\manage\controller\Events;
use OWeb\OWeb;
use OWeb\types\Controller as TypeController;
use OWeb\types\event\CoreEvents;

/**
 *
 * Manages the initiation of the main controller represented in the page directory.
 * Will also manage the action the main controller needs to do.
 *
 * @author De Cramer Oliver
 */
class Controller implements CoreEvents
{

    /**
     * @var TypeController
     */
    private $_controller = null;

    function __construct(Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->registerEvent(CoreEvents::name_OWeb_init, $this);
    }


    /**
     * Will initialize the Controller and will do the Actions to which the Controller has register
     *
     */
    public function OWeb_init()
    {

        if ($this->_controller == null) return;

        $this->_dispatcher->dispatchEvent(Events::EVENT_preInitController, $this->_controller);

        $this->_controller->init();

        $this->_dispatcher->dispatchEvent(Events::EVENT_postInitController, $this->_controller);

        $this->_dispatcher->dispatchEvent(Events::EVENT_preActionDispatch, $this->_controller);

        //gestion des Actions...
        $source[] = OWeb::getInstance()->getGet();
        $source[] = OWeb::getInstance()->getPost();

        foreach ($source as $get) {
            if (isset($get['action']))
                $this->_controller->doAction($get['action']);

            $i = 1;
            while (isset($get['action_' . $i])) {
                $this->_controller->doAction($get['action_' . $i]);
                $i++;
            }
        }

        $this->_dispatcher->dispatchEvent(Events::EVENT_postActionDispatch, $this->_controller);
    }


    /**
     * Will load the Controller as main controller.
     * Will automatically set up the initialisation sequence for the Controller.
     * The Controller will be initialized once OWeb has finished initialisation.
     *
     * @param \String $name of the Controller to load.
     *
     * @return \OWeb\types\Controller Loaded Controller
     *
     * @throws Exception If there is a error to the loading of the Controller
     */
    public function loadController($name)
    {
        try {
            if (!class_exists($name)) {
                throw new Exception("The Controller doesen't exist", 0);
            }

            $controller = new $name(true);
            $this->_controller = $controller;
            if (!($controller instanceof \OWeb\types\Controller))
                throw new Exception("A Controller needs to be an instance of \\OWeb\\Types\\Controller");


        } catch (\Exception $ex) {
            throw new Exception("The Controller couldn't be loaded due to Errors", 0, $ex);
        }

        return $this->_controller;
    }

    public function loadException($exception)
    {
        $templateManager = $this->_controller->getTemplateController();

        unset($this->_controller);
        $this->_controller = null;

        $ctr = $this->loadController('Controller\OWeb\Exception');
        $ctr->addParams('exception',$exception);
        $ctr->init();
        if ($templateManager != null) {
            $ctr->applyTemplateController($templateManager);
        }
        return $ctr;
    }

    /**
     * Will start the display sequence of the controller.
     * First will prepare controller for display.
     * Then it will ask the controller to display it's View.
     *
     * @throws Exception If there is a problem about Displaying the Controller
     */
    public function display()
    {
        if ($this->_controller != NULL) {
            try {
                $this->_controller->display();
            } catch (\Exception $ex) {
                throw new Exception("The Controller couldn't be shown due to Errors", 0, $ex);
            }
        } else {
            throw new Exception("A Controller wasn't loaded to be shown");
        }
    }
}
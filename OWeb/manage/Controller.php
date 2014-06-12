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

use OWeb\manage\Events;

/**
 *
 * Manages the initiation of the main controller represented in the page directory.
 * Will also manage the action the main controller needs to do.
 *
 * @author De Cramer Oliver
 */
class Controller extends \OWeb\utils\Singleton
{

    private $controller = null;

    private $loaded = false;

    private $excpetionLoaded = false;

    function __construct()
    {
        $events = \OWeb\manage\Events::getInstance();
        $events->registerEvent('Init@OWeb', $this, 'initController');
    }


    /**
     * Will initialize the Controller and will do the Actions to which the Controller has register
     *
     * @param \OWeb\types\Controller $controller The controller to Initialize
     */
    public function initController(\OWeb\types\Controller $controller = null)
    {

        if ($this->controller == null)
            return;

        Events::getInstance()->sendEvent('Init_Prepare@OWeb\manage\Controller', $this->controller);

        $this->controller->initController();

        Events::getInstance()->sendEvent('Init_Done@OWeb\manage\Controller', $this->controller);

        Events::getInstance()->sendEvent('ActionDist_Prepare@OWeb\manage\Controller', $this->controller);

        //gestion des Actions...
        $source[] = \OWeb\OWeb::getInstance()->get_get();
        $source[] = \OWeb\OWeb::getInstance()->get_post();

        foreach ($source as $get) {
            if (isset($get['action']))
                $this->controller->doAction($get['action']);

            $i = 1;
            while (isset($get['action_' . $i])) {
                $this->controller->doAction($get['action_' . $i]);
                $i++;
            }
        }

        Events::getInstance()->sendEvent('ActionDist_Done@OWeb\manage\Controller', $this->controller);
    }

    /**
     * Will load the Controller as main controller.
     * Will automaticlly set up the initialisation sequence for the Controller.
     * The Controller will be initialized once OWeb has finished initialisation.
     *
     * @param \String $name of the Controller to load.
     *
     * @return \OWeb\types\Controller Loaded Controller
     * @throws \OWeb\manage\exceptions\Controller If there is a error to the loading of the COntroller
     */
    public function loadController($name)
    {

        if ($this->controller != null) {
            throw new \OWeb\manage\exceptions\Controller("A Controller was already loaded");
        } else {

            try {
                $controller       = new $name(true);
                $this->controller = $controller;
                if (!($controller instanceof \OWeb\types\Controller))
                    throw new \OWeb\manage\exceptions\Controller("A Controller needs to be an instance of \\OWeb\\Types\\Controller");

                \OWeb\manage\Events::getInstance()->sendEvent('loaded@OWeb\manage\Controller', $this->controller);

            } catch (\Exception $ex) {
                throw new \OWeb\manage\exceptions\Controller("The Controller couldn't be loaded due to Errors", 0, $ex);
            }
        }

        return $this->controller;
    }

    public function loadException($exception)
    {
        $templateManager = $this->controller->getTemplateController();

        unset($this->controller);
        $this->controller = null;

        $ctr = $this->loadController('Controller\OWeb\Exception');
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
     * @throws \OWeb\manage\exceptions\Controller If there is a problem about Displaying the Controller
     */
    public function display()
    {
        if ($this->controller != null) {
            try {
                $this->controller->display();
            } catch (\Exception $ex) {
                throw new \OWeb\manage\exceptions\Controller("The Controller couldn't be shown due to Errors", 0, $ex);
            }
        } else {
            throw new \OWeb\manage\exceptions\Controller("A Controller wasn't loaded to be shown");
        }
    }


}

?>

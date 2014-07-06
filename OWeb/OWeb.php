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

use OWeb\manage\Controller;
use OWeb\manage\Dispatcher;
use OWeb\manage\Extension;
use OWeb\manage\Settings;
use OWeb\types\event\CoreEvents;
use OWeb\web\displayMode\module\Extension\PageDisplayHandler;

define('OWEB_DIR', __DIR__);

define('OWEB_VERSION', '0.4.0');

if (!defined('OWEB_DIR_TEMPLATES')) define('OWEB_DIR_TEMPLATES', 'templates/');

if (!defined('OWEB_DIR_DATA')) define('OWEB_DIR_DATA', 'sources/data');

// Les Fichier pour le HTML par Default
if (!defined('OWEB_HTML_DIR_CSS')) define('OWEB_HTML_DIR_CSS', 'sources/css');
if (!defined('OWEB_HTML_DIR_JS')) define('OWEB_HTML_DIR_JS', 'sources/js');

if (!defined('OWEB_HTML_URL_IMG')) define('OWEB_HTML_URL_IMG', 'sources/files');

/**
 * Including autoloader manually.
 */
require_once __DIR__ . '/autoLoader.php';

/**
 * The main OWeb class that runs the wheel of time and this the world
 *
 * @package OWeb
 */
class OWeb
{

    /**
     * @var OWeb Instance of running OWeb
     */
    private static $instance = null;

    /**
     * base PHP variables
     *
     * @var Array
     */
    private $_get, $_post, $_files, $_cookies, $_server, $_adresse;

    /**
     * @var AutoLoader
     */
    private $_autoLoader;

    /**
     * @var Dispatcher
     */
    private $_manageEvents;

    /**
     * @var manage\Extension
     */
    private $_manageExtensions;

    /**
     * @var PageDisplayHandler
     */
    private $_displayExtension;

    /**
     * @var Controller
     */
    private $_manageController;

    /**
     * @var Settings
     */
    private $_manageSettings;

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @param array $get
     * @param array $post
     * @param array $files
     * @param array $cookies
     * @param array $server
     * @param array $adr
     */
    public function __construct(&$get, &$post, &$files, &$cookies, &$server, $adr)
    {

        self::$instance = $this;

        /**
         * Starting instance Run time
         */
        $this->getRunTime();

        /**
         * Affecting base PHP variables
         */
        $this->_get = $get;
        $this->_post = $post;
        $this->_files = $files;
        $this->_cookies = $cookies;
        $this->_server = $server;
        $this->_adresse = $adr;

        $this->_autoLoader = new AutoLoader();
        $this->_autoLoader->addModulePath(dirname(__DIR__) . '/modules');
        $this->_autoLoader->addPagePath(dirname(__DIR__) . '');

        $this->_manageEvents = new Dispatcher();

        $this->_manageExtensions = new Extension($this->_manageEvents);

        $this->_manageController = new Controller($this->_manageEvents);

        $this->_manageSettings = new Settings();
    }

    public function init()
    {

        $this->_displayExtension = $this->_manageExtensions->getExtension('OWeb\web\displayMode\module\Extension\PageDisplayHandler');

        $this->_manageEvents->dispatchEvent(CoreEvents::name_OWeb_init);
    }

    public function start()
    {
        $this->_displayExtension->display();
    }

    public function getRunTime()
    {
        static $a;
        if ($a == 0) {
            $a = microtime(true);
            return 0;
        } else return microtime(true) - $a;
    }

    /*
    * Returns the runtime in seconds.
    *
    * @param int [=3] The require precision.
    * @return String The run time with te demanded precision
    */
    public function getStringRuntTime($precision = 3)
    {
        return number_format($this->getRunTime(), $precision);
    }

    /**
     * @return Array
     */
    public function getAdresse()
    {
        return $this->_adresse;
    }

    /**
     * @return \OWeb\AutoLoader
     */
    public function getAutoLoader()
    {
        return $this->_autoLoader;
    }

    /**
     * @return Array
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * @return Array
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * @return Array
     */
    public function getGet()
    {
        return $this->_get;
    }

    /**
     * @return \OWeb\manage\Dispatcher
     */
    public function getManageEvents()
    {
        return $this->_manageEvents;
    }

    /**
     * @return \OWeb\manage\Extension
     */
    public function getManageExtensions()
    {
        return $this->_manageExtensions;
    }

    /**
     * @return \OWeb\web\displayMode\module\Extension\PageDisplayHandler
     */
    public function getDisplayExtension()
    {
        return $this->_displayExtension;
    }

    /**
     * @return \OWeb\manage\Controller
     */
    public function getManageController()
    {
        return $this->_manageController;
    }

    /**
     * @param \OWeb\manage\Settings $manageSettings
     */
    public function setManageSettings($manageSettings)
    {
        $this->_manageSettings = $manageSettings;
    }

    /**
     * @return \OWeb\manage\Settings
     */
    public function getManageSettings()
    {
        return $this->_manageSettings;
    }


    /**
     * @return Array
     */
    public function getPost()
    {
        return $this->_post;
    }

    /**
     * @return Array
     */
    public function getServer()
    {
        return $this->_server;
    }


}
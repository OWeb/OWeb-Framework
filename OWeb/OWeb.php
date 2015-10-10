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

use OWeb\log\module\Extension\Log;
use OWeb\manage\Controller;
use OWeb\manage\Dispatcher;
use OWeb\manage\Extension;
use OWeb\manage\Settings;
use OWeb\types\event\CoreEvents;
use OWeb\utils\SimpleArray;

define('OWEB_DIR', __DIR__);

define('OWEB_FRAMEWORK_DIR',dirname( __DIR__));

define('OWEB_VERSION', '0.5.0');

// Path to put OWEB data into.
if (!defined('OWEB_DIR_DATA')) define('OWEB_DIR_DATA', 'sources/data');

// Path for OWebs configuration.
if (!defined('OWEB_DIR_CONFIG')) define('OWEB_DIR_CONFIG', 'config');

// Path to all public files.
if (!defined('OWEB_HTML_DIR_PUBLIC')) define('OWEB_HTML_DIR_CSS', 'sources/');

if (!defined('OWEB_NEW_LINE')) define('OWEB_NEW_LINE', "\n\r");

/**
 * Including OWebs autoloader manually.
 */
require_once __DIR__ . '/autoLoader.php';

/**
 * Once oweb's autoloader is included include composer autoloader for the rest.
 *
 * OWebs autoloader has a multi level loading system & loads module related information as well,
 * therefore it must be the one loading OWeb related classes.
 *
 * @TODO This method of inclusin might not be the best solution.
 */
require_once 'vendor/autoload.php';

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
     * @var Log
     */
    private $_manageLogs;

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
    public function __construct(&$get, &$post = array(), &$files = array(), &$cookies = array(), &$server = array(), $adr = 'default')
    {

        self::$instance = $this;

        /**
         * Starting instance Run time
         */
        $this->getRunTime();

        // Start the autoloader with default paths.
        $this->_autoLoader = new AutoLoader();
        $this->_autoLoader->addModulePath(dirname(__DIR__) . '/modules');
        $this->_autoLoader->addPagePath(dirname(__DIR__) . '');

        /**
         * Affecting base PHP variables
         */
        $this->_get = new SimpleArray($get);
        $this->_get->makeReadOnly();
        $this->_post = new SimpleArray($post);
        $this->_post->makeReadOnly();
        $this->_files = new SimpleArray($files);
        $this->_files->makeReadOnly();
        $this->_cookies = new SimpleArray($cookies);
        $this->_cookies->makeReadOnly();
        $this->_server = new SimpleArray($server);
        $this->_server->makeReadOnly();
        $this->_adresse = $adr;

        // The even manager will allo us to dispatch events for the core to work.
        $this->_manageEvents = new Dispatcher();

        // Extensions is the heart of OWeb so need to menage to start working them out.
        $this->_manageExtensions = new Extension($this->_manageEvents);

        // We also need to load settings
        $this->_manageSettings = new Settings();
    }

    /**
     * Call this once you have altered autoloade or other settings to really start the magic.
     *
     * @param string $extensionToLoad
     *   The extension to load as main extension (frontController/Console ...)
     */
    public function init($extensionToLoad = NULL)
    {
        $settings = $this->_manageSettings->loadMainSettings();

        if(array($settings->OWeb->extensions->extension)){
            foreach($settings->OWeb->extensions->extension as $extension){
                $extensionStatus = $this->_manageExtensions->getExtension((string)$extension['name']);
                if (!$extensionStatus) {
                    die("Extension in settings couldn't be loaded : '".(string)$extension['name']);
                }
            }
        }

        // By default OWeb always loads a log Extension start it up now.
        $this->_manageLogs = $this->_manageExtensions->getExtension('OWeb\log','Log');

        // Settings may override the extension to be loaded, therfore needs to be loaded last.
        if (!empty($extensionToLoad)) {
            $this->_manageExtensions->getExtension($extensionToLoad);
        }

        // Let the world now of the sucess.
        $this->_manageEvents->dispatchEvent(CoreEvents::name_OWeb_init);
    }

    /**
     * Get the run time of the oweb instance.
     *
     * @return int|mixed
     */
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
     * @return SimpleArray
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * @return SimpleArray
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * @return SimpleArray
     */
    public function getGet()
    {
        return $this->_get;
    }

    /**
     * @return SimpleArray
     */
    public function getPost()
    {
        return $this->_post;
    }

    /**
     * @return SimpleArray
     */
    public function getServer()
    {
        return $this->_server;
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
     * Logs using the active log extension
     *
     * @param mixed  $msg   Message to log
     * @param int    $level Log level
     * @param string $file  File to write logs into
     */
    public function log($msg, $level = Log::LEVEL_INFO, $file = null)
    {
        $this->_manageLogs->log($msg, $level, $file);
    }
}
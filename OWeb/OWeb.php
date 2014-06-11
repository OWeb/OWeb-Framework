<?php
/**
 * @author      Oliver de Cramer (oliverde8 at gmail.com)
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

/**********************************************************************************
* OWeb\OWeb.php
* OWeb main File to get it run
***********************************************************************************
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
***********************************************************************************
* OWeb is a simple Object PHP FrameWork
* ===============================================================================
* Version:		              0.3.2
* Author	                  Oliver de Cramer (oliverde8 at gmail.com)
**********************************************************************************/

namespace OWeb;

if(!defined('OWEB_DEBUG'))define('OWEB_DEBUG', "1");

if(!defined('OWEB_DIR_MAIN'))define('OWEB_DIR_MAIN', "../OWeb_src/OWeb");
if(!defined('OWEB_CONFIG'))define('OWEB_CONFIG_DIR', "../OWeb_src/confing");
if(!defined('OWEB_CONFIG'))define('OWEB_CONFIG', "config.ini");
if(!defined('OWEB_DIR_LANG'))define('OWEB_DIR_LANG', "lang");
if(!defined('OWEB_DEFAULT_LANG_DIR'))define('OWEB_DEFAULT_LANG_DIR', "../OWeb_src/lang");

if(!defined('OWEB_DIR_VIEWS'))define('OWEB_DIR_VIEWS', "../OWeb_src/views");
if(!defined('OWEB_DIR_SVUES'))define('OWEB_DIR_SVUES', "../OWeb_src/sVue");
if(!defined('OWEB_DIR_MODELS'))define('OWEB_DIR_MODELS', "../OWeb_src/models");
if(!defined('OWEB_DIR_CONTROLLERS'))define('OWEB_DIR_CONTROLLERS', "../OWeb_src/controllers");
if(!defined('OWEB_DIR_PAGES'))define('OWEB_DIR_PAGES', "pages");
if(!defined('OWEB_DIR_EXTENSIONS')) define('OWEB_DIR_EXTENSIONS','../OWeb_src/extensions');
if(!defined('OWEB_DIR_TEMPLATES')) define('OWEB_DIR_TEMPLATES','templates');

// Les controleurs par default
if(!defined('OWEB_DEFAULT_PAGE')) define('OWEB_DEFAULT_PAGE','home');
if(!defined('OWEB_DIR_DATA')) define('OWEB_DIR_DATA','sources/data');

// Les Fichier pour le HTML par Default
if(!defined('OWEB_HTML_DIR_CSS')) define('OWEB_HTML_DIR_CSS','sources/css');
if(!defined('OWEB_HTML_DIR_JS')) define('OWEB_HTML_DIR_JS','sources/js');

if(!defined('OWEB_HTML_URL_IMG')) define('OWEB_HTML_URL_IMG','sources/files');

define('OWEB_VERSION','0.3.2');

require_once OWEB_DIR_MAIN.'/autoLoader.php';

/**
 * Description of OWeb
 *
 * @author De Cramer Oliver
 */
class OWeb {
	
	//OWeb instance
	private static $instance = null;
	
	//Les variables ....
	private $_get;
    private $_post;
    private $_files;
	private $_cookies;
    private $_server;
	private $_adresse;
	
	//Managers
	private $manage_events;
	private $manage_headers;
	private $manage_controllers;
	private $manage_subViews;
	private $manage_extensions;
	private $manage_settings;
	
	private $mode;
	
	private $startTime;
	
	public function __construct(&$get, &$post, &$files, &$cookies, &$server, $adr) {
		
		$this->get_runTime();
		
		//Checking if There is an older instance. If yes Exception.
		if(self::$instance != null){
			throw new Exception("Only 1 instance of OWeb can run.");
		}else{
			self::$instance = $this;
		}

        error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT);
		
		//Variables d'environement
        $this->_get= $get;
        $this->_post= $post;
        $this->_files= $files;
		$this->_cookies = $cookies;
        $this->_server= $server;
		$this->_adresse = $adr;
		
		//Initializing AutoLoader. 
		new autoLoader();
		
		//Initialize Events manager
		$this->manage_events = \OWeb\manage\Events::getInstance();
		
		//Initialize headers manager
		$this->manage_headers = \OWeb\manage\Headers::getInstance();
		
				//Initialize Extension manager
		$this->manage_extensions = \OWeb\manage\Extensions::getInstance();
		
		//Initialize Controller manager
		$this->manage_controllers = \OWeb\manage\Controller::getInstance();
		
		//Initialize SubView manager
		$this->manage_subViews = \OWeb\manage\SubViews::getInstance();
		
		//Initialize Setting Manager
		$this->manage_settings = \OWeb\manage\Settings::getInstance();
		
		//Initializing Managers DONE. Starting to recover OWeb General settings
		$this->loadSettings();
		
		//Settings loaded, starting the mode ...
		if(isset($this->_get['mode'])){
			switch (strtoupper($this->_get['mode'])) {
				case 'API':
					$this->mode = $this->manage_extensions->getExtension("core\modes\Api");
					break;
				case 'CTRACTION':
					$this->mode = $this->manage_extensions->getExtension("core\modes\CtrAction");
					break;
				case 'PAGE':
				default:
					$this->mode = $this->manage_extensions->getExtension("core\modes\Page");
			}
		}else{
			$this->mode = $this->manage_extensions->getExtension("core\modes\Page");
		}
		$this->mode->initMode();
		
		//Initialasation of OWeb DONE. 
		$this->manage_events->sendEvent('Init@OWeb');
		
	}
	
	/**
	 * Will load the settings from the Default config file. And will after loads the extensions
	 * 
	 * @throws \OWeb\Exception If there is any error while loading the Settings
	 */
	private function loadSettings(){
		
		$settings = $this->manage_settings->getSetting($this);
	
		if(isset($settings['extensions'])){
			//loading all extensions
			try{
				$this->loadExtensions($settings['extensions']);
			}catch(\Exception $ex){
				throw new \OWeb\Exception('Failed to load all Extensions in'.OWEB_CONFIG, 0, $ex);
		}
		}
	}
	
	/**
	 * Will load the extensions passed in Paramater.
	 *
	 * @param array $extensions The list of expensions as recoverd by the setting file
	 */
    private function loadExtensions($extensions=array()){
		
		foreach($extensions as $ex){
			$this->manage_extensions->getExtension($ex);
		}
	}
	
	/**
	 * Will start the display. The display will depend on the current mode.
	 */
	public function display(){
		$this->mode->display();
	}
	
	/*
	 * Will return the current mode. 
	 */
	public function get_mode(){
		return $this->mode;
	}
	
	public function get_get() {
		return $this->_get;
	}

	public function get_post() {
		return $this->_post;
	}

	public function get_files() {
		return $this->_files;
	}

	public function get_cookies() {
		return $this->_cookies;
	}

	public function get_server() {
		return $this->_server;
	}

	public function get_adresse() {
		return $this->_adresse;
	}
	
	public function get_startTime(){
		return $this->startTime;
	}
	
	public function get_runTime(){
		static $a;
		if($a == 0){
			$a = microtime(true);
			return 0;
		}
		else return microtime(true)-$a;
	}
	
	/**
	 * Returns the runtime in seconds.
	 * 
	 * @param int [=3] The require precision.
	 * @return String The run time with te demanded precision
	 */
	public function get_stringRuntTime($precision = 3){
		return number_format($this->get_runTime(),$precision);
	}
	
	
	/**
	 * 
	 * @return OWeb
	 */
	public static function getInstance(){
		return self::$instance;
	}

	
}

?>

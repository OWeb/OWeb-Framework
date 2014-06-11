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

namespace OWeb\utils\js\jquery;


use \OWeb\manage\Events;
use OWeb\manage\Headers;

/**
 * Description of HeaderOnReady
 *
 * @author De Cramer Oliver
 */
class HeaderOnReadyManager{
	
	private static $_instance = null;
	
	private $headers = array();
	
	private $event;
	
	/**
	 * 
	 * @return HeaderOnReadyManager
	 */
	public static function getInstance(){
		if(self::$_instance == null)
			self::$_instance = new HeaderOnReadyManager();
		
		return self::$_instance;
	}
	
	function __construct() {
		$event = Events::getInstance();
		$event->registerEvent('Didplay_Prepare@OWeb\manage\Headers', $this, 'makeAdd');
	}
	
	public function add($code){
		$this->headers[md5($code)] = $code;
	}

	/**
	 * Will add the headers registered to the main Headers Manager
	 */
	public function makeAdd(){		
		if(OWEB_DEBUG > 0)
			$code .= $this->makeAddNormal ();
		else
			$code .= $this->makeAddNormal ();
		
		Headers::getInstance()->addHeader($code, Headers::jsCode);
	}
	
	/**
	 * If debug is active we should separate each JS function to prevent one
	 * wrong code to brake all others
	 * 
	 * @return string
	 */
	public function makeAddDebug(){
		$s = "";
		foreach ($this->headers as $code){
			$s .= '$( document ).ready(function() {'."\n";
			$s .= $code;
			$s .= "\n});\n\n";
		}
		return $s;
	}
	
	/**
	 * If Debug isn't active we will put them all in 1 ready function to increase
	 * performances and hope that all JS codes are okay.
	 * 
	 * @return string
	 */
	public function makeAddNormal(){
		$s = "var oweb_ready = function (){";
		foreach ($this->headers as $code)
			$s .= $code."\n\n";
		$s .= "}\n\n";
		
		$s .= '$( document ).ready(function() {'."\n";
		$s .= "oweb_ready(); \n";
		$s .= "\n});\n\n";
		
		return $s;
	}
	
}

?>

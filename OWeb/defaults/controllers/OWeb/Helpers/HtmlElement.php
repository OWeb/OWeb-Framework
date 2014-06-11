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

namespace Controller\OWeb\Helpers;

/**
 * Description of HtmlElement
 *
 * @author De Cramer Oliver
 */
abstract class HtmlElement extends \OWeb\types\Controller
								implements \OWeb\utils\html\HtmlIdentified{
	
	private $identifier;
	
	public function init() {
		$this->identifier = new \OWeb\utils\HtmlElementIdentifier();
	}
	
	/**
	 * Setting the id of this html tag
	 * 
	 * @param String the id
	 */
	public function setHtmlId($id){
		$this->identifier->setHtmlId($id);
	}
	
	/**
	 * @return String the id of this Html tag
	 */
	public function getHtmlId(){
		return $this->identifier->getHtmlId();
	}
	
	/**
	 * Add's a class to this Html Tag. 
	 * @param String the class name to add to this Html tag
	 */
	public function addHtmlClass($class){
		$this->identifier->addHtmlClass($class);
	}
	
	/**
	 * @return String The identifier is generated into a string for html use
	 */
	public function getIdentifier(){
		return $this->identifier;
	}
	
}

?>

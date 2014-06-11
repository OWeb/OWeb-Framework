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
namespace Extension\core\url;

/**
 * Description of Generator
 *
 * @author De Cramer Oliver
 */
class Generator extends \OWeb\types\extension{

	public function init(){
		$this->addAlias("url", "getLink");
		$this->addAlias("CurrentUrl", "getCurrentUrl");
		$this->addAlias("generateUrl", "generate_LinkString");
	}
	
	public function generate_LinkString($params) {
		
		$link = "";
		if(isset($params['page'])){
			$link = str_replace("\\", '.', $params['page']).'.html';
			unset($params['page']);
		}
		
		$link .= "?"; 
		foreach($params as $name => $value){
			$link .="$name=$value&";
		}
		return substr($link, 0, strlen($link)-1);
	}
	
	public function getLink($params=array()){
		return new Link($params);
	}
	
	public function getCurrentUrl(){
		return clone Link::getCurrentLink();
	}
}

?>

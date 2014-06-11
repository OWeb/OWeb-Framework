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

namespace OWeb\utils\inputManagement;

/**
 * Description of Validators
 *
 * @author De Cramer Oliver
 */
abstract class Validators extends \OWeb\types\NamedClass{
	
	private $_name;
	
	private $_exception;
	
	private $_l;
	
	/**
	 * 
	 * @param type $value The value to check
	 */
	abstract public function valideteValue($value);
	
	/**
	 * 
	 * @param type $title The title to give to the error
	 * @param type $description The description of the error
	 */
	public function setErrorMesssage($title, $description){
		$this->_exception = new \OWeb\types\UserException($title);
		$this->_exception->setUserDescription($description);
	}
	
	/**
	 * 
	 * @param type $name
	 * @throws type
	 */
	protected function throwErrorMessage($name = null, $param = ''){	
		if($name == null)
			$name = $this->_name;
		else
			$name = $this->_name.'_'.$name;
		
		if($this->_exception != null)
			throw $this->_exception;
		else{
			if($this->_l == null){
				$this->InitLanguageFile();
				$this->_exception = new \OWeb\types\UserException($this->_l->get('Title_'.$name).$param);
				$this->_exception->setUserDescription($this->_l->get('Desc_'.$name));
				throw $this->_exception;
			}
		}
	}
	
	protected function setName($name){
		$this->_name = $name;
	}
	
	protected function InitLanguageFile(){
		if($this->_l == null)
			$this->_l = new \OWeb\types\Language ();

		$this->InitRecLanguageFile(get_class($this));
	}
	
	
	private function InitRecLanguageFile($name, \OWeb\types\Language $lang = null){
		$lManager = \OWeb\manage\Languages::getInstance();

		$l = $lManager->getLanguage($name, $this->get_exploded_nameOf($name));
		
		if($lang == null){
			$this->_l = clone $l;
			$lang = $this->_l;
		}else{
			$lang->merge($l);
		}
		
		$parent = get_parent_class($name);
		
		if ($parent != '\OWeb\types\NamedClass' && $parent != 'OWeb\types\NamedClass')
			$this->InitRecLanguageFile($parent, $lang);
	}
}

?>

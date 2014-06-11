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
namespace OWeb\types;

use OWeb\types\Controller;

/**
 * Description of Language
 *
 * @author De Cramer Oliver
 */
class Language {

	public static $default_language;
	public static $user_language;
	
	private $def_languageTexts;
	private $user_languageTexts;
	
	private $otherLanguages;
	
	function __construct() {
		$this->def_languageTexts = new \SplDoublyLinkedList();
		$this->user_languageTexts = new \SplDoublyLinkedList();
		$this->otherLanguages = new \SplDoublyLinkedList();
	}
	
	public function addDefLanguageText(array $text){
		$this->def_languageTexts->push($text);
	}
	
	public function addUserLanguageText(array $text){
		$this->user_languageTexts->push($text);
	}
	
	public function merge(Language $otherLang){
		$this->otherLanguages->push($otherLang);
	}
	
	public function get($name, $sendNull = false) {
		for ($this->user_languageTexts->rewind(); $this->user_languageTexts->valid(); $this->user_languageTexts->next()) {
			$current = $this->user_languageTexts->current();
			if(isset($current[$name]))
				return $current[$name];
		}
		for ($this->def_languageTexts->rewind(); $this->def_languageTexts->valid(); $this->def_languageTexts->next()) {
			$current = $this->def_languageTexts->current();
			if(isset($current[$name]))
				return $current[$name];
		}
		for ($this->otherLanguages->rewind(); $this->otherLanguages->valid(); $this->otherLanguages->next()) {
			$val = $this->otherLanguages->current()->get($name, true);
			if($val != null)
				return $val;
		}
		return $sendNull ? null : $name;
	}

	public function getLang() {
		return self::$user_language;
	}

}

?>

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
namespace Model\articles;

/**
 * Description of Article
 *
 * @author De Cramer Oliver
 */
class Article {
	
	private $id;
	private $type;
	private $date;
	private $img;
	
	private $langs = array();
	private $titles = array();
	private $contents = array();
	
	private $isPublished;
	
	private $categorie;
	private $categories = array();
	
	private $attributes = array();
	
	private $done = false;
	
	function __construct($id, $type, $img, $isPublished, $date, $categorie) {
		$this->id = $id;
		$this->type = $type;
		$this->img = $img;
		$this->date = $date;
		$this->isPublished = $isPublished;
		$this->categorie = $categorie;
	}

	function addLanguage($lang, $title, $content){
		$this->langs[] = $lang;
		$this->titles[$lang] = $title;
		$this->contents[$lang] = $content;
	}
	
	function addCategorie(\Model\articles\CategorieElement $categorie){
		$this->categories[] = $categorie;
	}
	
	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function getType() {
		return $this->type;
	}

	public function setType($type) {
		$this->type = $type;
	}
	
	public function getImg() {
		return $this->img;
	}

	public function setImg($img) {
		$this->img = $img;
	}

		
	public function getLangs() {
		return $this->langs;
	}
	
	public function checkLang($lang){
		return (isset($this->titles[$lang]));
	}
		
	public function getDate() {
		return $this->date;
	}

	public function setDate($date) {
		$this->date = $date;
	}

		
	public function getTitle($lang) {
		return $this->titles[$lang];
	}

	public function setTitle($lang, $titles) {
		$this->titles[$lang] = $titles;
	}

	public function getContent($lang) {
		return $this->contents[$lang];
	}

	public function setContents($lang,$contents) {
		$this->contents[$lang] = $contents;
	}

	public function getIsPublished() {
		return $this->isPublished;
	}

	public function setIsPublished($lang,$isPublished) {
		$this->isPublished[$lang] = $isPublished;
	}

	public function getCategorie() {
		return $this->categorie;
	}

	public function setCategorie($categorie) {
		$this->categorie = $categorie;
	}

	public function getCategories() {
		return $this->categories;
	}
	
	public function addAttribute($name, $value){
		$this->attributes[$name] = $value;
	}
	
	public function getAttribute($name){
		if(isset($this->attributes[$name]))
			return $this->attributes[$name];
		else
			return "";
	}
	
	public function getAllAtributes(){
		return $this->attributes;
	}
	
	public function isDone(){
		return $this->done;
	}
	
	public function setisDOne($done){
		$this->done = $done;
	}
}

?>

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
namespace Model\programs;

/**
 * Description of Categorie
 *
 * @author De Cramer Oliver
 */
class CategorieElement extends \Model\OWeb\treeElement\TreeElement{
	
	private $categories;
	
	private $description;
	
	private $parent_id;
	
	private $parent = null;
	
	private $img;
	
	private $child_done = false;
	
	function __construct($categories, $id, $title, $description, $parent_id, $img) {
		parent::__construct($id, $title, true);
		$this->categories = $categories;
		$this->description = $description;
		$this->parent_id = $parent_id;
		$this->img = $img;
	}


	public function getParent() {
		if($this->parent_id == -1)
			return null;
		else if( $this-> parent != null)
			return $this->parent;
		else{
			$this->parent = $this->categories->getElement($this->parent_id);
			return $this->parent;
		}
	}
	
	public function getChildrens(){
		if(!$this->child_done){
			$this->childrens = $this->categories->getChildrenOf($this->id);
			$this->child_done = true;
		}
		return $this->childrens;
	}
	
	public function setChildrends($childs){
		$this->childrens = $childs;
	}
	
	public function isChildrenDone(){
		return $this->child_done;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}
	
	public function getImg() {
		return $this->img;
	}

	public function setImg($img) {
		$this->img = $img;
	}
	
}

?>

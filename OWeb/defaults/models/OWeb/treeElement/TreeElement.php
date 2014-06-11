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
namespace Model\OWeb\treeElement;

/**
 * Description of TreeElement
 *
 * @author De Cramer Oliver
 */
abstract class TreeElement {
	
	protected $id;
	protected $name;
	protected $visible;
	
	protected $childrens = array();
	
	function __construct($id, $name, $visible) {
		$this->id = $id;
		$this->name = $name;
		$this->visible = $visible;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function isVisible() {
		return $this->visible;
	}

	public function setVisible($visible) {
		$this->visible = $visible;
	}

	abstract public function getParent();
	
	abstract public function getChildrens();
	
	public function getRecursiveParentsIds($ids = ""){
		if($this->getParent() != null){
			if($ids == "")
				$ids .= " ".$this->id;
			else 
				$ids .= ", ".$this->id;
			return $this->parent->getRecursiveParentsIds($ids);
		}else
			return $ids;
	}
	
	public function getRecuriveChildrensIds($ids = ""){
		
		$this->getChildrens();
			
		foreach($this->childrens as $child){
			if($ids != "")
				$ids .= ", ";
			$ids .= $child->getId();
			$ids = $child->getRecuriveChildrensIds($ids);
		}

		return $ids;
	}

}

?>

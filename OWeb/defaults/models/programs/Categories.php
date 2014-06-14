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
use OWeb\utils\Singleton;

/**
 * Description of Categories
 *
 * @author De Cramer Oliver
 */
class Categories extends Singleton implements \Model\OWeb\treeElement\TreeManager{

	private $ext_connection;
	
	private $elements = array();
	private $childrends = array();
	private $done = false;

	function __construct() {

		$this->ext_connection = \OWeb\manage\Extensions::getInstance()->getExtension('db\Connection');
	}

	public function getElement($id) {
		if (isset($this->elements[$id]))
			return $this->elements[$id];
		else{
			try {
				if(!$this->done){
					$this->getAll();
					return $this->getElement($id);
				}else
					throw new \Model\programs\exception\CategoryNotFound("The category with the id $id coulsn't be found : Category with this id don't exist.");
			} catch (\Exception $ex) {
				throw new \Model\programs\exception\CategoryNotFound("The category with the id $id couldn't be found : Unknown Error.", 0, $ex);
			}
		}
	}

	public function getAllRoot() {
		if(!$this->done)
			$this->getAll ();
		return($this->getChildrenOf(-1));
	}

	public function getChildrenOf($id){
		if(isset($this->childrends[$id]))
			return $this->childrends[$id];
		else
			return array();
	}
	
	public function getAll(){
		
		$connection = $this->ext_connection->get_Connection();
		$prefix = $this->ext_connection->get_prefix();
		$this->done = true;
		try{
			$sql = "SELECT * FROM " . $prefix . "program_category ORDER BY title ASC";
			if($sql = $connection->query($sql)){
				$sql_res = $sql->fetchAll();
				
				if(is_array($sql_res)){
					foreach($sql_res as $res_el){
						$element = new CategorieElement($this, $res_el['id_category'], $res_el['title'], $res_el['description'], $res_el['parent_id'],  $res_el['img']);

						$this->elements[$res_el['id_category']]=$element;
						$this->childrends[$res_el['parent_id']][] = $element;
					}
				}
			}else
				throw new \Model\programs\exception\CategoryNotFound("Couldn't load All categories. SQL ERROR");
		}catch(\Exception $ex){
			throw new \Model\programs\exception\CategoryNotFound("Couldn't load All categories. SQL ERROR", 0, $ex);
		}
	}

}

?>

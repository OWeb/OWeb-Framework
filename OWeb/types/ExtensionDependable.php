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

use \OWeb\manage\Extensions;

/**
 * Description of ExtensionDependable
 *
 * @author De Cramer Oliver
 */
class ExtensionDependable implements InterfaceExtensionDependable{
	
	protected $dependence;
	
	function __construct() {
		$this->dependence = new \SplDoublyLinkedList();
	}

	
	protected function addDependance($extension_name) {
		try{
			if(is_object($extension_name)){
				$ext= $extension_name;
				$extension_name = get_class($extension_name);
			}else
				$ext = Extensions::getInstance()->getExtension($extension_name);
			
			if (!$ext) {
				throw new \OWeb\Exception("");
			}else {				
				$this->dependence->push($ext);
			}
		}catch (\OWeb\Exception $exception){
			throw new \OWeb\Exception("The extension: " . $extension_name." Couldn't be loaded. L'The controller " . get_class($this) . " needs it to work",0, $exception);
		}
	}
	
	public function __call($name, $arguments){		
        for($this->dependence->rewind(); $this->dependence->valid(); $this->dependence->next()){
			$current = $this->dependence->current();
			$alias = $current->getAlias($name);
			if($alias != null){
				return call_user_func_array(array($current, $alias), $arguments);
			}
		}
		throw new \OWeb\Exception("The function: " . $name." doesen't exist and couldn't be find in any extension to whom the plugin depends",0);
    }

	public function getDependences() {
		return $this->dependence;
	}

}

?>

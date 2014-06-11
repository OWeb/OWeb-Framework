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

namespace OWeb\utils\inputManagement\validators;

/**
 * Description of Integer
 *
 * @author De Cramer Oliver
 */
class Integer extends \OWeb\utils\inputManagement\Validators{
	
	public $max = null; 
	
	public $min = null;
	
	function __construct() {
		$this->setName('Integer');
	}

	
	public function valideteValue($value) {
		
		if($value != null && preg_match('/^-?\d+$/', $value)){
			$val = (int) $value;
			
			if($this->max != null && $val > $this->max)
				$this->throwErrorMessage ('Max', $this->max);
			
			if($this->min != null && $val < $this->min)
				$this->throwErrorMessage ('Min', $this->min);
			
			return $val;
		}else
			$this->throwErrorMessage ();
		
	}
}

?>

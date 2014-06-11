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

namespace OWeb\utils;

use \OWeb\types\NamedClass;

abstract class Singleton extends NamedClass{
	protected static $instances = array();

	static public function getInstance()
	{
		$class = get_called_class();
		if(!isset(self::$instances[$class]))
		{
			self::$instances[$class] = new $class();
		}
		return self::$instances[$class];
	}

    static protected function getInstanceNull(){
        $class = get_called_class();
        return isset(self::$instances[$class]) ? self::$instances[$class] : null;
    }

    static protected function setInstance(Singleton $object){
        $class = get_called_class();
        self::$instances[$class] = $object;
    }

	/**
	 * Force a singleton object to be instanciated with the given instance
	 * Use with care!
	 */
	static function forceInstance(Singleton $object, $class = null)
	{
		if($class == null)
			$class = get_class($object);
		
		if(!isset(self::$instances[$class]))
		{
			self::$instances[$class] = $object;
		}
		else
		{
			throw new \Exception(sprintf('Object of class %s was previously instanciated', $class));
		}
	}

	final protected function __clone() {}
}


?>
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


/**
 * Description of NamedClass
 *
 * @author De Cramer Oliver
 */
abstract class NamedClass{
	
	private static $_exploded_name;
	private static $_full_path;
	private static $_relative_path;
	private static $_start_path;
	
	public function get_exploded_name() {
		return self::$_exploded_name[get_class($this)];
	}
	
	public function get_exploded_num($i) {
		return self::$_exploded_name[get_class($this)][$i];
	}

	public function get_full_path() {
		return self::$_full_path[get_class($this)];
	}

	public function get_relative_path() {
		return self::$_relative_path[get_class($this)];
	}

	public function get_start_path() {
		return self::$_start_path[get_class($this)];
	}
	
	public static function get_exploded_nameOf($class) {
		return self::$_exploded_name[$class];
	}
	
	public static function get_exploded_numOf($class, $i) {
		return self::$_exploded_name[$class][$i];
	}

	public static function get_full_pathOf($class) {
		return self::$_full_path[$class];
	}

	public static function get_relative_pathOf($class) {
		return self::$_relative_path[$class];
	}

	public static function get_start_pathOf($class) {
		return self::$_start_path[$class];
	}

	public static function newNamedClass($name, $ename, $fpath, $rpath, $path){
		self::$_exploded_name[$name]=$ename;
		self::$_full_path[$name]=$fpath;
		self::$_relative_path[$name]=$rpath;
		self::$_start_path[$name]=$path;				
	}
}

?>

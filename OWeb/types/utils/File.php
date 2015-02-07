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

namespace OWeb\types\utils;


use OWeb\Exception;

class File {

    public static function mkDir($dir, $mode = 0777, $recursive = false){
        if (!is_dir($dir)) {
            mkdir($dir, $mode, $recursive);
        } else if(!is_writable($dir)) {
            chmod($dir, $mode);
        }

        if (!is_dir($dir) || !is_writable($dir)){
            throw new Exception('Couldn\'t create or change permissions for directory : '.$dir);
        }
    }

    public static function cleanFileName($file) {
        $file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $file);
        return preg_replace("([\.]{2,})", '', $file);
    }

    public static function isDirEmpty($dir) {
        if (!is_readable($dir)) {
            throw new Exception('Couldn\'t read directory : '.$dir);
        }
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                return false;
            }
        }
        return true;
    }
} 
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

namespace Extension\user\permission\SimplePermission;


class PermissionList {

    const noPermission = 0;
    const hasPermission = 1;
    const canGivePermission = 2;


    private $permission_list;


    public function registerPermission($name, $elements){
        if(strtolower($elements) == 'all' || empty($elements)){
            $this->permission_list[$name] = true;
        }else{
            $list = explode(";", $elements);
            foreach($list as $element){
                $p = explode(":",$element);
                $this->permission_list[$name][$p[0]] = $p[1];
            }
        }
    }

    public function getPermission($name, $element){
        if( isset($this->permission_list[$name])){

            if($this->permission_list[$name]){
                return self::canGivePermission;
            }else if(isset($this->permission_list[$name][$element]))
                return $this->permission_list[$name][$element];
        }
        else 0;
    }

    public function getHasPermission($name, $element){
        return $this->getPermission($name, $element) >= self::hasPermission;
    }

    public function getCanGivePermission($name, $element){
        return $this->getPermission($name, $element) > self::hasPermission;
    }

}
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

namespace Extension\user\permission;


use Extension\user\permission\SimplePermission\PermissionList;

class SimplePermission extends TypePermission{

    protected $permissions = array();

    private $handler_list = array();

    public function getUserPermission($login, $permissionName, $forElement){

        if(!isset($this->permissions[$login]))
            $this->loadUserPermission($login);

        if(isset($this->permissions[$login]) && $this->permissions[$login] != null)
            return $this->permissions[$login]->getPermission($permissionName, $forElement);
        else
            return PermissionList::noPermission;
    }

    public function loadUserPermission($login){
        $connection = $this->ext_db_absConnect->get_Connection();
        $prefix = $this->ext_db_absConnect->get_Prefix();

        $sql = $connection->prepare("SELECT *
                                        FROM ".$prefix."users_permission p, ".$prefix."users_permission_list l
                                        WHERE login = :login
                                            AND p.permission_id = l.id_permission");
        if($sql->execute(array(':login'=>$login))){
            if ($sql->rowCount()>0){
                $permission = new PermissionList();

                while($obj = $sql->fetchObject()){
                    $permission->registerPermission($obj->permission_id, $obj->name, $obj->hanlder, $obj->permission_adv);
                    $this->handler_list[$obj->name] = $obj->handler;
                }

                $this->permissions[$login] = $permission;
            }else{
                $this->permissions[$login] = null;
            }
        }
    }

    public function getHavePermission($login, $name, $element){
        return $this->getUserPermission($login, $name, $element) >= PermissionList::hasPermission;
    }

    public function getCanGivePermission($login, $name, $element){
        return $this->getUserPermission($login, $name, $element) == PermissionList::canGivePermission;
    }

    public function getHandlerName($name){
        return isset($this->handler_list[$name]) ? $this->handler_list[$name] : null;
    }
}
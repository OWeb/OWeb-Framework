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

namespace OWeb\db\module\Model\Settings;

use OWeb\OWeb;
use OWeb\settings\module\Extension\Settings;
use OWeb\settings\module\Model\Setting;
use OWeb\settings\module\Model\SimpleXMLElement;

class PDO extends Setting {
    function __construct()
    {
        parent::__construct();
        foreach($this as $key => $value){
            if (!is_string($value))
                $this->$key = ((string)$value);
        }
    }


    public $connection_type = 'mysql';
    public $connection_host = 'localhost';
    public $connection_dbName = '';

    public $auth_name = 'root';
    public $auth_pwd = '';
    public $prefix = '';

} 
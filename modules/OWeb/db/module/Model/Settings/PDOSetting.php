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

use OWeb\settings\module\Model\Setting;
use OWeb\settings\module\Model\SimpleXMLElement;

class PDOSetting extends Setting {

    protected $name = null;

    protected $default = FALSE;

    protected $type = 'mysql';
    protected $host = 'localhost';
    protected $dbName = '';

    protected $auth_name = 'root';
    protected $auth_pwd = '';
    protected $prefix = '';

    /**
     * PDO constructor.
     */
    public function __construct($name, $settings = null)
    {
        if (is_null($settings)) {
            parent::__construct();
        } else {
            $this->applyRawSettings($settings);

            if (!empty($settings['default'])) {
                $this->default = TRUE;
            }
        }

        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @return string
     */
    public function getAuthName()
    {
        return $this->auth_name;
    }

    /**
     * @return string
     */
    public function getAuthPwd()
    {
        return $this->auth_pwd;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


} 
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

namespace OWeb\db\module\Extension;


use OWeb\db\module\Model\PDOConnection as Connection;
use OWeb\db\module\Model\Settings\PDO as PDOSettings;
use OWeb\db\module\Model\Settings\PDO;
use OWeb\db\module\Model\Settings\PDOSetting;
use OWeb\utils\SimpleArray;

class PDOConnection extends AbstractConnection{

    /** @var PDO */
    protected $settings = null;

    /*
     * Creates a connection
     */
    protected function startConnection($name = 'main')
    {

        if (is_null($this->settings)) {
            $this->settings = PDO::getInstance();
        }

        if (!empty($this->settings->getSetting($name))) {

            $connection = Connection::initFromSetting($this->settings->getSetting($name));
            $this->connections->set($name, $connection);

        } else if (!empty($this->settings->getUsage($name))) {

            $connection = $this->getConnection($this->settings->getUsage($name));
            $this->connections->set($name, $connection);

        } else {
            $connection = $this->getConnection($this->settings->getDefault()->getName());
            $this->connections->set($name, $connection);
        }
    }

    public function getConnection($name = 'main') {

        if (is_null($this->connections)) {
            $this->connections = new SimpleArray();
        }

        if (is_null($this->connections->get($name))) {
            $this->startConnection($name);
        }

        return $this->connections->get($name);
    }
}
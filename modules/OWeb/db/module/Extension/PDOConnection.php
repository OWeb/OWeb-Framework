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

class PDOConnection extends AbstractConnection{

    /*
     * Creates a connection
     */
    protected function startConnection() {

        $settings = new PDOSettings();

        $this->prefix = $settings->prefix;

        $con = ($settings->connection_type) . ':host=' . ($settings->connection_host) . ';dbname=' . ($settings->connection_dbName)."";
        try{
            $this->connection = new Connection(
                $con,
                ($settings->auth_name),
                ($settings->auth_pwd)
                , array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        }catch(\Exception $ex){
            throw new \OWeb\Exception("Couldn't connect to DB : ".$con, 0, $ex);
        }
        $this->done = true;
    }

    public function getConnection() {
        //Si la connection n'a pas encore ete etablis faut le faire.
        if (!$this->done)
            $this->startConnection();
        return $this->connection;
    }
}
<?php
/**
 * @author       Oliver de Cramer (oliverde8 at gmail.com)
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
namespace Extension\db;

/**
 * Description of Connection
 *
 * @author De Cramer Oliver
 */
class Connection extends absConnect
{


    protected function init()
    {
        parent::init();
    }

    /*
     * Creates a connection
     */
    protected function startConnection()
    {

        $this->initSettings();

        $this->prefix = $this->settings['prefix'];

        $con = $this->settings['connection.type'] . ':host=' . $this->settings['connection.host'] . ';dbname=' . $this->settings['connection.dbname'] . "";
        try {
            $this->connection = new \PDO($con, $this->settings['authontification.user'], $this->settings['authontification.password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        } catch (\Exception $ex) {
            throw new \OWeb\Exception("Couldn't connect to DB : " . $con, 0, $ex);
        }
        $this->done = true;
    }

    /**
     * @return \PDO
     */
    public function get_Connection()
    {
        //Si la connection n'a pas encore ete etablis faut le faire.
        if (!$this->done)
            $this->startConnection();

        return $this->connection;
    }

}

?>

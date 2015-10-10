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

namespace OWeb\db\module\Model;


use OWeb\db\module\Extension\AbstractConnection;
use OWeb\OWeb;

trait DBExtension
{
    /** @var  AbstractConnection */
    private $__dbExtension;

    /**
     * Add dependency
     */
    protected function initDbExtension()
    {
        $this->__dbExtension = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\db\module\Extension\AbstractConnection');
    }

    /**
     * Get a particular connection.
     *
     * @param $name
     *  The name of the connection to get.
     *
     * @return mixed
     */
    protected function getDbConnection($name)
    {
        return $this->__dbExtension->getConnection($name);
    }

    /**
     * Get the read connection.
     *
     * @return mixed
     */
    protected function getReadConnection()
    {
        return $this->__dbExtension->getConnection('read');
    }

    /**
     * Get the write connection.
     *
     * @return mixed
     */
    protected function getWriteConnection()
    {
        return $this->__dbExtension->getConnection('write');
    }
}
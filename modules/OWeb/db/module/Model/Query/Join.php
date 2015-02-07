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

namespace OWeb\db\module\Model\Query;


class Join {

    private $joinType;

    private $joinTable;

    private $alias;

    private $where;

    /**
     * @param string $alias     Alias of the table to join with
     * @param string $joinTable Table name
     * @param string $joinType  Type of join
     * @param Where  $where     Condition to join on
     */
    function __construct($alias, $joinTable, $joinType, Where $where)
    {
        $this->alias = $alias;
        $this->joinTable = $joinTable;
        $this->joinType = $joinType;
        $this->where = $where;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return mixed
     */
    public function getJoinTable()
    {
        return $this->joinTable;
    }

    /**
     * @return mixed
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * @return mixed
     */
    public function getWhere()
    {
        return $this->where;
    }


} 
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

namespace OWeb\db\module\Model\Query;


class Select implements Query
{

    const JOIN_INNER = "0";
    const JOIN_LEFT = "1";
    const JOIN_RIGHT = "2";
    const JOIN_FULL = "3";

    private $select = array();
    private $tableSelect = array();

    private $from = array();

    private $joins = array();

    private $where = null;

    /**
     * @param String $tableName To select from
     * @param string $alias
     */
    function __construct($tableName = null, $alias = 'main')
    {
        if ($tableName != null) {
            $this->from[$alias] = $tableName;
        }
    }

    /**
     * Add table to select from
     *
     * @param string $tableName Name of the table to add
     * @param string $alias     Alias for the table, if none then table name will be alias
     *
     * @return $this
     */
    public function addFrom($tableName, $alias = null)
    {
        $alias = is_null($alias) ? $tableName : $alias;
        $this->from[$alias] = $tableName;

        return $this;
    }

    /**
     * Remove a table
     *
     * @param string $alias Alias of the table to remove
     *
     * @return $this
     */
    public function removeFrom($alias)
    {
        if (isset($this->from[$alias])) {
            unset($this->from[$alias]);
        }

        // Remove dependencies
        if (isset($this->tableSelect[$alias])) {
            foreach ($this->tableSelect[$alias] as $calias => $column) {
                unset($this->select[$calias]);
            }
        }

        return $this;
    }

    /**
     * List of tables
     *
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Add column to select
     *
     * @param string $column Name of the column
     * @param string $alias  Alias of the column
     * @param string $table  Name of the table to get the data from(must be alias)
     *
     * @throws Exception If problem with the query
     *
     * @return $this
     */
    public function addSelect($column, $alias = null, $table = null)
    {
        if (is_null($alias)) {
            $exploded = explode('.', $column);
            $alias = array_pop($exploded);
        }
        $this->select[$alias] = array($column, $table);

        if (!is_null($table)) {
            if (isset($this->from[$table])) {
                $this->tableSelect[$table][$alias] = $column;
            } else if(!isset($this->joins[$table])) {
                throw new Exception('Trying to select column for non existent table alias : '.$table);
            }
        }

        return $this;
    }

    public function removeSelect($alias)
    {
        if (isset($this->select[$alias])) {
            if (!is_null($this->select[$alias][1])) {
                unset($this->tableSelect[$this->select[$alias][1]][$alias]);
            }
            unset($this->select[$alias]);
        }
        return $this;
    }

    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @return Where
     */
    public function where()
    {
        if (is_null($this->where)) {
            $this->where = new Where();
        }
        return $this->where;
    }

    public function getWhere()
    {
        return $this->where();
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     *
     * @return $this
     */
    public function addInnerJoin($tableName, $alias, Where $constraint)
    {
        $this->joins[$alias] = new Join($alias, $tableName, self::JOIN_INNER, $constraint);
        return $this;
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     *
     * @return $this
     */
    public function addLeftJoin($tableName, $alias, Where $constraint)
    {
        $this->joins[$alias] = new Join($alias, $tableName, self::JOIN_LEFT, $constraint);
        return $this;
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     *
     * @return $this
     */
    public function addRightJoin($tableName, $alias, Where $constraint)
    {
        $this->joins[$alias] = new Join($alias, $tableName, self::JOIN_RIGHT, $constraint);
        return $this;
    }

    /**
     * @param Join $join join to use
     *
     * @return $this
     */
    protected function addJoin(Join $join)
    {
        $this->joins[$join->getAlias()] = $join;
        return $this;
    }

    /**
     * @return Join[]
     */
    public function getJoins()
    {
        return $this->joins;
    }
} 
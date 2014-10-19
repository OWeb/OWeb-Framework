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

use OWeb\db\module\Model\DbModel;
use OWeb\OWeb;

class Query {

    const JOIN_INNER = "0";
    const JOIN_LEFT = "1";
    const JOIN_RIGHT = "2";
    const JOIN_FULL = "3";

    private $mainTable = null;

    private $joins = array();

    private $select = array();

    /**
     * @var null | Where
     */
    private $where = null;

    private $orderBy = array();

    private $groupBy = array();

    private $virtualGroupBy = '';

    private $results = array();

    /**
     * @param String $mainTable Name of the main table. (will always have alias m)
     */
    function __construct($mainTable)
    {
        $this->mainTable = $mainTable;
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     */
    public function addInnerJoin($tableName, $alias, Where $constraint){
        $this->joins[$alias] = $this->generateJoinCode(self::JOIN_INNER, $tableName, $alias, $constraint);
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     */
    public function addLeftJoin($tableName, $alias, Where $constraint){
        $this->joins[$alias] = $this->generateJoinCode(self::JOIN_LEFT, $tableName, $alias, $constraint);
    }

    /**
     * @param string $tableName  Name of the table to InnerJoin
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     */
    public function addRightJoin($tableName, $alias, Where $constraint){
        $this->joins[$alias] = $this->generateJoinCode(self::JOIN_RIGHT, $tableName, $alias, $constraint);
    }

    /**
     * @param int    $joinType   Join Type
     * @param string $tableName  Name of the table to join
     * @param string $alias      The alias of the table
     * @param Where  $constraint The constraint(ON) to use with the join
     *
     * @return string
     */
    protected function generateJoinCode($joinType, $tableName, $alias, Where $constraint){
        $join = "JOIN";

        switch($joinType){
            case self::JOIN_INNER :
                $join = "INNER JOIN";
                break;
            case self::JOIN_LEFT :
                $join = "LEFT JOIN";
                break;
            case self::JOIN_RIGHT :
                $join = "RIGHT JOIN";
                break;
            case self::JOIN_FULL :
                $join = "FULL JOIN";
                break;
        }

        $join .= " $tableName AS $alias \n\t\tON $constraint";
        return $join;
    }

    /**
     * @param Where $where
     */
    public function setWhere(Where $where){
        $this->where = $where;
    }

    /**
     * @return null|Where
     */
    public function getWhere(){
        return $this->where;
    }

    public function get(){
        /** @var \OWeb\db\module\Model\PDOConnection $connection */
        $connection = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\db\module\Extension\PDOConnection')->getConnection();

        $this->results = array();
        foreach($connection->query($this->__tostring()) as $result){
            $model = new DbModel();
            $model->setArrayData($result);
            $this->results[] = $model;
        }

        return $this->results;
    }

    public function __tostring(){
        $query = "SELECT * FROM {$this->mainTable} m";

        foreach($this->joins as $joinString){
            $query .= "\n\t".$joinString;
        }

        if ($this->where != null) {
            $query .= "\nWHERE {$this->where}";
        }

        return $query;
    }
} 
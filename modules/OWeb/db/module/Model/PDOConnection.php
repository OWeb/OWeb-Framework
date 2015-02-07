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


use OWeb\db\module\Model\Query\Comparison;
use OWeb\db\module\Model\Query\Query;
use OWeb\db\module\Model\Query\Select;
use OWeb\db\module\Model\Query\Where;

class PDOConnection extends \PDO{

    public function prepare ($statement, $driver_options = array()) {
        return parent::prepare($statement, $driver_options);
    }

    public function exec($statement)
    {
        return parent::exec($statement);
    }

    public function query($statement)
    {
        return parent::exec($statement);
    }

    public function prepareStatement($statement) {
        if ($statement instanceof Query) {
            // Need to make string;
            if ($statement instanceof Select) {
                return $this->buildSelectQuery($statement);
            }
        } else {
            return $statement;
        }
    }

    protected function buildSelectQuery(Select $selectQuery)
    {
        $parts = array();
        foreach ($selectQuery->getSelect() as $alias => $columnInfo) {
            $column = ((!is_null($columnInfo[1])) ? $columnInfo[1].'.' : '').$columnInfo[0];
            $parts[] = "`$column` AS `$alias`";
        }

        $query = "SELECT ".implode(',', $parts)."\n";

        $parts = array();
        foreach ($selectQuery->getFrom() as $alias => $tableName) {
            $parts[] = "`$tableName` `$alias`";
        }
        $query .= implode(',', $parts)."\n";

        foreach ($selectQuery->getJoins() as $alias => $join) {
            $query .= "LEFT JOIN {$join->getJoinTable()} {$join->getAlias()}\n";
            $query .= "\t ON {$this->buildWhere($join->getWhere())}\n";
        }

        $query .= 'WHERE '.$this->buildWhere($selectQuery->getWhere());

        return $query;
    }

    protected function buildWhere(Where $where) {
        $expressions = $where->getExpressions();

        if (empty($expressions)) {
            return "";
        }

        $sql = "(".$this->buildExpression($expressions[0][1]);

        for($i = 1; $i < count($expressions); $i++){
            $sql .= " ".$expressions[$i][0]." ".$this->buildExpression($expressions[$i][1]);
        }
        return $sql.")";
    }

    protected function buildExpression($expression) {
        if ($expression instanceof Where) {
            return $this->buildWhere($expression);
        } else if ($expression instanceof Comparison) {
            if ($expression->getOperator() == Comparison::OPERATOR_IN) {
                if (is_array($expression->getValue())) {
                    $quoted = array();
                    foreach($expression->getValue() as $value) {
                        $quoted[$value] = $this->quote($value);
                    }
                    $value = '('.implode(',', $quoted).')';
                } else {
                    $value = '('.$this->quote($expression->getValue()).')';
                }
            } else {
                $value = $this->quote($expression->getValue());
            }
            return $expression->getColumn()." ".$expression->getOperator()." ".$value;
        }
    }
} 
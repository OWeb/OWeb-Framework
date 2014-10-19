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

/**
 * Class Where
 *
 * @package OWeb\db\module\Model\Query
 *
 * @TODO handle empty where creation
 */
class Where extends Expression{

    private $expressions = array();

    /**
     * @param string            $mainElement Main element to compare. Name of field
     * @param string            $operator    The operator to use
     * @param Expression|string $value       The value to compare it with
     */
    function __construct($mainElement, $operator, $value)
    {
        $this->expressions[0] = new WhereComparison($mainElement, $operator, $value);
    }

    /**
     * @param string            $mainElement Main element to compare. Name of field
     * @param string            $operator    The operator to use
     * @param Expression|string $value       The value to compare it with
     *
     * @return Where
     */
    public function addAnd($mainElement, $operator, $value){
        $where = new Where($mainElement, $operator, $value);
        $this->expressions[] =array('AND', $where);
        return $where;
    }

    /**
     * @param string            $mainElement Main element to compare. Name of field
     * @param string            $operator    The operator to use
     * @param Expression|string $value       The value to compare it with
     *
     * @return Where
     */
    public function addOr($mainElement, $operator, $value){
        $where = new Where($mainElement, $operator, $value);
        $this->expressions[] =array('OR', $where);
        return $where;
    }

    public function __toString(){
        $sql = "(".(string)$this->expressions[0];

        for($i = 1; $i < count($this->expressions); $i++){
            $sql .= " ".$this->expressions[$i][0]." ".((string)$this->expressions[$i][1]);
        }
        return $sql.")";
    }
} 
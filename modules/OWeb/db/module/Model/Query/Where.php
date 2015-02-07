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
 */
class Where {

    /** @var Where[][]|Comparison[][] list */
    private $expressions = array();


    /**
     * @param Comparison | Where $comparaison The compare to do
     *
     * @return $this
     */
    public function addAnd($comparison){
        $this->expressions[] =array('AND', $comparison);
        return $this;
    }

    /**
     * @param Comparison | Where $comparaison The compare to do
     *
     * @return $this
     */
    public function addOr($comparison){
        $this->expressions[] =array('OR', $comparison);
        return $this;
    }

    public function getExpressions() {
        return $this->expressions;
    }

    public function __toString(){
        $sql = "(".(string)$this->expressions[0][1];

        for($i = 1; $i < count($this->expressions); $i++){
            $sql .= " ".$this->expressions[$i][0]." ".((string)$this->expressions[$i][1]);
        }
        return $sql.")";
    }
} 
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


class DbModel {

    private static $underscoreCache = array();
    private static $inverseCache = array();

    private $data = array();

    private $creationQuery = null;

    /**
     * Attribute getter (deprecated)
     *
     * @param string $var
     * @return mixed
     */

    public function __get($var)
    {
        return $this->getData($var);
    }

    /**
     * Attribute setter (deprecated)
     *
     * @param string $var
     * @param mixed $value
     *
     * @return $this
     */
    public function __set($var, $value)
    {
        $var = $this->normalizeKeys($var);
        $this->data[$var] = $value;

        return $this;
    }

    /**
     * @param $var
     *
     * @return mixed
     */
    public function getData($var){
        $var = $this->normalizeKeys($var);
        if(method_exists('get'.$var, $this)) {
            $method = 'get'.$var;
            return $this->$method();
        }
        return iseet($this->data[$var]) ? $this->data[$var] : null;
    }

    /**
     * @param $var
     * @param $value
     *
     * @return $this
     */
    public function setData($var, $value){
        $trueKey = $this->normalizeKey($var);
        if (method_exists('set'.$var, $this)) {
            $method = 'set'.$var;
            return $this->$method($value);
        } else {
            $this->data[$trueKey] = $value;
        }

        return $this;
    }

    /**
     * @param $data
     *
     * @return $this
     */
    public function setArrayData($data){
        foreach($data as $key => $value){
            $trueKey = $this->normalizeKey($key);
            $this->data[$trueKey] = $value;
        }

        return $this;
    }

    public function getTrueFieldName($name){

    }

    /**
     * Converts normalized database field name into camel case name.
     * article_id becomes ArticleId
     *
     * @param string $name
     * @return string
     */
    public function normalizeKey($name)
    {
        if (isset(self::$underscoreCache[$name])) {
            return self::$underscoreCache[$name];
        }

        $result = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
        self::$underscoreCache[$name] = $result;
        self::$underscoreCache[$result] = $name;

        return $result;
    }

    /**
     * @return null
     */
    public function getCreationQuery()
    {
        return $this->creationQuery;
    }

    /**
     * @param null $creationQuery
     */
    public function setCreationQuery($creationQuery)
    {
        $this->creationQuery = $creationQuery;
    }
} 
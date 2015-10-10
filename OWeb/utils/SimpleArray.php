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

namespace OWeb\utils;

use OWeb\Exception;

/**
 * Make access to arrays easier
 *
 * @package OWeb\types\utils
 */
class SimpleArray
{

    private $data = array();

    private $separator;

    private $readOnly = false;

    function __construct($data = array(), $keySeparator = '/')
    {
        $this->data = $data;
        $this->separator = $keySeparator;
    }

    /**
     * Get value from, if is not set then return default value(null)
     *
     * @param string[]|string $key       Key or path to the value
     *                                   (either array or string separated with the separator)
     * @param mixed           $default   Default value to return if none was find
     *
     * @return mixed
     */
    public function get($key, $default = null) {
        return self::getFromKey($this->data, $key, $default, $this->separator);
    }

    /**
     * Set data inside
     *
     * @param string[]|string $key       Key or path to the value to set
     *                                   (either array or string separated with the separator)
     * @param mixed           $value     Value to put
     */
    public function set($key, $value) {
        $this->checkReadOnly();
        self::setFromKey($this->data, $key, $value, $this->separator);
    }

    /**
     * @return array All the data
     */
    public function getData(){
        return $this->data;
    }

    /**
     * Replace the data
     *
     * @param array $data new data
     */
    public function setData($data) {
        $this->checkReadOnly();
        $this->data = $data;
    }

    /**
     * Makes the data inside read only !!
     */
    public function makeReadOnly() {
        $this->readOnly = true;
    }

    protected function checkReadOnly() {
        if ($this->readOnly) {
            throw new Exception('Trying to edit content in read only SimpleArray !');
        }
    }

    /**
     * Get value from array, if is not set then return default value(null)
     *
     * @param array           $data      Array to get data from
     * @param string[]|string $key       Key or path to the value
     *                                   (either array or string separated with the separator)
     * @param mixed           $default   Default value to return if none was find
     * @param string          $separator Separator to use
     *
     * @return mixed
     */
    public static function getFromKey($data, $key, $default = null, $separator = '/')
    {
        if (!is_array($key)) {
            $key = explode($separator, $key);
        }
        return self::_getFromKey($data, $key, $default);
    }

    /**
     * Set data inside an array
     *
     * @param array           $data      array to set new value in
     * @param string[]|string $key       Key or path to the value to set
     *                                   (either array or string separated with the separator)
     * @param mixed           $value     Value to put
     * @param string          $separator separator to use with the string
     */
    public static function setFromKey(&$data, $key, $value, $separator = '/')
    {
        if (is_string($key)) {
            $key = explode($separator, $key);
        }
        $data = self::_setFromKey($data, $key, $value);
    }

    /**
     * Private unsecured method to set data in array
     *
     * @param $data
     * @param $key
     * @param $value
     *
     * @return array
     */
    private static function _setFromKey($data, $key, $value)
    {
        if (empty($key)) {
            return $value;
        } else {
            if (!is_array($data)) {
                $data = array();
            }
            $currentKey = array_shift($key);
            $data[$currentKey] = self::_setFromKey($data, $key, $value);
            return $data;
        }
    }

    /**
     * Private unsecured function for getFromKey
     *
     * @param $data
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    private static function _getFromKey($data, $key, $default)
    {
        if (empty($key)) {
            return $data;
        } else {
            $currentKey = array_shift($key);
            return isset($data[$currentKey]) ? self::_getFromKey($data[$currentKey], $key, $default) : $default;
        }
    }
} 
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

namespace Extension\core\cache;


use Model\OWeb\cache\Cache;
use OWeb\types\Extension;

class Caching extends Extension
{

    private $cacheDir = "";

    private $datas = array();

    protected function init()
    {
        $this->cacheDir = OWEB_DIR_DATA . '/cache';

        if (!is_dir($this->cacheDir))
            !mkdir($this->cacheDir);

        $this->addAlias("getCache", "createCacheElement");
    }

    /**
     * @param string   $key      Key to be used to save the cache
     * @param callback $callback Call to make to get the data if not up to date
     * @param arraay   $params
     * @param int      $timeout  Time for time out
     *
     * @return Object
     */
    public function createCacheElement($key, $callback, $params, $timeout)
    {
        if(isset($this->datas[$key]))
            return $this->datas[$key];

        $upToDate = true;
        $data     = null;

        if (file_exists($this->cacheDir . '/' . $key . '.cache')) {
            try {
                /** @var Cache $data */
                $data = unserialize(file_get_contents($this->cacheDir . '/' . $key . '.cache'));

                if ($data->time + $timeout < time()){
                    $upToDate = false;
                }
            } catch (\Exception $e) {
                $upToDate = false;
            }
        } else {
            $upToDate = false;
        }

        if ($upToDate && $data != null) {
            $this->datas[$key] =  $data->data;
            return $this->datas[$key];
        } else {
            $data = new Cache();

            $data->data = call_user_func_array($callback, $params);
            $data->time = time();
            file_put_contents($this->cacheDir . '/' . $key . '.cache', serialize($data));
            $this->datas[$key] = $data->data;
            return $this->datas[$key];
        }
    }
}
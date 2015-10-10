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

namespace OWeb\cache\module\Extension;
use OWeb\types\extension\Extension;

/**
 * Class AbstractCache, is a cache that never cache. It just looks like it works. It should be extended to actually work
 *
 * @package OWeb\cache\module\Extension
 */
class AbstractCache extends Extension{

    protected function init()
    {

    }

    protected function ready()
    {

    }

    /**
     * @return $this
     */
    public function getCacheHandler() {
        return $this;
    }

    /**
     * Caches a new data
     *
     * @param string   $key  The cache key
     * @param mixed   $data Data to cache
     * @param int      $ttl  TTL for the data. 0 means infinite
     * @param string[] $tags Tags to add to the cached data. Allows clearing ot the data
     *
     * @return bool
     */
    public function cacheData($key, $data, $ttl = 0, $tags)
    {
        return true;
    }

    /**
     * Gets the cached value if exist if not null
     *
     * @param string $key The cache key
     *
     * @return null|mixed
     */
    public function getCache($key)
    {
        return null;
    }

    /**
     * Checks if a cache exists. (it you will get the cache you probably should use directly getCache as it will optimize the calls)
     *
     * @param $key
     *
     * @return bool
     */
    public function existCache($key)
    {
        return false;
    }

    /**
     * Gets multiple cache values.
     *
     * @param string[] $keys The cache key
     *
     * @return array
     */
    public function getMultipleCache($keys)
    {
        return array();
    }

    /**
     * Deletes a cache
     *
     * @param string $key The cache key
     *
     * @return bool did the cache exists.
     */
    public function deleteCache($key){
        return true;
    }

    /**
     * Deletes all caches that has this tag
     *
     * @param string $tag
     */
    public function deleteCacheByTag($tag)
    {
    }
}
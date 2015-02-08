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

namespace OWeb\cache\file\module\Extension;


use OWeb\abs\cache\module\Extension\AbstractCache;
use OWeb\cache\file\module\Model\Cache;
use OWeb\cache\file\module\Model\Settings;
use OWeb\Exception;
use OWeb\types\utils\File;

/**
 * Class FileCache
 *
 * @method AbstractCache getCacheHandler()
 *
 * @package OWeb\cache\file\module\Extension
 */
class FileCache extends AbstractCache{

    private $settings;

    /** @var Cache[] */
    private $loaded = array();

    protected function ready()
    {
        $this->settings = new Settings();
        File::mkDir($this->settings->path.'/'.'data');
        File::mkDir($this->settings->path.'/'.'tags');
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
    public function cacheData($key, $data, $ttl = 0, $tags = array())
    {
        $fileName = File::cleanFileName($key);

        $dataCache = new Cache();
        $dataCache->key = $key;
        $dataCache->data = $data;
        $dataCache->expireDate = time() + $ttl;
        $dataCache->tags = $tags;

        file_put_contents($this->settings->path.'/data/'.$fileName, serialize($dataCache));

        $this->loaded[$key] = $dataCache;

        if (!empty($tags)) {
            foreach ($tags as $tag) {
                File::mkDir($this->settings->path.'/tags/'.$tag);
                file_put_contents($this->settings->path.'/tags/'.$tag.'/'.$fileName, '');
            }
        }
    }

    /**
     * Gets the cached value if exist and not timed out if not null
     *
     * @param string $key The cache key
     *
     * @return null|mixed
     */
    public function getCache($key)
    {
        if (isset($this->loaded[$key])) {
            return $this->loaded[$key];
        }

        $fileName = File::cleanFileName($key);

        if (file_exists($this->settings->path.'/data/'.$fileName)) {
            /** @var Cache $data */
            $data = unserialize(file_get_contents($this->settings->path.'/data/'.$fileName));

            if ($data->expireDate > time()) {
                $this->loaded[$key] = $data;
                return $data->data;
            } else {
                //Expired delete existing data
                $this->deleteCache($key);
            }
        }

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
        return !is_null($this->getCache($key));
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
        $result = array();
        foreach ($keys as $key) {
            $result[$key] = $this->getCache($key);
        }

        return $result;
    }

    /**
     * Deletes a cache
     *
     * @param string $key The cache key
     *
     * @throws Exception if error
     *
     * @return bool did the cache exists.
     */
    public function deleteCache($key)
    {
        if ($this->existCache($key)) {

            $data = $this->loaded[$key];

            $fileName = File::cleanFileName($key);

            if (!unlink($this->settings->path.'/data/'.$fileName)) {
                throw new Exception('Couln\'t delete cache. Issue with permissions !!');
            }

            if (!empty($data->tags)) {
                foreach ($data->tags as $tag) {
                    if (file_exists($this->settings->path.'/tags/'.$tag.'/'.$fileName)) {
                        if (!unlink($this->settings . '/tags/' . $tag . '/' . $fileName)) {
                            throw new Exception('Couln\'t delete cache tags. Issue with permissions !!');
                        }

                        if (File::isDirEmpty($this->settings . '/tags/' . $tag)) {
                            rmdir($this->settings . '/tags/' . $tag);
                        }
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Deletes all caches that has this tag
     *
     * @param string $tag
     */
    public function deleteCacheByTag($tag)
    {
        $keys = array();

        if (is_dir($this->settings . '/tags/' . $tag)) {
            foreach (scandir($this->settings . '/tags/' . $tag) as $file) {
                if ($file != '.' && $file != '..') {
                    $cache = $this->loadByFileName($file);

                    if (is_null($cache)) {
                        unlink($this->settings . '/tags/' . $tag. '/'.$file);
                    } else {
                        $keys[$cache->key] = true;
                        $this->loaded[$cache->key] = $cache;
                    }
                }
            }
        }

        if (!empty($keys)) {
            foreach ($keys as $key => $ignore) {
                $this->deleteCache($key);
            }
        }
    }

    /**
     * @param $fileName
     *
     * @return Cache
     */
    protected function loadByFileName($fileName) {
        if (file_exists($this->settings->path.'/data/'.$fileName)) {
            /** @var Cache $data */
            return unserialize(file_get_contents($this->settings . '/data/' . $fileName));
        }

        return null;
    }
} 
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

namespace OWeb;

use OWeb\manage\Dispatcher;
use OWeb\manage\Extension;

define('OWEB_DIR', __DIR__);

/**
 * Including autoloader manually.
 */
require_once __DIR__.'/autoLoader.php';

/**
 * The main OWeb class that runs the wheel of time and this the world
 *
 * @package OWeb
 */
class OWeb {

    /**
     * @var OWeb Instance of running OWeb
     */
    private static $instance = null;

    /**
     * base PHP variables
     *
     * @var Array
     */
    private $_get, $_post, $_files, $_cookies, $_server, $_adresse;

    /**
     * @var Dispatcher
     */
    private $_manageEvents;

    private $_manageExtensions;

    /**
     * @param array $get
     * @param array $post
     * @param array $files
     * @param array $cookies
     * @param array $server
     * @param array $adr
     */
    public function __construct(&$get, &$post, &$files, &$cookies, &$server, $adr) {

        /**
         * Starting instance Run time
         */
        $this->getRunTime();

        /**
         * Affecting base PHP variables
         */
        $this->_get= $get;
        $this->_post= $post;
        $this->_files= $files;
        $this->_cookies = $cookies;
        $this->_server= $server;
        $this->_adresse = $adr;

        new AutoLoader();

        $this->_manageEvents = new Dispatcher();

        $this->_manageExtensions = new Extension($this->_manageEvents);

    }


    public function getRunTime(){
        static $a;
        if($a == 0){
            $a = microtime(true);
            return 0;
        }
        else return microtime(true)-$a;
    }

} 
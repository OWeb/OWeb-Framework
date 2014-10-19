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

namespace OWeb\web\displayMode\module\Controller\errors;

use OWeb\types\Controller;
use OWeb\web\displayMode\module\Model\Link;
use OWeb\web\displayMode\module\Model\string;
use OWeb\web\header\module\Model\int;

/**
 * @method void addHeader(String $header, int $type = -1, String $key = null)
 * @method Link url(string $page, array $params = array())
 * @method Link getCurrentUrl()
 */
class Error extends Controller{

    /**
     * Called after construction when OWeb is ready.
     *
     * @return void
     */
    public function init()
    {

    }

    /**
     * Called before displaying the window
     *
     * @return mixed
     */
    protected function onDisplay()
    {

    }

    function __call($name, $arguments)
    {

    }
}
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

namespace OWeb\web\displayMode\module\Controller;


use OWeb\db\module\Model\DataBaseConnectionInterface;
use OWeb\types\Controller;

/**
 * Interface DataBaseConnection
 *
 * @method \PDO getDataBaseConnection() Returns the PDO connection to the database
 * @method void addHeader(String $header, int $type = -1, String $key = null);
 * @method Link url(string $page, array $params = array()) Returns a Link object to manipulate or display;
 * @method Link getCurrentUrl() Returns a Link object for the current page;
 */
class ExempleHome extends Controller implements DataBaseConnectionInterface{

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
        //nothing to do
    }


}
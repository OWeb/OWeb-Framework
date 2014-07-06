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

namespace OWeb\types\autoloader;

/**
 * Information about a loaded file
 *
 * @package OWeb\types\autoloader
 */
class FileInformation
{
    /** @var null | String path to the module. IF null then core component */
    public $modulePath = null;

    /** @var String path to the file, inside the module or full path of the core component */
    public $relativePath;

    /** @var null | String Full path to the file if it could be loaded */
    public $fullPath = null;

    /** @var array ALl pathes in which the system looks in order to load this page */
    public $possiblePaths = array();

} 
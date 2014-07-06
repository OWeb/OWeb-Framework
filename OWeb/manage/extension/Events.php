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

namespace OWeb\manage\extension;


use OWeb\types\extension\Extension;

interface Events
{

    /**
     * Called before at start of the create extension. Only called if the extension wasn't loaded before
     *
     * @param $name Name of the extension that the system will try to load
     *
     * @return mixed
     */
    public function OWeb_Extension_preCreateExtension($name);

    /**
     * @param String           $name      Name of the extension
     * @param Extension | null $extension The extension object, null if it wasn't found
     *
     * @return mixed
     */
    public function OWeb_Extension_postCreateExtension($name, $extension);

    /**
     * @param $extension
     *
     * @return mixed
     */
    public function OWeb_Extension_preInitExtension($extension);

    /**
     * @param $extension
     *
     * @return mixed
     */
    public function OWeb_Extension_postInitExtension($extension);
}
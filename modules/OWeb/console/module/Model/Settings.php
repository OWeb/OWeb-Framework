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

namespace OWeb\console\module\Model;

use OWeb\settings\module\Model\Setting;
use OWeb\settings\module\Model\SimpleXMLElement;

class Settings extends Setting {
    /** @var array List of all modules that has a console to load.*/
    public $extensions = array();

    /**
     * Chanage the file loaded for the settings.
     */
    public function __construct()
    {
        // The configuration is in another file.
        parent::__construct();

        $extensions = array();
        if (!empty($this->extensions) && !empty($this->extensions->children())) {
            foreach ($this->extensions->children() as $extension) {
                $extensions[] = (string) $extension['name'];
            }
        }

        $this->extensions = $extensions;
    }


} 
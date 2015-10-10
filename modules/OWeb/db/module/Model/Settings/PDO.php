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

namespace OWeb\db\module\Model\Settings;

use OWeb\settings\module\Model\Exception;
use OWeb\settings\module\Model\Setting;
use OWeb\settings\module\Model\SimpleXMLElement;
use OWeb\utils\SimpleArray;

class PDO extends Setting {

    /** @var PDOSetting  */
    protected $default;

    /** @var SimpleArray  */
    protected $settings;

    /** @var SimpleArray  */
    protected $usages;

    function __construct()
    {
        $this->settings = new SimpleArray();
        $this->usages = new SimpleArray();

        $this->default = null;

        /** @var \SimpleXMLElement $settings */
        $settings = $this->getRawSettings();


        foreach ($settings->children() as $value){
            /** @var \SimpleXMLElement $value */
            if ( empty($value['name'])) {
                throw new Exception('Database settings must contain a name : <connection name="...">');
            }

            $name = (string) $value['name'];

            // If usage then we don't have settings to read.
            if (!empty($value['use'])) {
                $this->usages->set($name, (string) $value['use']);
            }
            else {
                // We need to read the settings.
                $setting = new PDOSetting($name, $value);
                $this->settings->set($name, $setting);

                if ($setting->isDefault()) {
                    $this->default = $setting;
                }
            }
        }

        // Check all usages and check connections.
        foreach ($this->usages->getData() as $name => $use) {
            if (empty($this->settings->get($use))) {
                throw new Exception("Database connection '$name' uses '$use' but the connection '$use' couldn't be found !");
            }
        }
    }

    /**
     * @param $name
     * @return PDOSetting
     */
    public function getSetting($name) {
        return $this->settings->get($name);
    }

    /**
     * @param $name
     * @return PDOSetting
     */
    public function getUsage($name) {
        return $this->usages->get($name);
    }

    /**
     * @return PDOSetting
     */
    public function getDefault() {
        return $this->default;
    }
} 
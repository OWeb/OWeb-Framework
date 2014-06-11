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
namespace OWeb\manage;
/**
 * Will load setting file(s)
 * OWeb core will ask it to load the main setting file.
 *
 * The settings manager seperates settings of different
 * Classes so that you can easily have acces to a setting
 * It can load multiple different files, for different Classes
 *
 * @author oliver
 */
class Settings extends \OWeb\utils\Singleton
{

    private $setting_files = array();

    private $file_settings;

    private $class_setings;


    /**
     * The Setting array for you, in the default file or the file you asked it to check
     *
     * @param String $asker The namen or Object of the one who asks for the settings
     * @param string $explodedName
     *
     * @return array()
     */
    public function getSetting($asker, $explodedName = null)
    {

        if (is_string($asker))
            $name = $asker;
        else if (is_object($asker))
            $name = get_class($asker);

        if (!isset($this->class_setings[$name])) {
            $fileMain = OWEB_CONFIG;

            //Loading the default file first.
            if (!isset($this->file_settings[$fileMain]))
                $this->file_settings[$fileMain] = $this->loadFile($fileMain);

            $this->loadSecondaryFiles();

            if ($explodedName == null)
                if ($asker instanceof \OWeb\types\NamedClass)
                    $explodedName = $asker->get_exploded_name();
                else if (is_object($asker))
                    $explodedName = explode('\\', $name);

            $file = OWEB_CONFIG_DIR;
            for ($i = 0; $i < sizeof($explodedName); $i++) {
                $file .= '/' . $explodedName[$i];
            }
            $file .= '.ini';

            if (!isset($this->file_settings[$file]))
                $this->file_settings[$file] = $this->loadFile($file);

            if (!isset($this->file_settings[$fileMain][$name]))
                $this->file_settings[$fileMain][$name] = array();

            $this->class_setings[$name] = array_merge(
                $this->file_settings[$file],
                $this->file_settings[$fileMain][$name]
            );
        }

        return $this->class_setings[$name];
    }

    /**
     * Recoveres the value of the setting for that class(object)
     *
     * @param mixed $asker The object or class that asks the Information
     * @param       $asked The name of the information to get
     *
     * @return mixed If information available string if not false
     */
    public function getDefSettingValue($asker, $asked)
    {
        $settings = $this->getSetting($asker);

        return isset($settings[$asked]) ? $settings[$asked] : null;
    }

    /**
     * Loads a different configuration file on top of the existing one
     *
     * @param $file
     *
     * @throws exceptions\Settings
     */
    private function loadFile($file)
    {

        $f = array();
        if (file_exists($file))
            try {
                $f = parse_ini_file($file, true);
            } catch (\Exception $ex) {
                throw new \OWeb\manage\exceptions\Settings("Failed to load Settings file : '$file'", 0, $ex);
            }

        return $f;

    }

    private function loadSecondaryFiles()
    {
        foreach ($this->setting_files as $file) {
            if (!isset($this->file_settings[$file]))
                $this->file_settings[$file] = $this->loadFile($file);
        }
    }
}

?>

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

namespace OWeb\log\module\Extension;

use OWeb\log\module\Model\Settings;
use OWeb\OWeb;
use OWeb\types\extension\Extension;

class Log extends Extension{

    const LEVEL_INFO = 3;
    const LEVEL_WARN = 2;
    const LEVEL_ERROR = 1;
    const LEVEL_NONE = 0;

    private $settings = null;

    protected function init()
    {
    }

    protected function ready()
    {
    }

    /**
     * Logs in a file.
     *
     * @param mixed  $msg   Message to log
     * @param int    $level Log level
     * @param string $file  File to write logs into
     */
    public function log($msg, $level = self::LEVEL_INFO, $file = null)
    {
        /** @var Settings $settings */
        $settings = Settings::getInstance();

        if ($settings->logLevel < $level) {
            return;
        }

        if ($file == null) {
            $file = $settings->defaultFile;
        }

        $logDir = $settings->path;

        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
            chmod($logDir, 0777);
        }

        $logFile = $logDir.'/'.$file.'.log';

        if (!file_exists($logFile)) {
            file_put_contents($logFile, '');
            chmod($logFile, 0777);
        }

        $title = "";
        $additional = array();

        if (is_object($msg)) {
            $additional = explode("\n", print_r($msg, true));
            $title = "PrintR result : ";
        }
        else if (is_array($msg))
        {
            $additional = explode("\n", print_r($msg, true));
            $title = array_shift($additional);
        }

        $dateTime = date("Y-m-d H:i:s");
        $logName = $this->getLogLevelName($level);

        $title = "[$dateTime] [$logName] $title";

        $this->logToFile($title, $logFile, $additional);
    }

    protected function logToFile($title, $logFile, $additional = array()){
        file_put_contents($logFile, $title."\n", FILE_APPEND);

        if (!empty($additional)) {
            foreach ($additional as $msg){
                if(!empty($msg)) {
                    file_put_contents($logFile, "\t\t\t\t".$msg."\n", FILE_APPEND);
                }
            }
        }
    }

    protected function getLogLevelName($logLevel){
        switch ($logLevel) {
            case self::LEVEL_INFO :
                return "INFO";
            case self::LEVEL_WARN :
                return "WARN";
            case self::LEVEL_ERROR :
            default :
                return "ERROR";

        }
    }
}
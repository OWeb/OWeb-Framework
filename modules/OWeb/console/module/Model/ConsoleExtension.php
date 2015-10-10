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

use OWeb\console\module\Extension\Console;
use OWeb\OWeb;

/**
 * Trait ConsoleExtension allows easy usage of the console extension in other extensions.
 * @package OWeb\console\module\Model
 */
trait ConsoleExtension
{
    /** @var  Console */
    private $__consoleExtension;

    /**
     * Add dependency
     */
    protected function initConsoleExtension()
    {
        $this->__consoleExtension = OWeb::getInstance()->getManageExtensions()->getExtension('OWeb\console\module\Extension\Console');
    }

    /**
     * Add a console command.
     *
     * @param string $command
     *  The name of the command
     *
     * @param $callback
     *  The callback to call.
     */
    protected function addCmd($command, $callback)
    {
        $this->__consoleExtension->addCmd($command, $callback);
    }

    /**
     * @see Console::console
     */
    protected function console($msg)
    {
        $this->__consoleExtension->console($msg);
    }
}
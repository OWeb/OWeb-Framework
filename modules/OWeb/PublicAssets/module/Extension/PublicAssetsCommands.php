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

namespace OWeb\PublicAssets\module\Extension;

use OWeb\console\module\Model\ConsoleExtension;
use OWeb\OWeb;
use OWeb\types\extension\Extension;

/**
 * Install all the assets of the different modules.
 *
 * @package OWeb\console\module\Extension
 */
class PublicAssetsCommands extends Extension
{
    // The extension needs to add some console stuff.
    use ConsoleExtension;

    protected $commands = array();

    /**
     * Add Command to install assets.
     */
    protected function init()
    {
        $this->initConsoleExtension();
        //$this->addCmd("oweb:asset:install", array($this, 'install'));

    }

    /**
     * Now that the system is ready let's see if we can run a command.
     */
    protected function ready()
    {
    }

    public function install() {
        $this->console('Done !!');
    }
}
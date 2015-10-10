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

namespace OWeb\db\module\Extension;


use GetOptionKit\OptionResult;
use OWeb\console\module\Model\ConsoleExtension;
use OWeb\db\module\Model\DBExtension;
use OWeb\types\extension\Extension;
use \OWeb\console\module\Model\Cmd as CmdDefinition;

class Cmd  extends Extension {
    // The extension needs to add some console stuff.
    use ConsoleExtension;
    use DBExtension;

    /**
     * Add Command to work with the database.
     */
    protected function init()
    {
        $this->initConsoleExtension();

        $cmd = new CmdDefinition($this, 'testCmd', 'Test the connection to a certain database');
        $cmd->addOption('n|name?', 'The name of the database to connect to, if not default database.')
            ->isa('String');
        $this->addCmd('oweb:db:test', $cmd);
    }

    /**
     * Now that the system is ready let's see if we can run a command.
     */
    protected function ready()
    {
    }

    /**
     * Check if can connect on a certain database.
     *
     * @param OptionResult $params
     *   Parameters.
     */
    public function testCmd($params) {
        if ($params->has('name')) {
            $this->getDbConnection($params->keys['name']->value);
        } else {
            $this->getDbConnection(md5("__"));
        }
        $this->console('Sucess !!');
    }
}
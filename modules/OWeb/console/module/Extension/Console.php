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

namespace OWeb\console\module\Extension;

use GetOptionKit\OptionResult;
use OWeb\console\module\Model\Cmd;
use OWeb\console\module\Model\Settings;
use OWeb\log\module\Extension\Log;
use OWeb\OWeb;
use OWeb\types\extension\Extension;
use OWeb\utils\SimpleArray;

/**
 * Give OWeb some console capabilities.
 *
 * @package OWeb\console\module\Extension
 */
class Console extends Extension
{
    /** @var SimpleArray  */
    protected $commands = NULL;

    /**
     * Add alias to allow other extensions to add commands.
     */
    protected function init()
    {
        $this->commands = new SimpleArray();

        // Add default commands.
        $help = array(
            'Can also be used to filter amon existing commands :');
        $cmd = new Cmd($this, 'listCmd', 'See the list of all available commands.', $help);
        $cmd->addOption('n|name?', 'Search for a certain command filtering by name')
            ->isa('String');
        $this->addCmd('oweb:cmd:list', $cmd);

        $cmd = new Cmd($this, 'helpCmd', 'See detailled information on a command');
        $cmd->addOption('n|name?', 'Get help for the command')
            ->isa('String');
        $this->addCmd('oweb:cmd:man', $cmd);

        // Start all extensions that has a cmd to declare.
        /** @var Settings $settings */
        $settings = Settings::getInstance();
        foreach ($settings->extensions as $extensionName) {
            $this->addDependance($extensionName);
        }
    }

    /**
     * Now that the system is ready let's see if we can run a command.
     */
    protected function ready()
    {
        $args = OWeb::getInstance()->getGet();

        // Default command if nothing is found
        $cmd = new Cmd($this, 'notFound', '');

        /** @var Cmd $cmd */
        $cmd = $this->commands->get($args->get(1, 'oweb:cmd:list'), $cmd);

        $cmd->execute($args);
    }

    /**
     * Add a console command.
     *
     * @param string $command
     *  The name of the command
     *
     * @param Cmd $description
     *  The command to be used.
     */
    public function addCmd($command, Cmd $description)
    {
        $this->commands->set($command, $description);
    }

    /**
     * Log & Display a result/error.
     *
     * @param string $msg
     *   Message to display.
     */
    public function console($msg) {
        OWeb::getInstance()->log($msg, Log::LEVEL_INFO, 'console');
        echo $msg;
    }

    /**
     * Method to call when the command wasn't found.
     */
    public function notFound() {
        $this->console('Command not found !');
    }

    /**
     * Display the list of all available commands.
     *
     * @param OptionResult $params
     *   Parameters.
     */
    public function listCmd($params) {
        echo "List of available Commands  : " . OWEB_NEW_LINE;
        foreach ($this->commands->getData() as $command => $cmd) {
            /** @var Cmd $cmd */
            if (!$params->has('name') || strpos($command, $params->keys['name']->value) !== FALSE) {
                echo "\t$command  : " . $cmd->getDescription() . OWEB_NEW_LINE;
            }
        }

        echo "\n\n";
    }

    public function helpCmd($params) {
        if ($params->has('name')) {
            $this->displayHelpFor($params->keys['name']->value);
        } else {
            $this->displayHelpFor('oweb:cmd:man');
        }
    }

    protected function displayHelpFor($command) {

        /** @var Cmd $cmd */
        $cmd = $this->commands->get($command);

        if($cmd) {
            echo $cmd->getFormattedHelp();
            echo $cmd->getUsage();
        } else {
            echo "Command not found !";
            $this->displayHelpFor('oweb:cmd:man');
        }
    }
}
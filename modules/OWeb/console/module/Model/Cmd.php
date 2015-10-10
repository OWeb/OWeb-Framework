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

use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;
use OWeb\utils\SimpleArray;

/**
 * Definition of a console command
 *
 * @package OWeb\console\module\Model
 */
class Cmd
{
    /** @var Object The object that will need to be called. */
    private $object;

    /** @var string The name of the method to call from the object */
    private $method;

    /** @var string Description about how to use the command */
    private $description;

    /** @var string[] Detailled information on how to use the method */
    private $help;

    /** @var string[] Description of the parameters the command needs */
    private $options;

    /**
     * Cmd constructor.
     * @param Object $object
     *  Object to call the method in.
     *
     * @param string $method
     *  The name of the method to call.
     *
     * @param string $description
     *  Description of the Command.
     *
     * @param \string[] $help
     *  More information about how to use the command.
     */
    public function __construct($object, $method, $description, array $help = array())
    {
        $this->object = $object;
        $this->method = $method;
        $this->description = $description;
        $this->help = $help;

        $this->options = new OptionCollection();
    }


    /**
     * Execute this command.
     *
     * @param SimpleArray $params
     *  Command parameters
     */
    public function execute($params) {
        if ($this->object != null && method_exists($this->object, $this->method)) {
            $parser = new OptionParser($this->options);

            try {
                $args = $params->getData();
                $result = $parser->parse($args);

                call_user_func_array(array($this->object, $this->method), array($result));
            } catch (\Exception $e) {
                echo $e->getMessage() . OWEB_NEW_LINE;

                echo $this->getUsage();
            }
        } else {
            new ConsoleException("The registered command isn't set up properly (Code issue)");
        }
    }

    /**
     * Get lines of help that describes the command.
     *
     * @return \string[]
     */
    public function getHelp()
    {
        return $this->help;
    }

    public function getFormattedHelp()
    {
        if (!empty($this->help)) {
            return $this->getDescription() . OWEB_NEW_LINE . $this->_getFormattedHelp($this->help);
        } else {
            return $this->getDescription();
        }
    }

    /**
     * Format help with tabulations
     *
     * @param string|string[] $help
     *   The help to format.
     * @param int $depth
     *   The depth at which we are.
     *
     * @return string
     *   The formatted help.
     */
    protected function _getFormattedHelp($help, $depth = 0) {
        $helpString = '';

        if (!is_array($help)) {
            $helpString .= $this->getTabulation($depth) . $help;
        } else {
            foreach($help as $group) {
                $helpString .= $this->_getFormattedHelp($group, $depth+1);
            }
        }

        return $helpString;
    }

    public function getUsage() {
        $printer = new ConsoleOptionPrinter();
        $print = $printer->render($this->options);

        $usage = '';
        if (!empty($print)) {
            $usage .= OWEB_NEW_LINE . "Usage : " . OWEB_NEW_LINE;
            $usage .= $print;
        }

        return $usage;
    }

    /**
     * Get a certain amount of tabulation.
     *
     * @param int $nb
     *  Number of tabulation to get.
     *
     * @return string
     *  The tabulations.
     */
    protected function getTabulation($nb) {
        $tabs = '';
        for($i = 0; $i < $nb; $i++) {
            $tabs .= "\t";
        }

        return $tabs;
    }

    /**
     * Get the short description of the command
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $option
     * @param $description
     *
     * @return \GetOptionKit\Option
     *
     * @throws \GetOptionKit\Exception
     */
    public function addOption($option, $description) {
        return $this->options->add($option, $description);
    }
}
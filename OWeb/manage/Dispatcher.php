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


use OWeb\types\event\Event;

class Dispatcher
{

    private $_actions;

    /**
     * Register a function to be called in a certain event.
     *
     * @param string $event  The name of the Event
     * @param mixed $object The object of which the function will be called
     */
    public function registerEvent($event, $object)
    {
        $this->_actions[$event][] = new Event($object, $event);
    }

    /**
     * Will send and event and call the functions that has registered to that Event.
     *
     * @param String       $event  The name of the Event
     * @param Array(mixed) $params The parameters array you want to send with the event.
     */
    public function dispatchEvent($event, $params = array())
    {

        if (isset ($this->_actions[$event])) {
            foreach ($this->_actions[$event] as $eventO) {
                $eventO->doEvent($params);
            }
        }
    }

} 
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
namespace OWeb\manage;

/**
 * Manages the event system of OWeb. 
 *
 * @author De Cramer Oliver
 */
class Events extends \OWeb\utils\Singleton{
	
	protected $actions = array();
	
	
	/**
	 * Register a function to be called in a certain event.
	 * 
	 * @param type $event	The name of the Event
	 * @param type $object	The object of which the function will be called
	 * @param type $fn		The name of the function to call
	 */
	public function registerEvent($event, $object, $fn){
		$this->actions[$event][] = new \OWeb\manage\events\Event($object, $fn);
	}
	
	/**
	 * Will send and event and call the functions that has registered to that Event.
	 * 
	 * @param String $event The name of the Event
	 * @param Array(mixed) $params The parameters array you want to send with the event.
	 */
	public function sendEvent($event, $params = array()){
		
		if(isset ($this->actions[$event])){
			foreach ($this->actions[$event] as $eventO){
				$eventO->doEvent($params);
			}
		}
	}
	
}

?>

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
 * Subviews are just controllers not used as pages. Any controller can be used as a SubView.
 * This class will allow you to load and use them easily.
 * You may also want to load them manually your self. 
 * 
 * notes :
 *	* SubViews wont't be by default initialized. you need to do so by calling ->init() of the Controller returned by getSubView
 *	* The same way SubViews wao't be affected by actions, you need to call ->doAction($actionName) of the Controller
 *
 * @author De Cramer Oliver
 */
class SubViews extends \OWeb\utils\Singleton{
	
	private $subViews = array();
	
	function __construct() {
		$events = \OWeb\manage\Events::getInstance();
	}
	
	/**
	 * Will return an instance of the Controller required as a subview. 
	 * 
	 * !! A controller can also be initiated throught manually with new <name> but 
	 * it might then need manual initialisation
	 * 
	 * !! since OWeb 0.3.2 this will generate only Singletons. 
	 * To get a new instance every time you may create the controller yourself
	 * or use getNewSubView
	 * 
	 * @param type $name Name of the controller you want to use as a sub view.
	 * @return \Controller The controller that was asked.
	 * @throws \OWeb\manage\exceptions\Controller
	 */
	public function getSubView($name){
		if(isset($this->subViews[$name]))
			return $this->subViews[$name];
		else 
			return $this->getNewSubView($name);
	}
	
	/**
	 * Will return a new instance of the Controller required as a subview. After
	 * having initialized it. 
	 *  
	 * !! A controller can also be initiated throught manually with new <name> but 
	 * it might then need manual initialisation
	 * 
	 * @param type $name Name of the controller you want to use as a sub view.
	 * @return \Controller The controller that was asked.
	 * @throws \OWeb\manage\exceptions\Controller
	 */
	public function getNewSubView($name){
		try{
			$controller = new $name();

			if(! ($controller instanceof \OWeb\types\Controller))
				throw new \OWeb\manage\exceptions\Controller("The class \"".$name."\" isn't an instance of \OWeb\Types\Controller");	
			
			if(!isset($this->subViews[$name]))
				$this->subViews[$name] = $controller;
				
			\OWeb\manage\Events::getInstance()->sendEvent('loaded@OWeb\manage\SubViews',$controller);
			$controller->init();
			return $controller;
			
		}catch(\Exception $ex){
			throw new \OWeb\manage\exceptions\Controller("The SubView : \"".$name."\"couldn't be loaded due to Errors",0,$ex);
		}
	}
	
	
}

?>

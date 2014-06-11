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

namespace Controller\OWeb\widgets\jquery_ui;

use \Controller\OWeb\widgets\jquery\CodeGenerator as JQueryGen;

/**
 * Allows the creation of a jqueryui Tabs.
 * Demo : http://jqueryui.com/accordion/
 * Doc : http://api.jqueryui.com/accordion/
 *
 * @author De Cramer Oliver
 */
class Tabs extends JQueryGen{

	private $sections = array();
	
	public function init() {
		parent::init();
		
		$this->setFunction('tabs');
		$this->addOption('active', 0);
		$this->addOption('collapsible', 'false');
		$this->addOption('disabled', 'false');
		$this->addOption('event', '"click"');
		$this->addOption('heightStyle', '"content"');
		$this->addOption('hide', 'null');
		$this->addOption('show', 'null');
	}
	
	/**
	 * Which panel is currently open.
	 * 
	 * @param Boolean or Integer
	 *		Boolean: Setting active to false will collapse all panels. 
	 *			This requires the collapsible option to be true.
	 *		Integer: The zero-based index of the panel that is active (open). 
	 *			A negative value selects panels going backward from the last panel.
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setActive($active = 0){
		$this->addParams('active', $active);
		return $this;
	}
	
	/**
	 * Whether all the sections can be closed at once. Allows collapsing the active section
	 * 
	 * @param Boolean $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setCollapsible($val = 'false'){
		$this->addParams('collapsible', $val);
		return $this;
	}
	
	/**
	 * Disables the accordion if set to true.
	 * 
	 * @param Boolean $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setDisabled($val = 'false'){
		$this->addParams('disabled', $val);
		return $this;
	}
	
	/**
	 * The event that accordion headers will react to in order to activate the associated panel. 
	 * Multiple events can be specified, separated by a space.
	 * 
	 * @param String $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setEvent($val = 'click'){
		$this->addParams('event', $val);
		return $this;
	}
	
	/**
	 * Controls the height of the accordion and each panel. Possible values:
	 * 
	 * @param String $val
	 * 		"auto": All panels will be set to the height of the tallest panel.
	 *		"fill": Expand to the available height based on the accordion's parent height.
	 *		"content": Each panel will be only as tall as its content.
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setHeightStyle($val = '"content"'){
		$this->addParams('heightStyle', $val);
		return $this;
	}
	
	/**
	 * If and how to animate the hiding of the panel.
	 * 
	 * @param Boolean or Number or String or Object $val
	 *		Boolean : When set to false, no animation will be used and the panel will be hidden immediately. 
	 *				When set to true, the panel will fade out with the default duration and the default easing.
	 * 		Number : The panel will fade out with the specified duration and the default easing.
	 *		String : The panel will be hidden using the specified effect. 
	 *			The value can either be the name of a built-in jQuery animation method, such as "slideUp", or the name of 
	 *			a jQuery UI effect, such as "fold". In either case the effect 
	 *			will be used with the default duration and the default easing.
	 *		Object : If the value is an object, then effect, delay, duration, and easing 
	 *			properties may be provided. If the effect property contains the name of a jQuery method, 
	 *			then that method will be used; otherwise it is assumed to be the name of a jQuery UI effect. 
	 *			When using a jQuery UI effect that supports additional settings, you may include those settings in the object 
	 *			and they will be passed to the effect. If duration or easing is omitted, then the default values will be used. 
	 *			If effect is omitted, then "fadeOut" will be used. If delay is omitted, then no delay is used.
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setHide($val = 'null'){
		$this->addParams('hide', $val);
		return $this;
	}
	
	/**
	 * Controls the height of the accordion and each panel. Possible values:
	 * 
	 * @param Boolean or Number or String or Object $val
	 *		Boolean : When set to false, no animation will be used and the panel will be hidden immediately. 
	 *				When set to true, the panel will fade out with the default duration and the default easing.
	 * 		Number : The panel will fade out with the specified duration and the default easing.
	 *		String : The panel will be hidden using the specified effect. 
	 *			The value can either be the name of a built-in jQuery animation method, such as "slideUp", or the name of 
	 *			a jQuery UI effect, such as "fold". In either case the effect 
	 *			will be used with the default duration and the default easing.
	 *		Object : If the value is an object, then effect, delay, duration, and easing 
	 *			properties may be provided. If the effect property contains the name of a jQuery method, 
	 *			then that method will be used; otherwise it is assumed to be the name of a jQuery UI effect. 
	 *			When using a jQuery UI effect that supports additional settings, you may include those settings in the object 
	 *			and they will be passed to the effect. If duration or easing is omitted, then the default values will be used. 
	 *			If effect is omitted, then "fadeOut" will be used. If delay is omitted, then no delay is used.
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Tabs
	 */
	public function setShow($val = 'null'){
		$this->addParams('show', $val);
		return $this;
	}

	
	public function addSection($title, $content){
		$this->sections[$title] = $content;
	}
	
	public function onDisplay() {
		if(empty($this->sections))
			return false;
		
		$this->view->sections = $this->sections;
		parent::onDisplay();
	}
	
}

?>

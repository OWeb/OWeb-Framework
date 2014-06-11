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
 * Allows the creation of a jqueryui Accordion.
 * Demo : http://jqueryui.com/accordion/
 * Doc : http://api.jqueryui.com/accordion/
 *
 * @author De Cramer Oliver
 */
class Accordion extends JQueryGen{

	private $sections = array();
	
	public function init() {
		parent::init();
		
		$this->setFunction('accordion');
		$this->addOption('active', 0);
		$this->addOption('animate', '');
		$this->addOption('collapsible', 'false');
		$this->addOption('disabled', 'false');
		$this->addOption('event', '"click"');
		$this->addOption('header', '"> li > :first-child,> :not(li):even"');
		$this->addOption('heightStyle', '"auto"');
		$this->addOption('icons', '"header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s" ');
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
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setActive($active = 0){
		$this->addParams('active', $active);
		return $this;
	}

	/**
	 * If and how to animate changing panels.
	 * 
	 * @param  Boolean or Number or String or Object 
	 *		Boolean: A value of false will disable animations.
	 *		Number: Duration in milliseconds with default easing.
	 *		String: Name of easing to use with default duration.
	 *		Object: Animation settings with easing and duration properties.
	 *			Can also contain a down property with any of the above options.
	 *			"Down" animations occur when the panel being activated has a lower index than the currently active panel.
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setAnimate($val = '{}'){
		$this->addParams('animate', $val);
		return $this;
	}
	
	/**
	 * Whether all the sections can be closed at once. Allows collapsing the active section
	 * 
	 * @param Boolean $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setCollapsible($val = 'false'){
		$this->addParams('collapsible', $val);
		return $this;
	}
	
	/**
	 * Disables the accordion if set to true.
	 * 
	 * @param Boolean $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
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
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setEvent($val = 'click'){
		$this->addParams('event', $val);
		return $this;
	}
	
	/**
	 * Selector for the header element, applied via .find() on the main accordion element.
	 * Content panels must be the sibling immediately after their associated headers.
	 * 
	 * @param String $val
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setHeader($val = '"> li > :first-child,> :not(li):even"'){
		$this->addParams('header', $val);
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
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setHeightStyle($val = '"auto"'){
		$this->addParams('heightStyle', $val);
		return $this;
	}
	
	/**
	 * cons to use for headers, matching an icon provided by the jQuery UI CSS Framework. 
	 * Set to false to have no icons displayed.
	 * 
	 * @param String $val
	 * 		header (string, default: "ui-icon-triangle-1-e")
	 *		activeHeader (string, default: "ui-icon-triangle-1-s")
	 * 
	 * @return \Controller\OWeb\widgets\jquery_ui\Accordion
	 */
	public function setIcons($val = '{ "header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s" }'){
		$this->addParams('icons', $val);
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

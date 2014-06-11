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

namespace Controller\demo\jquery\ui;

/**
 * Description of Accordion
 *
 * @author De Cramer Oliver
 */
class Tabs extends \OWeb\types\Controller{
	
	private $form;
	private $tabs;
	
	public function init() {
		$this->action_mode = self::ACTION_GET;
		
		//Applying special template
		$this->applyTemplateController(new \Controller\demo\Template());
		$this->addAction('refresh', 'doRefresh');
		
		//Creating the form
		$this->form = new \Controller\demo\jquery\ui\TabsForm();
		$this->form->init();		
		$this->form->loadParams();
		
		//Creating the accordion
		$this->tabs = new \Controller\OWeb\widgets\jquery_ui\Tabs();
		$this->tabs->init();
	}
	
	/**
	 * If form returned an action
	 */
	public function doRefresh(){
		//Validating elements.  Should be already done but let's say on the safe side
		$this->form->validateElements();
		
		//If valid apply values to the accordion
		if($this->form->isValid()){
			foreach($this->form->getElements() as $element){
				$this->tabs->addParams($element->getName(), $element->getVal());
			}
		}
	}

	public function onDisplay() {
		$this->view->form = $this->form;
		$this->view->tabs = $this->tabs;
	}
}

?>

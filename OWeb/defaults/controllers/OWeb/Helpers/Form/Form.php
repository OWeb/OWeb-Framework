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

namespace Controller\OWeb\Helpers\Form;

/**
 * Description of Form
 *
 * @author De Cramer Oliver
 */
abstract class Form extends \Controller\OWeb\Helpers\HtmlElement{
	
	/**
	 * The id of the form, it will be applied to the sub elements as well. 
	 * @var String
	 */
	private $_formId;
	
	/**
	 * List of all elements that needs to be displayed
	 * @var Array(\OWeb\types\Controller) 
	 */
	private $_displayElements = array();
	/**
	 * List of all Form elements. 
	 * @var Array(Controller\OWeb\Helpers\Form\Elements\Elements)
	 */
	private $_elements = array();
	
	/**
	 * Is all the elements of this Form valid
	 * @var Boolean
	 */
	private $_isValid = null;
	/**
	 * Name of the action to be sent.
	 * @var String
	 */
	private $_action = null;
	
	public function init() {
		parent::init();
		$this->registerElements();
		$this->addHtmlClass('OWebForm');
	}
	
	/**
	 * Will run a check on all elements to check if their values are correct. 
	 * Will also parse the values of the elements to whom parser validators are applied.
	 */
	public function validateElements(){
		$this->_isValid = true;
		$values = $this->getParams();
		
		$this->recValidateElements($this->_elements, $values);
	}
	
	private function recValidateElements($elements, $values, $collection = false){
		
		$i = 0;
		
		foreach ($elements as $element) {
			if(isset($values[$element->getName()]) && !is_array($values[$element->getName()]))
				$element->setVal($values[$element->getName()]);
			
			$valid = $element->validate();			
			$this->_isValid = $this->_isValid && $valid;
			
			echo "For : ".$element->getName();
			print_r($values[$element->getName()]);
			echo "\n\n";
			
			if($element instanceof Elements\InterfaceElementHolder){
				$isCollection = $element instanceof Elements\Collection;
				
				$newVals = array();
				if(isset($values[$element->getName()])&& is_array($values[$element->getName()]))
					$newVals = $values[$element->getName()];
				
				
				if($collection && isset($newVals[$i]))
					$newVals = $newVals[$i];
						
				
				$this->recValidateElements($element->getAllElements(), $newVals, $isCollection);
			}
			$i++;
		}
	}
	
	/**
	 * Add a display element to the Form. If this element is also a Form Element
	 * then it will automatically be added to the list of form elements.
	 * 
	 * @param \OWeb\types\Controller $element The element to add
	 */
	public function addDisplayElement(\OWeb\types\Controller $element){
		$this->_displayElements[] = $element;
		if($element instanceof \Controller\OWeb\Helpers\Form\Elements\AbstractElement){
			$this->_elements[] = $element;
		}
		//$element->init();
	}
	
	/**
	 * Adds an Form Element to the Form without adding it to the Display List.
	 * 
	 * @param \Controller\OWeb\Helpers\Form\Elements\AbstractElement $element
	 */
	public function addElement(\Controller\OWeb\Helpers\Form\Elements\AbstractElement $element){
		$this->_elements[] = $element;
		//$element->init();
	}
	
	/**
	 * You will register all your elements to the form at this stage of the code.
	 */
	abstract protected function registerElements();

	/**
	 * Is all the elements in this Form has valid values? 
	 * If all elements haven't been calidated yet
	 * 
	 * @return Boolean
	 */
	public function isValid(){
		if($this->_isValid == null)
			$this->validateElements();
		return $this->_isValid;
	}
	
	/**
	 * The id of the form, it will be applied to the sub elements as well.
	 * 
	 * @param String $id
	 */
	public function setFormId($id){
		$this->_action = $id;
	}
	
	/**
	 * Set the action of the form. 
	 * It will generate a hidden element with name action and the desired action name
	 * 
	 * @param type $actionName
	 */
	public function setAction($actionName){
		$this->_action = new Elements\Hidden();
		$this->_action->setName('action');
		$this->_action->setVal($actionName);
		$this->_action->init();
	}
	
	public function onDisplay() {
		$this->view->htmlIdentifier = $this->getIdentifier();
		$this->view->elements = $this->_displayElements;
		$this->view->actionMode = $this->action_mode;
		$this->view->action = $this->_action;
	}
	
	/**
	 * 
	 * @return Array(Controller\OWeb\Helpers\Form\Elements\Elements)
	 */
	public function getElements(){
		return $this->_elements;
	}
}

?>

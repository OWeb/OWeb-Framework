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
namespace Controller\OWeb;

/**
 * Description of Error
 *
 * @author De Cramer Oliver
 */
class Error extends \OWeb\types\Controller{
	
	public function init(){
		$this->InitLanguageFile();
        if($this->isPrimaryController()){
            $this->applyTemplateController('Controller\OWeb\SimpleContent');
        }
	}
	
	public	function onDisplay() {
		$this->view->title = $this->getParam("Title");
		$this->view->desc = $this->getParam("Description");
		$this->view->img = $this->getParam("img");
	}
}

?>

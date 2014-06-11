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
use OWeb\types\UserException;

/**
 * Description of Exception
 *
 * @author De Cramer Oliver
 */
class Exception extends \OWeb\types\Controller{
	
	public function init(){
		$this->InitLanguageFile();
        $this->applyTemplateController('Controller\OWeb\SimpleContent');
	}
	
	
	public	function onDisplay() {
		$exception = $this->getParam("exception");
        $this->view->userException = array();
		$this->view->messages = array();
		
		if($exception instanceof \Exception){
			$this->view->messages[]=$exception;
			while ($exception->getPrevious() != null){
				$exception = $exception->getPrevious();
				$this->view->messages[]=$exception;
                if($exception instanceof UserException ){
                    $this->view->userException[] = $exception;
                }
			}
		}
        if(empty($this->view->userException)){
            $ex = new UserException($this->l('title_unknown'));
            $ex->setUserDescription($this->l('desc_unknown'));
            $this->view->userException[] = $ex;
        }
	}

}

?>

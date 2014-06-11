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

namespace Controller\demo\forms;

use \Controller\OWeb\Helpers\Form\Elements\FieldSet;
use \Controller\OWeb\Helpers\Form\Elements\Text;
use \Controller\OWeb\Helpers\Form\Elements\Collection;

/**
 * Description of DynamicFor
 *
 * @author De Cramer Oliver
 */
class DynamicForm extends \Controller\OWeb\Helpers\Form\Form{
	
	
	protected function registerElements() {
		$this->action_mode = self::ACTION_GET;
		
		$this->setAction('refresh');
		
		$text = new Text();
		$text->init();
		$text->setTitle("Movie name");
		$text->setName("movieName");
		$this->addDisplayElement($text);
		
		$fieldset = new FieldSet();
		$fieldset->init();
		$fieldset->setTitle("Actor");
		$fieldset->setName("actor");
		
		$text = new Text();
		$text->init();
		$text->setTitle("Name");
		$text->setName("name");
		$fieldset->add($text);
				
		
		$text = new Text();
		$text->init();
		$text->setTitle("FDfsf");
		$text->setName("fof");
		$fieldset->add($text);
		
		$collection = new Collection();
		$collection->init();
		$collection->setTitle("Actors");
		$collection->setName('actors');
		$collection->add($fieldset);

		$this->addDisplayElement($collection);
		
		$submit = new \Controller\OWeb\Helpers\Form\Elements\Submit();
		$submit->init();
		$submit->setVal("Refresh");
		$this->addDisplayElement($submit);
	}
}

?>

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

$this->addHeader('Helpers.Form.css',\OWeb\manage\Headers::css); 

?>
<div <?=$this->htmlIdentifier?>>

	<form <?=$this->htmlIdentifier?> method="<?php echo $this->actionMode == OWeb\types\Controller::ACTION_GET ? 'get' : 'post' ?>">

	<?php

	if(	$this->action != null)
			$this->action->display();

	$i = 0;
	foreach ($this->elements as $element){
		if($this->htmlIdentifier->getHtmlId() != null)
			$element->setFormId($this->htmlIdentifier->getHtmlId(), $i);

		$element->addHtmlClass('OWebForm_input');
		$element->addHtmlClass('OWebForm_input_'.$i%2);
		$element->display();
		$i++;
	}
	?>

	</form>

</div>

<div class="ColloneClean"></div>
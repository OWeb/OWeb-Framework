<?php

/**
 * @author       Oliver de Cramer (oliverde8 at gmail.com)
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

/**
 * Description of Elements
 *
 * @author De Cramer Oliver
 */
$id = clone $this->htmlIdentifier;
$id->addHtmlClass('OWebForm_input_def');

?>

<div class="uk-width-4-10">
    <label <?= $id ?> for="<?= $this->name ?>"><?= $this->title ?></label>
</div>

<div class="uk-width-5-10">
    <input <?= $id ?> type="<?= $this->type ?>" name="<?= $this->name ?>" value="<?= $this->val ?>">
</div>
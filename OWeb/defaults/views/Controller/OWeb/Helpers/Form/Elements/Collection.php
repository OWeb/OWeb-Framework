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

    <h2 <?= $id ?> for="<?= $this->name ?>"><?= $this->title ?></h2>
<?php

$i = 0;
foreach ($this->items as $item) {
    if (!empty($this->name)) {
        $oldName = $item->getName();
        $item->setName($this->name . '[' . $oldName . '][' . $i . ']');
    }
    $item->display();
    if (!empty($this->name)) {
        $item->setName($oldName);
    }
    $i++;
}



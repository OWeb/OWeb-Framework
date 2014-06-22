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

namespace Controller\OWeb\widgets\uikit;


use OWeb\types\Controller;

class Switcher extends Controller{

    private $id;

    private $sections = array();


    public function init()
    {
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function onDisplay()
    {
        if($this->id == null ){
            $this->id = (String)(new \OWeb\utils\IdGenerator ());
            $displayId = 'id="'.$this->id.'"';
        }else{
            $displayId = 'id="'.$this->id.'"';
        }

        $this->view->displayId = $displayId;
        $this->view->id = $this->id;
        $this->view->sections = $this->sections;
    }

    public function addSection($title, $content)
    {
        $this->sections[$title] = $content;
    }
}
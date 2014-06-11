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

namespace Controller\OWeb\widgets\jquery\plugins;

use OWeb\types\Controller;

class GalleryView extends Controller{

    public function init(){
    }

    public function onDisplay(){

        $this->view->path = $this->getParam('path');
        $this->view->panel_width = $this->getParam("panel_width") == null ? 720 : $this->getParam("panel_width");
        $this->view->panel_height = $this->getParam("panel_height") == null ? 600 : $this->getParam("panel_height");
        $this->view->panel_scale = $this->getParam("panel_scale") == null ? 'fit' : $this->getParam("panel_scale");
        $this->view->pan_images = $this->getParam("panel_scale") == null ? 'true' : $this->getParam("pan_images");
        $this->view->show_infobar = $this->getParam("show_infobar") == null ? 'true' : $this->getParam("show_infobar");
        $this->view->infobar_opacity = $this->getParam("infobar_opacity") == null ? 1 : $this->getParam("infobar_opacity");


    }
}
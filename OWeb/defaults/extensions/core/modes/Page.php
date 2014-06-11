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
namespace Extension\core\modes;

/**
 * Description of Page
 *
 * @author De Cramer Oliver
 */
class Page extends \OWeb\types\Extension implements ModeInterface{

	public function init(){
		
	}

    public function initMode(){
        //On va charger le controleur.
        $man_ctr = \OWeb\manage\Controller::getInstance();

        //On va voir quel page on doit charger
        $get = \OWeb\OWeb::getInstance()->get_get();

        if (isset($get['page'])) {
            $ctr = $get['page'];
        } else {
            $ctr = OWEB_DEFAULT_PAGE;
        }

        try {
            $ctr = str_replace("\\\\", "\\", $ctr);
            $ctr = str_replace(".", "\\", $ctr);

            $ctr = $man_ctr->loadController('Page\\' . $ctr);
            $ctr->loadParams();
			
        } catch (\Exception $ex) {
            $ctr = $man_ctr->loadController('Page\OWeb\errors\http\NotFound');
            $ctr->loadParams();
        }
    }

    public function display()
    {
        new \OWeb\manage\Template();
    }
}

?>

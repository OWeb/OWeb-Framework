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

namespace Extension\core\modes;

/**
 * Description of API
 *
 * @author De Cramer Oliver
 */
class API extends \OWeb\types\Extension implements ModeInterface
{

    private $extension;

    public function initMode()
    {

        $ext_man = \OWeb\manage\Extensions::getInstance();

        //On va voir quel extension on doit charger
        $get = \OWeb\OWeb::getInstance()->get_get();

        if (isset($get['ext'])) {
            $ext = $get['ext'];
        } else {

        }

        try {
            $ext = str_replace("\\\\", "\\", $ext);
            $ext = str_replace(".", "\\", $ext);

            $this->extension = $ext_man->getExtension($ext);
            $this->extension->loadParams();
        } catch (\Exception $ex) {

        }
    }

    public function display()
    {

        $result = '';

        $get = \OWeb\OWeb::getInstance()->get_get();

        if (isset($get['action'])) {
            $r = $this->extension->doAction($get['action']);
            if ($r != null) {
                if (empty($result))
                    $result = $r;
                elseif (is_array($result)) {
                    $result[] = $r;
                } else {
                    $r2       = $result;
                    $result   = array();
                    $result[] = $r2;
                    $result[] = $r;
                }
            }
        }

        $i = 1;
        while (isset($get['action_' . $i])) {
            $r = $this->extension->doAction($get['action_' . $i]);
            if ($r != null) {
                if (empty($result))
                    $result = $r;
                elseif (is_array($result)) {
                    $result[] = $r;
                } else {
                    $r2       = $result;
                    $result   = array();
                    $result[] = $r2;
                    $result[] = $r;
                }
            }
            $i++;
        }

        echo json_encode($result);
    }

    public function init()
    {

    }
}

?>

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

namespace OWeb\abs\displayMode\module\Extension;

abstract class AbstractPageDisplayHandler extends \OWeb\types\extension\Extension
{
    const MODE_PAGE = 0;
    const MODE_CONTROLLER = 1;
    const MODE_PAGE_NO_TEMPLATE = 2;
    const MODE_CONTROLLER_NO_TEMPLATE = 3;


    protected $_mode;

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        $this->_mode = $mode;
    }


    /**
     * Called to display
     *
     * @return mixed
     */
    public abstract function display();

} 
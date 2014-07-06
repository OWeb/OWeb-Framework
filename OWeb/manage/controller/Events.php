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

namespace OWeb\manage\controller;


use OWeb\types\Controller;

interface Events
{


    const EVENT_preInitController = 'OWeb_Controller_preInitController';
    const EVENT_postInitController = 'OWeb_Controller_postInitController';
    const EVENT_preActionDispatch = 'OWeb_Controller_preActionDispatch';
    const EVENT_postActionDispatch = 'OWeb_Controller_postActionDispatch';

    /**
     * Called before the main controller is initialized
     *
     * @param Controller $controller
     *
     * @return void
     */
    public function OWeb_Controller_preInitController($controller);

    /**
     * Called after the main controller is initialized
     *
     * @param Controller $controller
     *
     * @return void
     */
    public function OWeb_Controller_postInitController($controller);


    /**
     * Called before the actions are distributed to the main controller
     *
     * @param Controller $controller
     *
     * @return void
     */
    public function OWeb_Controller_preActionDispatch($controller);

    /**
     * Called after the actions are distributed to the main controller
     *
     * @param Controller $controller
     *
     * @return void
     */
    public function OWeb_Controller_postActionDispatch($controller);
} 
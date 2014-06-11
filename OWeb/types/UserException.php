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

namespace OWeb\types;


use OWeb\Exception;

class UserException extends Exception{

    private $user_image;
    private $user_description;

    function __construct($message = "", $code = 0, \Exception $prevous = null)
    {
        parent::__construct($message, $code, $prevous);
    }

    /**
     * @param string $user_description
     */
    public function setUserDescription($user_description)
    {
        $this->user_description = $user_description;
    }

    /**
     * @return string
     */
    public function getUserDescription()
    {
        return $this->user_description;
    }

    /**
     * @param string $user_image
     */
    public function setUserImage($user_image)
    {
        $this->user_image = $user_image;
    }

    /**
     * @return string
     */
    public function getUserImage()
    {
        return $this->user_image;
    }

}
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

namespace Model\programs;


class Revision {

    private $revision_name;
    private $date;
    private $description;
    private $downloadLink;
    private $isBeta;

    function __construct($revision_name, $date, $description, $downloadLink, $isBeta)
    {
        $this->date = $date;
        $this->description = $description;
        $this->downloadLink = $downloadLink;
        $this->isBeta = $isBeta;
        $this->revision_name = $revision_name;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $downloadLink
     */
    public function setDownloadLink($downloadLink)
    {
        $this->downloadLink = $downloadLink;
    }

    /**
     * @return mixed
     */
    public function getDownloadLink()
    {
        return $this->downloadLink;
    }

    /**
     * @param mixed $isBeta
     */
    public function setIsBeta($isBeta)
    {
        $this->isBeta = $isBeta;
    }

    /**
     * @return mixed
     */
    public function IsBeta()
    {
        return $this->isBeta;
    }

    /**
     * @param mixed $revision_name
     */
    public function setRevisionName($revision_name)
    {
        $this->revision_name = $revision_name;
    }

    /**
     * @return mixed
     */
    public function getRevisionName()
    {
        return $this->revision_name;
    }



}
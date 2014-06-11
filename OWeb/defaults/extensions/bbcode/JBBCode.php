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
namespace Extension\bbcode;
/**
 * Description of JBBCode
 *
 * @author oliverde8
 */
class JBBCode extends \OWeb\types\Extension{
	
	private $parser = null;
	
	protected function init() {
		$this->addAlias('getBB', 'getParser');
	}
	
	protected function getNewParser(){
		$parser = new JBBCode\Parser();
		if($this->parser == null)
			$this->parser = $parser;
		
		$parser->addCodeDefinition(new \Extension\bbcode\JBBCode\myCodes\Titles());
		$parser->addCodeDefinition(new \Extension\bbcode\JBBCode\myCodes\Lis());
		$parser->addCodeDefinition(new \Extension\bbcode\JBBCode\myCodes\ListItem());
		
		return $parser;
	}
	
	public function getParser(){
		return $this->parser == null ? $this->getNewParser() : $this->parser;
	}
}

?>

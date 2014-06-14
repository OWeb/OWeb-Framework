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
namespace Model\downloads;


/**
 * Description of Download
 *
 * @author oliverde8
 */
class Download {
	
	private $ext_connection;
	
	private $id;
	private $name;
	private $url;
	private $isLocal;
	private $nbDownload;
	
	function __construct($id) {
		$this->ext_connection = \OWeb\manage\Extensions::getInstance()->getExtension('db\Connection');
		
		$connection = $this->ext_connection->get_Connection();
		$prefix = $this->ext_connection->get_prefix();
		
		$sql = $connection->prepare("SELECT * 
						FROM " . $prefix . "downloads
						WHERE id_download = :id");
		
		if($sql->execute(array(':id'=>$id))){
			if($result = $sql->fetchObject()){
				$this->id = $id;
				$this->name = $result->name;
				$this->url = $result->url;
				$this->isLocal = $result->isLocal;
				$this->nbDownload = $result->nbDownload;
			}else{
				throw new \Model\downloads\exception\DownloadCantBeFind("Download with id : $id doesen't exist SQL ERROR2");
			}
		}else{
			throw new \Model\downloads\exception\DownloadCantBeFind("Couldn't get Download with id : $id . SQL ERROR2");
		}
		
	}
	
	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function getUrl() {
		return $this->url;
	}

	public function getIsLocal() {
		return $this->isLocal;
	}

	public function getNbDownload() {
		return $this->nbDownload;
	}

	public function newDownload(){
		$connection = $this->ext_connection->get_Connection();
		$prefix = $this->ext_connection->get_prefix();
		
		$sql = $connection->prepare("UPDATE " . $prefix . "downloads
						SET nbDownload = nbDownload+1
						WHERE id_download = :id");
		$sql->execute(array(':id'=>$this->id));
	}
	
}

?>

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
namespace Controller\downloads;

/**
 * Description of download
 *
 * @author oliverde8
 */
class Download extends \OWeb\types\Controller{

	private $download;
	
	public function init() {
		$this->addAction("dwld", "dwld");
	}
	
	private function min(){
		if(is_numeric($this->getParam("id"))){
			$this->download = new \Model\downloads\Download($this->getParam("id"));
		}else{
			throw new \Model\downloads\exception\DownloadCantBeFind("No Download ID given");
		}
	}
	
	public function dwld(){
		$this->min();
		if(is_numeric($this->getParam("id"))){
			$this->download->newDownload();
			if($this->download->getIsLocal()){
				
				$url = (OWEB_DIR_DATA."/downloads/".$this->download->getUrl());
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'. basename($url) .'";');
				readfile($url);
			}else{
				$url = (OWEB_DIR_DATA."/downloads/".$this->download->getUrl());
				header("Content-Disposition: attachment; filename=" . basename($url));    
				header("Content-Type: application/force-download");
				header("Content-Type: application/octet-stream");
				header("Content-Type: application/download");
				header("Content-Description: File Transfer");             
				header("Content-Length: " . filesize($url));
				flush(); // this doesn't really matter.

				$fp = fopen($url, "r"); 
				while (!feof($fp))
				{
					echo fread($fp, 65536); 
					flush(); // this is essential for large downloads
				}  
				fclose($fp); 
			}
		}else{
			throw new \Model\downloads\exception\DownloadCantBeFind("No Download ID given");
		}
		
		
		
	}

	public function onDisplay() {
		$this->min();
		$this->view->dwld = $this->download;
	}
}

?>

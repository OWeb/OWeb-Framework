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
namespace OWeb\manage;
/**
 * Will load language files
 * OWeb core will ask it to load the main setting file. 
 * 
 * The settings manager seperates settings of different 
 * Classes so that you can easily have acces to a setting
 * It can load multiple different files, for different Classes
 * 
 * @author oliver
 */
class Languages extends \OWeb\utils\Singleton{

	const user_lang = 0;
	const def_lang = 1;
	
	private $language_files = array();
	
    private $file_languages;
	
	private $class_languages;
	
	private static $default_language;
	
	private $c_lang;
	

	/**
	 * The Setting array for you, in the default file or the file you asked it to check
	 * 
	 * @param String $asker The namen or Object of the one who asks for the settings
	 * @return \OWeb\types\Language()
	 */
    public function getLanguage($asker, $explodedName = null){
		
		if(is_string($asker))
			$name = $asker;
		else if(is_object($asker))
			$name = get_class($asker);
		
        if(!isset($this->class_languages[$name])){   
			$settings = \OWeb\manage\Settings::getInstance()->getSetting($this);

			self::$default_language = $settings['default_language'];
			//ToDO : Remove
			\OWeb\types\Language::$default_language = self::$default_language;
			
			
			$ext = \OWeb\manage\Extensions::getInstance()->tryGetExtension('user\connection\TypeConnection');
			
			if($explodedName == null){
				if($asker instanceof \OWeb\types\NamedClass){
					$cname = \OWeb\types\NamedClass::get_exploded_nameOf($name);
					$path = '/' . $cname[0];
					$path .= str_replace(".php", "", \OWeb\types\NamedClass::get_relative_pathOf($name));
				}
				else{
					$explodedName = explode('\\', $name);
					for($i = 0; $i < sizeof($explodedName); $i++){
						$path .= '/'.$explodedName[$i];
					}
				}
			}else{
				for($i = 0; $i < sizeof($explodedName); $i++){
					$path .= '/'.$explodedName[$i];
				}
			}
			
			$langObj = new \OWeb\types\Language();
			$this->class_languages[$name] = $langObj;
			
			$langObj->addDefLanguageText(
					$this->loadFile(OWEB_DEFAULT_LANG_DIR . $path . '/' . self::$default_language . '.php', self::$default_language));
			$langObj->addDefLanguageText(
					$this->loadFile(OWEB_DEFAULT_LANG_DIR . '/' . self::$default_language . '.php', self::$default_language));
			
			if ($ext != null && $ext->getlang() != self::$default_language) {
				
				$this->c_lang = $ext->getlang();
				\OWeb\types\Language::$user_language = $this->c_lang;
				
				$langObj->addUserLanguageText(
						$this->loadFile(OWEB_DEFAULT_LANG_DIR . $path . '/' . $this->c_lang . '.php', $this->c_lang));
				$langObj->addUserLanguageText(
						$this->loadFile(OWEB_DEFAULT_LANG_DIR . '/' . $this->c_lang . '.php', $this->c_lang));
			}
			else{
				$this->c_lang = self::$default_language;
				\OWeb\types\Language::$user_language = $this->c_lang;
			}		
		}
		
        return $this->class_languages[$name];
    }

    /**
     * Recoveres the value of the text for that class(object)
     *
     * @param mixed $asker The object or class that asks the Information
     * @param $asked The name of the information to get
     * @return mixed If information available string if not false
     */
    public function getLanguageText($asker, $asked){
		if(is_string($asker))
			$name = $asker;
		else if(is_object($asker))
			$name = get_class($asker);
		
		$l = $this->getLanguage($name);
		
		return $l->get($asked);
	}

    /**
     * Loads a different configuration file on top of the existing one
     * @param $file
     * @throws exceptions\Settings
     */
    private function loadFile($file){
		if(isset($this->file_languages[$file]) ){
			return $this->file_languages[$file];
		}else{
			$f = array();
			if(file_exists($file))
				try{
					require_once $file;
					$f = $_L;
					if($f == null)
						$f = array();
				}catch(\Exception $ex){
					throw new \OWeb\manage\exceptions\Settings("Failed to load Lanuage file : '$file'", 0, $ex);
				}
			$this->file_languages[$file] = $f;
			return $f;
		}
    }
	
	public function getDefLang(){
		return self::$default_language;
	}
}
?>

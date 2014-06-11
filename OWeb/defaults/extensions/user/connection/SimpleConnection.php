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
namespace Extension\user\connection;

/**
 * Description of SimpleConnection
 *
 * @author De Cramer Oliver
 */
class SimpleConnection extends TypeConnection{
	
	protected $login;
	protected $mail;

	public function __construct() {
		$this->addDependance("db\\absConnect");
	}
	
	protected function connect_cookie($login, $pwd) {
		return $this->checkLogin($login, $pwd);
	}

	protected function connect_new($login, $pwd) {
		if($this->checkLogin($login, md5($pwd))){
			$this->createCookies($login, $pwd);
			return true;
		}
		return false;
	}
	
	protected function createCookies($login, $pwd){
		setcookie($this->set_SessionName."_auth[login]", $login, time()+$this->set_CookieTime);
		setcookie($this->set_SessionName."_auth[pwd]", md5($pwd), time()+$this->set_CookieTime);
	}

	protected function connect_session($login, $pwd) {
		return $this->checkLogin($login, $pwd);
	}
	
	protected function checkLogin($login, $pwd){
		$connection = $this->ext_db_absConnect->get_Connection();
		$prefix = $this->ext_db_absConnect->get_Prefix();
		
		$sql = $connection->prepare("SELECT * FROM ".$prefix."users WHERE Login = :login");
		if($sql->execute(array(':login'=>$login))){
			if ($sql->rowCount()>0){
                $obj = $sql->fetchObject();
                if(md5($obj->pwd) == $pwd){
                    $this->login = $obj->Login;
                    $this->lang = $obj->lang;
                    $f = 'e-mail';
                    $this->mail = $obj->$f;
                    return true;
                }
			}
		}
		return false;
	}

    protected function getMailPwd($mail){
        $connection = $this->ext_db_absConnect->get_Connection();
        $prefix = $this->ext_db_absConnect->get_Prefix();

        $sql = $connection->prepare("SELECT * FROM ".$prefix."users WHERE `e-mail` = :email");

        if($sql->execute(array(':email'=>$mail))){
            if ($sql->rowCount()>0){
                $obj = $sql->fetchObject();
                $this->login = $obj->Login;
                $this->lang = $obj->lang;
                return $obj->pwd;
            }
        }else{
            print_r($sql->errorInfo());
        }
		return false;
	}
	
	protected function getLangConnected($get, $cookie){
		if($this->getConnectionType() == self::TYPE_NEW && $this->lang != ""){
			//If there is a language defined in the cookie or the get we will ignore it
			setcookie($this->set_SessionName."_auth[lang]", $this->lang);
			
		}else if(isset($get['lang']) 
				&& (strlen($get['lang'])==2 || strlen($get['lang'])==3) 
				&& $this->lang != $get['lang']){

			$this->lang = $get['lang'];
			//Updating in Database 
			$this->updateLangDB();
			
			//Updating in Cookies
			setcookie($this->set_SessionName."_auth[lang]", $this->lang);
			
		}else if(isset($cookies[$this->set_SessionName.'_auth']['lang'])
				&& $cookies[$this->set_SessionName.'_auth']['lang'] != $this->lang){
			$this->lang = $cookies[$this->set_SessionName.'_auth']['lang'];
			//Well updating Database again
			$this->updateLangDB();
		}

	}
	
	private function updateLangDB(){
		$connection = $this->ext_db_absConnect->get_Connection();
		$prefix = $this->ext_db_absConnect->get_Prefix();

		$statement = $connection->prepare('UPDATE '.$prefix.'users SET lang = :lang WHERE Login = \''.$this->login."'");
		$statement->execute(array(':lang' => $this->lang));
	}

	public function getLogin() {
		if($this->isConnected())
			return $this->login;
		else
			return "";
	}

    public function getEmail(){
        if($this->isConnected())
            return $this->mail;
        else
            return "";
    }
}

?>

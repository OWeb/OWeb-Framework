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
 * Description of MozillaPersona
 *
 * @author oliverde8
 */
class MozillaPersona extends SimpleConnection{

	protected function connect_new($login, $pwd) {				
		$persona = new \Extension\user\connection\MozillaPersona\Persona();

		$result = $persona->verifyAssertion($pwd);

		if ($result->status === 'okay') {

            $this->email = $result->email;

            //We connected need to check if first connection or if user already connected
            $NewPwd = $this->getMailPwd($this->email);

            if($NewPwd){
                //Not first connection let's rock and roll the connection
                $this->createCookies($this->login, $NewPwd);
                return true;
            }else{
				echo "NEW";
                //Oups new connection this is more complicated. The user doesen't yet have a Login.
                // @TODO better login handling.
                //We will just use mail as login, user might change it later.
                $connection = $this->ext_db_absConnect->get_Connection();
                $prefix = $this->ext_db_absConnect->get_Prefix();


                $sql = $connection->prepare("INSERT INTO ".$prefix."users(Login, pwd, `e-mail`) VALUES(:login, :pwd, :mail)");
                $sql->execute(array(':login'=>$this->email, ':mail'=>$this->email, ':pwd'=>$this->createkey(30)));
				print_r($sql->errorInfo());
            }

			return true;
		} else {
			return false;
		}
	}

}

?>

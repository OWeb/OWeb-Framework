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
namespace OWeb\manage;
use OWeb\types\Extension;

/**
 * Will manages the Extensions of OWeb.
 *
 * The Extension managers is family dependent.
 * Meaning that if a plugin extends another one it can't be used at the same time as the other one.
 * This allows you to replace a plugin without changing the code that used the all version. As the
 * Extension manager
 *
 * @author De Cramer Oliver
 */
class Extensions extends \OWeb\utils\Singleton
{


    private $extensions = array();
    private $obj_extension = array();

    private $notInit = true;

    function __construct()
    {
        \OWeb\manage\Events::getInstance()->registerEvent('Init@OWeb', $this, 'init_extensions');
    }

    /**
     * Will just try to get an extension, if it is impossible it will return null
     *
     * @param String $ext The name of the extension
     *
     * @return Extension The exception if loaded or could be load null if not.
     */
    public function tryGetExtension($ext)
    {
        try {
            return $this->getExtension($ext);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * If the extensions is loaded will just return it,
     * if not will try to load it and the return it
     *
     * @param type $ext The name of the extension you want to get
     *
     * @return The extension you asked for
     * @throws \OWeb\manage\exceptions\Extension If the extension isn't already loaded and can't be loaded.
     */
    public function getExtension($ext)
    {
        $name = "Extension\\" . $ext;

        if (isset($this->extensions[$name])) {
            return $this->extensions[$name];
        }

        try {
            $extension = $this->loadExtension($name);
            if (!$this->notInit) {
                $extension->OWeb_Init();
            }

        } catch (\Exception $exception) {
            throw new \OWeb\manage\exceptions\Extension("YOu can't get this extension. It hasn't been already loaded and can't be loaded now", 0, $exception);
        }

        return $extension;

    }

    /**
     * Will load the extension if it hasn't already been load and if it can find it.
     *
     * @param type $ClassName The name of the extension to be loaded.
     *
     * @return \OWeb\types\Extension The extension that has been loaded
     * @throws \OWeb\manage\exceptions\Extension
     */
    protected function loadExtension($ClassName)
    {

        try {
            $reflectionClass = new  \ReflectionClass($ClassName);
            if(!$reflectionClass->isInstantiable())
                throw new \OWeb\manage\exceptions\Extension("The Extension is still abstract");

            $extension = new $ClassName();

            if ($extension instanceof \OWeb\types\Extension) {
                $this->registerExtension($extension, $ClassName);

                return $extension;
            } else
                throw new \OWeb\manage\exceptions\Extension("The Extension isn't an instance of : \OWeb\\types\Extension");

        } catch (\Exception $ex) {
            throw new \OWeb\manage\exceptions\Extension("The extension couldn't be loaded.", 0, $ex);
        }
    }

    /**
     * Register the extension to the Extension manager.
     * It is here that we will check all the parents of the extension to Register for every parents it has.
     *
     * @param String    $extension The Extension Object
     * @param Extension $name      THe name of the Extension
     *
     * @throws \OWeb\Exception        If the extension has been already loaded.
     */
    protected function registerExtension($extension, $name)
    {

        $subExtensions = array();

        $subExtensions[$name] = $extension;
        $parent               = get_parent_class($extension);

        /*foreach($this->extensions as $name => $ext){
                echo ".".$name."------------------";
        }*/

        while ($parent != "" && $parent != "OWeb\\types\\Extension") {
            if (isset($this->extensions[$parent])) {
                throw new \OWeb\Exception("The plugin '$name' has already been registered as : " . get_class(
                        $this->extensions[$parent]
                    ));
            }
            $subExtensions[$parent] = $extension;
            $parent                 = get_parent_class($parent);
        }
        $this->obj_extension[$name] = $extension;
        $this->extensions           = array_merge($this->extensions, $subExtensions);
    }

    /**
     * Will initialize all extensions when Oweb has finished Initializing itself
     */
    public function init_extensions()
    {
        \OWeb\manage\Events::getInstance()->sendEvent('InitPrep@OWeb\manage\Etensions');

        foreach ($this->obj_extension as $extension) {
            $extension->OWeb_Init();
        }

        $this->notInit = false;
        \OWeb\manage\Events::getInstance()->sendEvent('Init@OWeb\manage\Etensions');
    }
}

?>

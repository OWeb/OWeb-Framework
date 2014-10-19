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

namespace OWeb\db\module\Model;
use OWeb\db\module\Model\DbModel\Attribute;
use OWeb\db\module\Model\DbModel\External;
use OWeb\db\module\Model\Query\Query;
use OWeb\db\module\Model\Query\Exception as QueryGenerationException;
use OWeb\db\module\Model\DbModel\Exception as ModelException;

/**
 * Handles querties to get Data of a Model from Database
 *
 * @package OWeb\db\module\Model
 */
abstract class DbResource {

    /** @var  String The name of the table the resource will work on */
    private $tableName;

    /** @var Attribute[] List of attributes the table has */
    private $localAttributes = array();

    /** @var External[] List of external attributes */
    private $externals = array();

    /** @var bool Was Externals already initialized */
    private $externalsInitialized = false;

    /** @var bool Do we have proof of the existance of the table */
    private $tableExits = false;

    /** @var bool Flag not to launch table creation multiple times */
    private $tableCreatedExecuted = false;

    /** @var DbModel[][] */
    private $results = array();

    function __construct()
    {
        $this->tableName = $this->init();

        //Check if init did it's job
        if (empty($this->tableName)) {
            throw new ModelException("init needs to return table name");
        }
    }

    /**
     * Checks if the attribute with that name is an external or not.
     *
     * @param $name Name of the attribute
     *
     * @return bool
     */
    public function isExternalAttribute($name){
        if (!$this->externalsInitialized) {
            $this->initExternals();
            $this->externalsInitialized = true;
        }

        return isset($this->externals[$name]);
    }

    public function getExternal($name, DbModel $model){
        if (!$this->externalsInitialized) {
            $this->initExternals();
            $this->externalsInitialized = true;
        }

        $hash = spl_object_hash($model->getCreationQuery());

        if(isset($this->results[$hash])){
            //TODO : Run the request on all results, of that query we will probably need it
        }else{
            //TODO : Run the request on 1 result only
        }
    }

    /**
     * Executes a query on the table, if the table doesen't exits yet creates it.
     * On non SQL systems will just do the query and return empty results
     *
     * @param Query $query
     *
     * @throws ModelException
     */
    protected function executeQuery(Query $query){
        try{
            //@TODO Execute database query
        }catch (QueryGenerationException $ex){
            // The error was created while generating the query, we have no info on the Database
            throw new ModelException("Issue while generating request.", $ex);
        }catch (\Exception $ex){
            //The error wasnot out of OWeb, either a problem or just database not existing.
            if($this->tableExits){
                //We already know that the table exist so the problem isn't due to that.
                throw new ModelException("Issue while generating request.", $ex);
            }else{
                //Database might just not exist. Let's make it rock
                //But first flag the table to exist to make sure not to have recrusive calls later
                $this->tableExits = true;

                $this->createDatabase();

                //We rexecute the query now that the database exists, hopefully it works.
                return $this->executeQuery($query);
            }
        }
    }

    /**
     * Creates database
     *
     * @return void
     */
    protected function createDatabase(){
        if ($this->tableCreatedExecuted) {
            return;
        }
        $this->initAttributes();
    }

    abstract protected function init();

    /**
     * Initialize all externals. Table might not have any.
     */
    protected function initExternals(){}

    /**
     * Initialisze all attributes.
     * This is only called if needed to create the database and will be ignored if not.
     *
     * @return void
     */
    abstract protected function initAttributes();
} 
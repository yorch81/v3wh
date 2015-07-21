<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * WareHouse 
 *
 * WareHouse Abstract Class for implement general methods 
 *
 * Copyright 2015 Jorge Alberto Ponce Turrubiates
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category   WareHouse
 * @package    WareHouse
 * @copyright  Copyright 2015 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2015-04-15
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
abstract class WareHouse
{
	/**
     * Connection Handler
     *
     * @var object $_conn Handler Connection
     * @access private
     */
	protected $_conn = null;

	/**
     * V3ctorWH Key
     *
     * @var string $_key V3ctorWH Key
     * @access private
     */
	protected $_key = null;

	/**
     * Log Instance
     *
     * @var object $_log Log Instance
     * @access private
     */
	protected $_log= null;

	/**
	 * Find Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id 	  Identificator of Object
	 * @return array Object
	 */
	public abstract function findObject($entity, $_id);

	/**
	 * Find by Pattern (Query)
	 *
	 * @param  string $entity Entity
	 * @param  string $query  Query Pattern
	 * @return array Object
	 */
	public abstract function query($entity, $query);

	/**
	 * Create New Object
	 *
	 * @param  string $entity    Entity
	 * @param  array $jsonObject Json Object to Insert
	 * @return array Inserted Object
	 */
	public abstract function newObject($entity, $jsonObject);

	/**
	 * Update a Object by _id
	 *
	 * @param  string $entity    Entity
	 * @param  string $_id       Identificator of Object
	 * @param  array $jsonObject New Json Object
	 * @return boolean
	 */
	public abstract function updateObject($entity, $_id, $jsonObject);

	/**
	 * Delete Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id    Identificator of Object
	 * @return boolean
	 */
	public abstract function deleteObject($entity, $_id);

	/**
	 * Create Entity
	 * 
	 * @param  string $entityName Name of Entity
	 * @param  array  $jsonConfig Json Configuration
	 * @return boolean
	 */
	public abstract function createEntity($entityName, $jsonConfig);

	/**
	 * Return if exists connection
	 *
	 * @return boolean
	 */
	public function isConnected()
	{
		return !is_null($this->_conn);
	}

	/**
	 * Gets V3ctorWH Key
	 * 
	 * @return string V3ctorWH Key
	 */
	public function getKey()
	{
		return $this->_key;
	}

	/**
	 * Initialize Log
	 */
	public function initLog()
	{
		// Create Log
		$logName = 'v3wh_log-' . date("Y-m-d") . '.log';

		$this->_log = new Logger('v3wh');
		$this->_log->pushHandler(new StreamHandler($logName, Logger::ERROR));
	}
}

/**
 * v3Mongo WareHouse for MongoDb
 *
 * @category   v3Mongo
 * @package    v3Mongo
 * @copyright  Copyright 2015 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2015-05-14
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class v3Mongo extends WareHouse
{
	/**
     * MongoDb DataBase
     *
     * @var object $_db MongoDb DataBase
     * @access private
     */
	protected $_db = null;

	/**
	 * Constructor of class
	 * 
	 * @param string $hostname   HostName MongoDb
	 * @param string $username   User of MongoDb
	 * @param string $password   Password of User
	 * @param string $dbname     DataBase Name
	 * @param string $key        V3ctorWH Key
	 */
	public function __construct($hostname, $username, $password, $dbname, $key)
	{
		$this->_key = $key;

		$this->initLog();

		try{
            $this->_conn = new Mongo('mongodb://' . $username . ':' . $password . '@' . $hostname .':27017/' . $dbname);

			if (! is_null($this->_conn))
				$this->_db = $this->_conn->selectDB($dbname);
        }
        catch (Exception $e) {
        	$this->_log->addError($e->getMessage());
            $this->_conn = null;
        }
	}

	/**
	 * Find Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id 	  Identificator of Object
	 * @return array Object
	 */
	public function findObject($entity, $_id)
	{
		$retValue = array();
		$query = array('_id' => new MongoId($_id));

		if (! is_null($this->_db)){
			try{
				$mongo = $this->_db->selectCollection($entity);

				// Find Object
				$retValue = $mongo->findOne($query);

				if (is_null($retValue))
					$retValue = array();
			}
			catch(Exception $e) {
				$this->_log->addError($e->getMessage());
			}
			
		}

		return $retValue;
	}

	/**
	 * Find by Pattern (Query)
	 *
	 * @param  string $entity Entity
	 * @param  string $query  Query Pattern
	 * @return array Object
	 */
	public function query($entity, $query)
	{
		$retValue = array();

		if (! is_null($this->_db)){
			try{
				$mongo = $this->_db->selectCollection($entity);

				// Find by query
				$cursor = $mongo->find($query);

				if (is_null($cursor))
					$retValue = array();
				else
					$retValue = iterator_to_array($cursor);
			}
			catch(Exception $e) {
				$this->_log->addError($e->getMessage());
			}	
		}

		return $retValue;
	}

	/**
	 * Create New Object
	 *
	 * @param  string $entity    Entity
	 * @param  array $jsonObject Json Object to Insert
	 * @return array Inserted Object
	 */
	public function newObject($entity, $jsonObject)
	{
		$retValue = array();

		if (! is_null($this->_db)){
			try{
				$mongo = $this->_db->selectCollection($entity);

				// Insert Object
				$mongo->insert($jsonObject);

				$retValue = $jsonObject;
			}
			catch(Exception $e) {
				$this->_log->addError($e->getMessage());
			}
		}

		return $retValue;
	}

	/**
	 * Update a Object by _id
	 *
	 * @param  string $entity    Entity
	 * @param  string $_id       Identificator of Object
	 * @param  array $jsonObject New Json Object
	 * @return boolean
	 */
	public function updateObject($entity, $_id, $jsonObject)
	{
		$retValue = true;
		$query = array('_id' => new MongoId($_id));
		$jsonUpd = array('$set' => $jsonObject);

		if (! is_null($this->_db)){
			try {
			    $mongo = $this->_db->selectCollection($entity);

			    // Update Object
			    $result = $mongo->update($query, $jsonUpd, array('w' => 1));

			    $retValue = $result["updatedExisting"];
			}
			catch (MongoCursorException $e) {
				$this->_log->addError($e->getMessage());
			    $retValue = false;
			}
		}

		return $retValue;
	}

	/**
	 * Delete Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id    Identificator of Object
	 * @return boolean
	 */
	public function deleteObject($entity, $_id)
	{
		$retValue = false;
		$query = array('_id' => new MongoId($_id));

		if (! is_null($this->_db)){
			try {
			    $mongo = $this->_db->selectCollection($entity);

			    // Remove Object
				$result = $mongo->remove($query, array('w' => 1));
				
				if ($result["n"] > 0)
					$retValue = true;
			}
			catch (MongoCursorException $e) {
				$this->_log->addError($e->getMessage());
			    $retValue = false;
			}
		}

		return $retValue;
	}

	/**
	 * Create Entity
	 * 
	 * @param  string $entityName Name of Entity
	 * @param  array  $jsonConfig Json Configuration
	 * @return boolean
	 */
	public function createEntity($entityName, $jsonConfig)
	{
		// Not Implemented for MongoDb
		return false;
	}
}

/**
 * V3MySQL WareHouse for MySQL or MariaDb
 *
 * @category   V3MySQL
 * @package    V3MySQL
 * @copyright  Copyright 2015 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2015-05-14
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class V3MySQL extends WareHouse
{
	/**
	 * Constructor of class
	 * 
	 * @param string $hostname   HostName MongoDb
	 * @param string $username   User of MongoDb
	 * @param string $password   Password of User
	 * @param string $dbname     DataBase Name
	 * @param string $key        V3ctorWH Key
	 */
	public function __construct($hostname, $username, $password, $dbname, $key)
	{
		$this->_key = $key;

		try {
			$this->_conn = new medoo([
			    'database_type' => 'mysql',
			    'database_name' => $dbname,
			    'server' => $hostname,
			    'username' => $username,
			    'password' => $password,
			    'charset' => 'utf8',
			    'port' => 3306,
			    'option' => [
			        PDO::ATTR_CASE => PDO::CASE_NATURAL
			    ]
			]); 
        }
        catch (Exception $e) {
            $this->_conn = null;
        }
	}

	/**
	 * Find Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id 	  Identificator of Object
	 * @return array Object
	 */
	public function findObject($entity, $_id)
	{
		$retValue = array();
		$query = sprintf("SELECT * FROM %s WHERE _id = %s", $entity, $_id);

		if (! is_null($this->_conn)){
			$retValue = $this->_conn->query($query)->fetchAll();
		}

		return $retValue;
	}

	/**
	 * Find by Pattern (Query)
	 *
	 * @param  string $entity Entity
	 * @param  string $query  Query Pattern
	 * @return array Object
	 */
	public function query($entity, $query)
	{
		$retValue = array();

		if (! is_null($this->_conn)){
			$retValue = $this->_conn->select($entity, '*', $query);
		}

		return $retValue;
	}

	/**
	 * Create New Object
	 *
	 * @param  string $entity    Entity
	 * @param  array $jsonObject Json Object to Insert
	 * @return array Inserted Object
	 */
	public function newObject($entity, $jsonObject)
	{
		$retValue = array();

		if (! is_null($this->_conn)){
			$last_user_id = $this->_conn->insert($entity, array($jsonObject));

			return array('_id' => $last_user_id);
		}
		else
			return $retValue;
	}

	/**
	 * Update a Object by _id
	 *
	 * @param  string $entity    Entity
	 * @param  string $_id       Identificator of Object
	 * @param  array $jsonObject New Json Object
	 * @return boolean
	 */
	public function updateObject($entity, $_id, $jsonObject)
	{
		$retValue = true;
		$arrayWhere = array('_id' => $_id);

		if (! is_null($this->_conn)){
			$this->_conn->update($entity, $jsonObject, $arrayWhere);
		}
		
		return $retValue;
	}

	/**
	 * Delete Object by _id
	 *
	 * @param  string $entity Entity
	 * @param  string $_id    Identificator of Object
	 * @return boolean
	 */
	public function deleteObject($entity, $_id)
	{
		$retValue = true;
		$arrayWhere = array('_id' => $_id);

		if (! is_null($this->_conn)){
			$this->_conn->delete($entity, $arrayWhere);
		}
		
		return $retValue;
	}

	/**
	 * Create Entity
	 * 
	 * @param  string $entityName Name of Entity
	 * @param  array  $jsonConfig Json Configuration
	 * @return boolean
	 */
	public function createEntity($entityName, $jsonConfig)
	{
		// Not Implemented for MySQL
		return false;
	}
}

?>

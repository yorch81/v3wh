<?php
require_once('WareHouse.class.php');

/**
 * V3WareHouse 
 *
 * V3WareHouse V3ctor WareHouse Class
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
 * @category   V3WareHouse
 * @package    V3WareHouse
 * @copyright  Copyright 2015 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2015-05-14
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class V3WareHouse
{
	/**
     * Instance Handler to Singleton Pattern
     *
     * @var object $_instance Instance Handler
     * @access private
     */
	private static $_instance;

	/**
     * Instance Handler
     * @var object $_v3wh Abstract Connection Handler
     *
     * @access private
     */
	private $_v3wh;


	/**
	 * Constructor of class
	 * 
	 * @param string $dbtype   DataBase Provider
	 * @param string $hostname Hostname
	 * @param string $username User Name
	 * @param string $password Password
	 * @param string $dbname   DataBase Name
	 * @param string $key      V3ctorWH Key
	 */
	private function __construct($dbtype, $hostname, $username, $password, $dbname, $key)
	{
		if(class_exists($dbtype)){
			$this->_v3wh = new $dbtype($hostname, $username, $password, $dbname, $key);
		}
		else{
			$this->_v3wh = null;
			die("DataBase Type Not Implemented");
		}
	}

	/**
	 * Singleton Implementation
	 * 
	 * @param string $dbtype   DataBase Provider
	 * @param string $hostname Hostname
	 * @param string $username User Name
	 * @param string $password Password
	 * @param string $dbname   DataBase Name
	 * @param string $key      V3ctorWH Key
	 * @return resource | null
	 */
	public static function getInstance($dbtype = 'v3Mongo', $hostname = '', $username = '', $password = '', $dbname = '', $key = '')
	{
		// If exists Instance return same Instance
		if(self::$_instance){
			return self::$_instance;
		}
		else{
			$class = __CLASS__;
			self::$_instance = new $class($dbtype, $hostname, $username, $password, $dbname, $key);
			return self::$_instance;
		}
	}

	/**
	 * Check if is Connected
	 * 
	 * @return boolean
	 */
	public function isConnected()
	{
		return $this->_v3wh->isConnected();
	}

	/**
	 * Gets V3ctorWH Key
	 * 
	 * @return string V3ctorWH Key
	 */
	public function getKey()
	{
		return $this->_v3wh->getKey();
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
		return $this->_v3wh->findObject($entity, $_id);
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
		return $this->_v3wh->query($entity, $query);
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
		return $this->_v3wh->newObject($entity, $jsonObject);
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
		return $this->_v3wh->updateObject($entity, $_id, $jsonObject);
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
		return $this->_v3wh->deleteObject($entity, $_id);
	}
}
?>
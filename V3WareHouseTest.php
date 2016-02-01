<?php
require_once('vendor/autoload.php');
require_once "config.php";

/**
 * V3WareHouseTest
 * 
 * V3WareHouseTest Test Example
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
 * @category   V3WareHouseTest
 * @package    V3WareHouseTest
 * @copyright  Copyright 2015 Jorge Alberto Ponce Turrubiates
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    1.0.0, 2015-07-17
 * @author     Jorge Alberto Ponce Turrubiates (the.yorch@gmail.com)
 */
class V3WareHouseTest extends PHPUnit_Framework_TestCase
{
    protected $v3ctor;

    /**
     * Setup Test
     */
    protected function setUp() {
    	$hostname = $GLOBALS["hostname"];
    	$username = $GLOBALS["username"];
    	$password = $GLOBALS["password"];
    	$dbname   = $GLOBALS["dbname"];
    	$port     = $GLOBALS["port"];		

    	$this->v3ctor = V3WareHouse::getInstance('v3Mongo', $hostname, $username, $password, $dbname, $port);
    }

    /**
     * TearDown Test
     */
    protected function tearDown() {
        unset($this->v3ctor);
    }

    /**
     * Test Is Connected
     */
    public function testIsConnected() {
        $expected = "";

        if ($this->v3ctor->isConnected())
        	$expected = "OK";

        $this->assertEquals($expected, "OK");
    }

    /**
     * Test Find Object
     */
    public function testFindObject() {
        $doc = array('r' => 666);

        $r = $this->v3ctor->newObject("demo", $doc);

        $id = (string) $r['_id'];

        $result = $this->v3ctor->findObject("demo", $id);

        $total = count($result);

        $this->assertGreaterThan(0, $total);
    }

    /**
     * Test Query
     */
    public function testQuery() {
        $doc = array('r' => 666);

        $r = $this->v3ctor->newObject("demo", $doc);

        $result = $this->v3ctor->query("demo", $doc);

        $total = count($result);

        $this->assertGreaterThan(0, $total);
    }

    /**
     * Test New Object
     */
    public function testNewObject() {
        $expected = 666;
        $doc = array('r' => 666);

        $newObject = $this->v3ctor->newObject("demo", $doc);

        $result = $newObject['r'];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Update Object
     */
    public function testUpdateObject() {
        $expected = true;
        $doc = array('r' => 666);

        $newObject = $this->v3ctor->newObject("demo", $doc);

        $id = (string) $newObject['_id'];

        $doc = array('r' => 777);

        $result = $this->v3ctor->updateObject("demo", $id, $doc);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test Delete Object
     */
    public function testDeleteObject() {
        $expected = true;
        $doc = array('r' => 666);

        $newObject = $this->v3ctor->newObject("demo", $doc);

        $id = (string) $newObject['_id'];

        $result = $this->v3ctor->deleteObject("demo", $id);

        $this->assertEquals($expected, $result);
    }
}
?>
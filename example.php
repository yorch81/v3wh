<?php
require 'vendor/autoload.php';

$v3 = V3WareHouse::getInstance('v3MySQL', 'localhost', 'root', 'r00tmysql', 'test', "lYltuNtYYbYRFC7QWwHn9b5aH2UJMk1234567890");


	if ($v3->isConnected()){
		$data = array('DATA' => 222, 'NAME' => 'jorge');

		$doc = array('r' => 666);
        $r = $v3->newObject("demo", $doc);

        $result = $v3->query("demo", $doc);

        $total = count($result);
        echo $total;
        
        var_dump ($result);
    }
?> 
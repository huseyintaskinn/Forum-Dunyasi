<?php 
	try {
		$vt = new PDO("mysql:dbname=forumdunyasi;host=localhost;charset=utf8mb4","root", "");
		$vt->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		echo $e->getMessage();
	} 

?>
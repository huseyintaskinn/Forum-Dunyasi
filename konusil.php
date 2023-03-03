<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	if (!isset($_GET['konuid'])) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT (SELECT altkonuid FROM acilankonu WHERE id = :id) as altkonuid, id FROM `acilankonu`';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute(Array(":id"=>$_GET['konuid']));
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
		$bolumid=$kayit["altkonuid"];
	}

	if (!in_array($_GET['konuid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	
	
	if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	$sql = "DELETE FROM yorum WHERE konuid = :id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":id"=>$_GET['konuid']));

	$sql = "DELETE FROM acilankonu WHERE id = :id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":id"=>$_GET['konuid']));

	$url="forum.php?bolumid=".$bolumid;
	git("Konu Silindi!", $url);
	

	//Bağlantıyı yok edelim...
	$vt = null;  
?>
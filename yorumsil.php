<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	if (!isset($_GET['yorumid'])) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT (SELECT uyeid FROM yorum WHERE id = :id) as uyeid, (SELECT konuid FROM yorum WHERE id = :id) as konuid, id FROM yorum';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute(Array(":id"=>$_GET['yorumid']));
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
		$konuid=$kayit["konuid"];
		$uyeid=$kayit["uyeid"];
	}

	if (!in_array($_GET['yorumid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	
	
	if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	$sql = "DELETE FROM yorum WHERE id = :id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":id"=>$_GET['yorumid']));

	$url="konu.php?konuid=".$konuid;
	git("Yorum Silindi!", $url);
	
	rutbeguncelle($uyeid);

	//Bağlantıyı yok edelim...
	$vt = null;  
?>
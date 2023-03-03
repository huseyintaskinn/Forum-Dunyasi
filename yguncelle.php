<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//yguncelle isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve index.php sayfasına yönlendiriyor
	if (!isset($_POST['yguncelle'])) {
		git("Önce formu doldurunuz!", "index.php");
		exit;
		//hata mesajı ver giriş sayfasına git
	}

	if (!isset($_POST['yazi']) or $_POST['yazi']==null or areatemizle($_POST['yazi']) == "") {
		$_SESSION['hata'][]="Yorum alanı boş bırakılamaz. Lütfen yorum alanını doldurunuz!";

		$adres="Location: yorumguncelle.php?yorumid=".$_POST["yorumid"];
		header($adres);
		exit;
	}

	if (!isset($_POST['yorumid'])) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT (SELECT konuid FROM yorum WHERE id = :id) as konuid, id FROM yorum';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute(Array(":id"=>$_POST['yorumid']));
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
		$konuid=$kayit["konuid"];
	}

	if (!in_array($_POST['yorumid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	$sql = "UPDATE yorum SET yorum = :yorum WHERE id = :id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":yorum"=>$_POST['yazi'],":id"=>$_POST['yorumid']));

	$url="konu.php?konuid=".$konuid;
	git("Yorum Güncellendi!", $url);
	

	//Bağlantıyı yok edelim...
	$vt = null;  
?>
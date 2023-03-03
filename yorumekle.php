<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//giriş yapılmış mı kontrol ediyor
	if (!isset($_SESSION['yetki'])) {
		git("Yorum yazabilmek için önce giriş yapmalısınız!", "giris.php");
	}

	if (!isset($_POST['yukle'])) {
		git("Önce formu doldurunuz!", "index.php");
		exit;
	}

	if (!isset($_POST['yorum']) or $_POST['yorum']==null or areatemizle($_POST['yorum']) == "") {
		$_SESSION['hata'][]="Yorum alanı boş bırakılamaz. Lütfen yorum alanını doldurunuz!";

		$adres="Location: konu.php?konuid=".$_POST["konuid"];
		header($adres);
		exit;
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";

	$sql = "SELECT kilit FROM acilankonu WHERE id = :konuid";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":konuid"=>$_POST['konuid']));
	$kilit = $ifade->fetch(PDO::FETCH_ASSOC);

	if ($kilit["kilit"] == "var") {
		git("Bu konuya yorum yapılamaz!", "index.php");
		exit;
	}

	$sql = "INSERT INTO yorum (uyeid, konuid, yorum) VALUES (:uyeid, :konuid, :yorum)";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":uyeid"=>$_SESSION['id'], ":konuid"=>$_POST['konuid'], ":yorum"=>$_POST['yorum']));
	
	$sql = "SELECT tarih FROM yorum WHERE uyeid = :uyeid and konuid = :konuid and yorum = :yorum";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":uyeid"=>$_SESSION['id'], ":konuid"=>$_POST['konuid'], ":yorum"=>$_POST['yorum']));
	$tarih = $ifade->fetch(PDO::FETCH_ASSOC);

	$sql = "UPDATE acilankonu SET sonyorumtarih = :tarih WHERE id = :konuid";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":tarih"=>$tarih['tarih'], ":konuid"=>$_POST['konuid']));

	rutbeguncelle($_SESSION['id']);
	sonaktivite($_SESSION['id']);

	//Bağlantıyı yok edelim...
	$vt = null; 

	$url="konu.php?konuid=".$_POST['konuid'];
	git("Yorum eklendi!", $url);
	

	 
?>
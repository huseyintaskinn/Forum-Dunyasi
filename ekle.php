<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	$hata=0;

	//yukle isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve konuekle.php sayfasına yönlendiriyor
	if (!isset($_POST['yukle'])) {
		git("Önce formu doldurunuz!", "index.php");
		exit;
		//hata mesajı ver giriş sayfasına git
	}

	if (!isset($_POST['baslik']) or $_POST['baslik']==null or trim($_POST['baslik']) == "") {
		
		if (isset($_POST['yazi'])) {
			$_SESSION['yazi']=$_POST['yazi'];
		}

		if (isset($_POST['tur'])) {
			$_SESSION['tur']=$_POST['tur'];
		}

		if (isset($_POST['kilit'])) {
			$_SESSION['kilit']=$_POST['kilit'];
		}

		$_SESSION['hata'][]="Başlık boş bırakılamaz. Lütfen başlık alanını doldurunuz!";

		$adres="Location: konuekle.php?bolumid=".$_POST["bolumid"];
		header($adres);
		$hata=1;
	}

	elseif (strlen($_POST['baslik']) > 255) {
		
		if (isset($_POST['yazi'])) {
			$_SESSION['yazi']=$_POST['yazi'];
		}

		if (isset($_POST['tur'])) {
			$_SESSION['tur']=$_POST['tur'];
		}

		if (isset($_POST['kilit'])) {
			$_SESSION['kilit']=$_POST['kilit'];
		}

		$_SESSION['baslik']=$_POST['baslik'];

		$_SESSION['hata'][]="Başlık en fazla 255 karakter olabilir. Lütfen uygun bir başlık giriniz!";

		$adres="Location: konuekle.php?bolumid=".$_POST["bolumid"];
		header($adres);
		$hata=1;
	}

	if (!isset($_POST['yazi']) or $_POST['yazi']==null or areatemizle($_POST['yazi']) == "") {
		if (isset($_POST['baslik'])) {
			$_SESSION['baslik']=$_POST['baslik'];
		}

		if (isset($_POST['tur'])) {
			$_SESSION['tur']=$_POST['tur'];
		}

		if (isset($_POST['kilit'])) {
			$_SESSION['kilit']=$_POST['kilit'];
		}

		$_SESSION['hata'][]="İçerik boş bırakılamaz. Lütfen içerik alanını doldurunuz!";

		$adres="Location: konuekle.php?bolumid=".$_POST["bolumid"];
		header($adres);
		$hata=1;
	}

	if ($hata==1) {
		exit;
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";

	if (!isset($_POST['tur'])) {
		$tur="normal";
	}
	else{
		$tur=$_POST['tur'];
	}

	if (!isset($_POST['kilit'])) {
		$kilit="yok";
	}
	else{
		$kilit=$_POST['kilit'];
	}

	$sql = "INSERT INTO acilankonu(altkonuid, uyeid, baslik, icerik, tur, kilit) VALUES (:altkonuid, :uyeid, :baslik, :icerik, :tur, :kilit)";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":altkonuid"=>$_POST['bolumid'], ":uyeid"=>$_SESSION['id'], ":baslik"=>$_POST['baslik'], ":icerik"=>$_POST['yazi'],":tur"=>$tur, ":kilit"=>$kilit));

	rutbeguncelle($_SESSION['id']);
	sonaktivite($_SESSION['id']);

	$vt = null; 

	$url="forum.php?bolumid=".$_POST['bolumid']."&git=Konuları+Görüntüle";
	git("Konu eklendi!", $url);
	
	 
?>
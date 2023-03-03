<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//yukle isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve konuekle.php sayfasına yönlendiriyor
	if (!isset($_POST['guncelle'])) {
		git("Önce formu doldurunuz!", "index.php");
		exit;
		//hata mesajı ver giriş sayfasına git
	}

	//Eğer urlde bolumid yoksa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_POST['konuid'])) {
		header("Location: index.php");
		exit;
	}

	$hata=0;

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

		$_SESSION['baslik']="";

		$_SESSION['hata'][]="Başlık boş bırakılamaz. Lütfen başlık alanını doldurunuz!";

		$adres="Location: konuguncelle.php?konuid=".$_POST["konuid"];
		header($adres);
		$hata=1;
	}

	if (strlen($_POST['baslik']) > 255) {
		
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

		$adres="Location: konuguncelle.php?konuid=".$_POST["konuid"];
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

		$_SESSION['yazi']="";

		$_SESSION['hata'][]="İçerik boş bırakılamaz. Lütfen içerik alanını doldurunuz!";

		$adres="Location: konuguncelle.php?konuid=".$_POST["konuid"];
		header($adres);
		$hata=1;
	}

	if ($hata==1) {
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT id FROM acilankonu';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute();
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
	}

	if (!in_array($_POST['konuid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	$sql = "UPDATE acilankonu SET baslik=:baslik,icerik=:icerik,tur=:tur,kilit=:kilit WHERE id = :id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":baslik"=>$_POST['baslik'], ":icerik"=>$_POST['yazi'], ":tur"=>$_POST['tur'], ":kilit"=>$_POST['kilit'], ":id"=>$_POST['konuid']));

	//Bağlantıyı yok edelim...
	$vt = null;  

	sonaktivite($_SESSION['id']);
	
	$url="konu.php?konuid=".$_POST['konuid'];
	git("Konu Güncellendi!", $url);
	

	
?>
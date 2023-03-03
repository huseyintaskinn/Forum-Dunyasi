<?php
	session_start(); //Oturum bilgilerine ulaşmak için kullandım
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Giriş yapılmış mı diye kontrol ediyor. Giriş yapılmadıysa herhangi bir işlem yapmadan ana sayfaya yönlendiriyor.
	if (!isset($_SESSION['yetki']) and $_SESSION['yetki'] != true) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql2 ="UPDATE uye SET cevrimici=0 WHERE id = :id";
	$ifade2 = $vt->prepare($sql2);
	$ifade2->execute(Array(":id"=> $_SESSION['id']));

	$vt=null;

	session_destroy(); //Tüm oturum bilgilerini siliyor
	$_SESSION = null;

	//Mesaj verip ana sayfaya yönlendiriyor.
	git("Çıkış yapıldı", "index.php"); 
?> 

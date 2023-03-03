<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Eğer giriş yapıldıysa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (isset($_SESSION['yetki']) and $_SESSION['yetki'] == true) {
		header("Location: index.php");
		exit;
	}

	//giris isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve giris.php sayfasına yönlendiriyor
	if (!isset($_POST['giris'])) {
		git("Önce formu doldurunuz!", "giris.php");
		//hata mesajı ver giriş sayfasına git
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";

	//Girilen kullanıcı adını veri tabanında aratıyor
	$sql ="select * from uye where kullaniciadi = :kullaniciadi";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":kullaniciadi"=> $_POST['kad']));
	$kayit = $ifade->fetch(PDO::FETCH_ASSOC);

	//Eğer herhangi bir sonuç gelmezse böyle bir kullanıcı yok demektir. Bu durumda mesaj veriyor ve giris.php sayfasına yönlendiriyor
	if ($kayit == false) {
		$_SESSION['hata']="Bu kullanıcı adı veya şifre yanlış! ";

		$adres="Location: giris.php";
		header($adres);
		exit;
	}

	//Eğer sonuç gelirse ife girmeden devam eder.

	//Eğer gelen sonuçlardaki şifre ve girilen şifre aynıysa gelen sonuçlara göre oturumda atama işlemleri yapıyorum
	if (password_verify($_POST['sifre'], $kayit["sifre"])) {
		
		$_SESSION['yetki'] = true;
		$_SESSION['id'] = $kayit['id'];
		$_SESSION['adsoyad'] = $kayit['adsoyad'];
		$_SESSION['email'] = $kayit['email'];
		$_SESSION['kullaniciadi'] = $kayit['kullaniciadi'];
		$_SESSION['rutbe'] = $kayit['rutbe'];
		$_SESSION['resim'] = $kayit['resim'];

		$sql2 ="UPDATE uye SET cevrimici=1, son = CURRENT_TIMESTAMP WHERE id = :id";
    	$ifade2 = $vt->prepare($sql2);
    	$ifade2->execute(Array(":id"=> $_SESSION['id']));

		git("Giriş Başarılı", "giris.php");

	}

	//Şifreler eşleşmiyorsa mesaj verip giris.php sayfasına yönlendiriyor.
	else{
		$_SESSION['hata']="Bu kullanıcı adı veya şifre yanlış! ";

		$adres="Location: giris.php";
		header($adres);
		exit;
	}

	//Bağlantıyı yok edelim...
	$vt = null;	  

	  
?>
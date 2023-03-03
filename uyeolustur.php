<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	$hata=0;

	//Eğer giriş yapıldıysa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (isset($_SESSION['yetki']) and $_SESSION['yetki'] == true) {
		header("Location: index.php");
		exit;
	}

	//kayit isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve kayitol.php sayfasına yönlendiriyor
	if (!isset($_POST['kayit'])) {
		git("Önce formu doldurunuz!", "kayitol.php");
		//hata mesajı ver giriş sayfasına git
	}

	if (!isset($_POST['ad']) or $_POST['ad']==null or trim($_POST['ad']) == "") {
		
		$_SESSION['hata2'][0]="Ad soyad alanı boş bırakılamaz!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	elseif (strlen($_POST['ad']) > 255) {

		$_SESSION['hata2'][1]="Ad soyad en fazla 255 karakter olabilir!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	if (!isset($_POST['mail']) or $_POST['mail']==null or trim($_POST['mail']) == "" and !filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL)) {
		
		$_SESSION['hata2'][2]="Geçersiz bir email adresi girdiniz!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	elseif (strlen($_POST['mail']) > 255) {

		$_SESSION['hata2'][3]="Email adresi en fazla 255 karakter olabilir!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	if (!isset($_POST['kad']) or $_POST['kad']==null or trim($_POST['kad']) == "") {
		
		$_SESSION['hata2'][4]="Kullanıcı adı alanı boş bırakılamaz!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	elseif (strlen($_POST['kad']) > 255) {

		$_SESSION['hata2'][5]="Kullanıcı adı en fazla 255 karakter olabilir!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	if (!isset($_POST['sifre']) or $_POST['sifre']==null) {
		
		$_SESSION['hata2'][6]="Şifre alanı boş bırakılamaz!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	elseif (strlen($_POST['sifre']) > 255) {

		$_SESSION['hata2'][7]="Şifre en fazla 255 karakter olabilir!";

		$adres="Location: kayitol.php";
		header($adres);
		$hata=1;
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";

	//Daha önce bu kullanıcı adı veya email alınmış mı veri tabanında aratıyor
	$sql ="select * from uye where kullaniciadi = :kullaniciadi or email = :email";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":kullaniciadi"=> $_POST['kad'], ":email"=> $_POST['mail']));

	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		if ($kayit["id"] != $_SESSION['id']) {
			$_SESSION['hata2'][8]="Bu kullanıcı adı veya email daha önce kullanılmış! ";

			$adres="Location: kayitol.php";
			header($adres);
			$hata=1;
		}
	}

	if ($hata==1) {
		if (isset($_POST['ad'])) {
			$_SESSION['ad']=$_POST['ad'];
		}

		if (isset($_POST['mail'])) {
			$_SESSION['mail']=$_POST['mail'];
		}

		if (isset($_POST['kad'])) {
			$_SESSION['kad']=$_POST['kad'];
		}

		if (isset($_POST['sifre'])) {
			$_SESSION['sifre']=$_POST['sifre'];
		}
		
		exit;
	}

	//Şifreyi şifreliyor
	$sifre=password_hash($_POST['sifre'], PASSWORD_DEFAULT);

	//Üyeyi ekliyor
	$sql = "insert into uye (adsoyad, email, kullaniciadi, sifre) values (:adsoyad, :email, :kullaniciadi, :sifre)";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":adsoyad"=>$_POST['ad'], ":email"=>$_POST['mail'], ":kullaniciadi"=>$_POST['kad'], ":sifre"=>$sifre));

	git("Hesabınız oluşturuldu! Giriş yapabilirsiniz!", "giris.php");
	
	//Bağlantıyı yok edelim...
	$vt = null;  
?>
<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	$hata=0;

	//Eğer giriş yapılmadıysa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_SESSION['yetki'])) {
		header("Location: index.php");
		exit;
	}

	//guncelle isimli butona basmadan bu sayfaya gelindiyse mesaj veriyor ve profil.php sayfasına yönlendiriyor
	if (!isset($_POST['guncelle'])) {
		git("Önce formu doldurunuz!", "profil.php");
		//hata mesajı ver profil sayfasına git
	}

	//ad alanı boş bırakıldıysa hata oluştur ve geri gönder
	if (!isset($_POST['ad']) or $_POST['ad']==null or trim($_POST['ad']) == "") {
		
		$_SESSION['hata2'][0]="Ad soyad alanı boş bırakılamaz!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//ad alanı 255 karakterden uzunsa hata oluştur ve geri gönder
	elseif (strlen($_POST['ad']) > 255) {

		$_SESSION['hata2'][1]="Ad soyad en fazla 255 karakter olabilir!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//mail alanı boş bırakıldıysa hata oluştur ve geri gönder
	if (!isset($_POST['mail']) or $_POST['mail']==null or trim($_POST['mail']) == "" and !filter_var($_GET['mail'], FILTER_VALIDATE_EMAIL)) {
		
		$_SESSION['hata2'][2]="Geçersiz bir email adresi girdiniz!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//mail alanı 255 karakterden uzunsa hata oluştur ve geri gönder
	elseif (strlen($_POST['mail']) > 255) {

		$_SESSION['hata2'][3]="Email adresi en fazla 255 karakter olabilir!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//kad (kullanıcı adı) alanı boş bırakıldıysa hata oluştur ve geri gönder
	if (!isset($_POST['kad']) or $_POST['kad']==null or trim($_POST['kad']) == "") {
		
		$_SESSION['hata2'][4]="Kullanıcı adı alanı boş bırakılamaz!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//kad alanı 255 karakterden uzunsa hata oluştur ve geri gönder
	elseif (strlen($_POST['kad']) > 255) {

		$_SESSION['hata2'][5]="Kullanıcı adı en fazla 255 karakter olabilir!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//şifre alanı boş bırakıldıysa hata oluştur ve geri gönder
	if (!isset($_POST['sifre']) or $_POST['sifre']==null) {
		
		$_SESSION['hata2'][6]="Şifre alanı boş bırakılamaz!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//şifre alanı 255 karakterden uzunsa hata oluştur ve geri gönder
	elseif (strlen($_POST['sifre']) > 255) {

		$_SESSION['hata2'][7]="Şifre en fazla 255 karakter olabilir!";

		$adres="Location: profil.php";
		header($adres);
		$hata=1;
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";

	//Daha önce bu kullanıcı adı veya email alınmış mı veri tabanında aratıyor
	$sql ="select * from uye where kullaniciadi = :kullaniciadi or email = :email";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":kullaniciadi"=> $_POST['kad'], ":email"=> $_POST['mail']));

	//daha önce u kullanıcı adı veya email kullanıldıysa hata mesajı oluştur ve geri gönder
	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		if ($kayit["id"] != $_SESSION['id']) {
			$_SESSION['hata2'][8]="Bu kullanıcı adı veya email daha önce kullanılmış! ";

			$adres="Location: profil.php";
			header($adres);
			$hata=1;
		}
	}

	//herhangi bir hata oluştuysa sayfanın geri kalanını çalıştırma
	if ($hata==1) {
		exit;
	}

	//Şifreyi şifreliyor
	$sifre=password_hash($_POST['sifre'], PASSWORD_DEFAULT);

	//yeni bilgilerle güncelleme yapıyor	
	$sql = "UPDATE uye SET adsoyad=:adsoyad, email=:email, kullaniciadi=:kullaniciadi, sifre=:sifre WHERE id=:id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":adsoyad"=>$_POST['ad'], ":email"=>$_POST['mail'], ":kullaniciadi"=>$_POST['kad'], ":sifre"=>$sifre, ":id"=>$_SESSION["id"]));

	//session değerlerini de güncelliyor
	$_SESSION['adsoyad'] = $_POST['ad'];
	$_SESSION['email'] = $_POST['mail'];
	$_SESSION['kullaniciadi'] = $_POST['kad'];

	//işlemin başarıyla gerçekleştiğine dair mesaj oluşturuyor ve profil.php de gönderyor
	$_SESSION['mesaj2']="Bilgileriniz Güncellendi!";
	$adres="Location: profil.php";
	header($adres);

	//Bağlantıyı yok edelim...
	$vt = null;  
?>
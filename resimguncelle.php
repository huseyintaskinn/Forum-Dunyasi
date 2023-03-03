<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	
	include "inc/fonksiyonlar.inc.php";

	//Eğer giriş yapılmadıysa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_SESSION['yetki'])) {
		header("Location: index.php");
		exit;
	}

	if (!isset($_POST['yukle'])) {
		header("Location: profil.php");
		//hata mesajı ver profil sayfasına git
	}

	if ($_FILES["profilp"]["error"] == UPLOAD_ERR_INI_SIZE) {
		
		$_SESSION['hata']="Dosya boyutu çok büyük. Lütfen başka bir resim seçiniz!";

		$adres="Location: profil.php";
		header($adres);
		exit;
	}

	if ($_FILES["profilp"]["error"] == UPLOAD_ERR_NO_FILE) {

	    $_SESSION['hata']="Herhangi bir dosya seçilmedi! Lütfen bir resim dosyası seçip tekrar deneyiniz!";

		$adres="Location: profil.php";
		header($adres);
		exit;
	}

	//Dosya yüklerken hata oluştu mu?
	if ($_FILES["profilp"]["error"] != 0) {
	   
	    $_SESSION['hata']="Dosya yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz!";

		$adres="Location: profil.php";
		header($adres);
		exit;
	}

	// Yüklenen resim dosyası mı kontrol et!
	// Yüklenen dosyanın tipi ile izin verilen dosya tiplerini karşılaştır.
	if ($_FILES["profilp"]["type"] == "image/png" or $_FILES["profilp"]["type"] == "image/jpeg") {
	  // devam edecek
	} else {
	    $_SESSION['hata']="Yükleyeceğiniz dosya bir resim dosyası olmalıdır!";

		$adres="Location: profil.php";
		header($adres);
		exit;
	}

	// Dosyayı sunucuya yükleyelim
	$hedefKlasor = "yuklenenler/";
	$hedefKlasor .= time();
	$hedefKlasor = $hedefKlasor.basename($_FILES['profilp']['name']);
	//basename ile sadece dosyanın ismi alınıyor.
	if (move_uploaded_file($_FILES["profilp"]['tmp_name'], $hedefKlasor))
	{
		// devam eder
	} else {
	    $_SESSION['hata']="Dosya yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyiniz!";

		$adres="Location: profil.php";
		header($adres);
		exit;
	}

	//Veri tabanı bağlantısı
	include "inc/vtbaglanti.inc.php";
	
	$sql = "UPDATE uye SET resim=:resim WHERE id=:id";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":resim"=>$hedefKlasor,":id"=>$_SESSION["id"]));

	$_SESSION["resim"]=$hedefKlasor;
	$_SESSION['mesaj']="Profil resminiz başarıyla güncellendi!";

	$adres="Location: profil.php";
	header($adres);

	//Bağlantıyı yok edelim...
	$vt = null;  
?>
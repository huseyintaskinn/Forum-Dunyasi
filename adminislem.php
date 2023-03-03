<?php 
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Rütbe adminse girsin değilse ana sayfaya gitsin
	if (isset($_SESSION["rutbe"]) and $_SESSION['rutbe'] == 'Admin') {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	//eğer kekle butonuna basıldıysa kategori eklesin 
	if (isset($_POST['kekle'])) {
		//kekle butonunun üstünde yer alan input boşsa hata mesajını sessionla kaydet ve geri gönder
		if (!isset($_POST['kategori']) or $_POST['kategori']==null or trim($_POST['kategori']) == "") {
			$_SESSION['hata3'][0]="Kategori adı boş bırakılamaz!";

			$adres="Location: adminpanel.php#1";
			header($adres);
			exit;
		}
		else{

			//doluysa kategoriyi ekle ve eklendiğine dair mesajı sessionla adminpanel.phpde göster
			include "inc/vtbaglanti.inc.php";

			$sql = "INSERT INTO konular (konu) VALUES (:kategori)";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":kategori"=>$_POST['kategori']));

			$vt = null; 

			$_SESSION['mesaj3'][0]="Kategori başarıyla eklendi!";

			$adres="Location: adminpanel.php#1";
			header($adres);
			exit;
		}
	}

	//eğer bekle butonuna basıldıysa bölüm eklesin 
	elseif (isset($_POST['bekle'])) {
		//kategori seçilmediyse hata mesaj oluştur ve geri gönder
		if ($_POST['kategoriid']=="") {
			$_SESSION['hata3'][1]="Lütfen bir kategori seçiniz!";

			$adres="Location: adminpanel.php#2";
			header($adres);
			exit;
		}
		//input alan boşsa hata mesajıoluştur ve geri gönder
		if (!isset($_POST['bolum']) or $_POST['bolum']==null or trim($_POST['bolum']) == "") {
			$_SESSION['hata3'][1]="Bölüm adı boş bırakılamaz!";

			$adres="Location: adminpanel.php#2";
			header($adres);
			exit;
		}
		else{

			//bilgiler tamsa bölümü ekle ve eklendiğine dair mesajı oluştur ve sessionla adminpanel.php de göster
			include "inc/vtbaglanti.inc.php";

			$sql = "INSERT INTO altkonu (konuid, altkonu) VALUES (:kategoriid, :bolum)";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":kategoriid"=>$_POST['kategoriid'],":bolum"=>$_POST['bolum']));

			$vt = null; 

			$_SESSION['mesaj3'][1]="Bölüm başarıyla eklendi!";

			$adres="Location: adminpanel.php#2";
			header($adres);
			exit;
		}
	}

	//ver butonuna basıldıysa moderatör yetkisi vermek için işlemler yap
	elseif (isset($_POST['ver'])) {
		//üye seçilmediyse hata mesajı oluştur ve geri gönder
		if ($_POST['uyeid']=="") {
			$_SESSION['hata3'][2]="Lütfen bir üye seçiniz!";

			$adres="Location: adminpanel.php#4";
			header($adres);
			exit;
		}

		else{

			//üye seçildiyse üyeye moderatör yetkisi ver ve moderatör yapıldığına dair mesajı oluştur daha sonra adminpanel.php de göster
			modekle($_POST['uyeid']);

			$_SESSION['mesaj3'][2]="Seçilen üyeye moderatör yetkisi verildi!";

			$adres="Location: adminpanel.php#4";
			header($adres);
			exit;
		}
	}

	//kaldir butonuna basıldıysa üyenin moderatör yetkisini kaldırmak için işlemler yap
	elseif (isset($_POST['kaldir'])) {
		//üye seçilmediyse hata mesajı oluştur ve geri gönder
		if ($_POST['uyeid2']=="") {
			$_SESSION['hata3'][3]="Lütfen bir üye seçiniz!";

			$adres="Location: adminpanel.php#6";
			header($adres);
			exit;
		}

		else{
			//üye seçildiyse moderatör yetkisi geri al ve geri alındığına dair mesajı oluştur adminpanel.php de göster
			modcikar($_POST['uyeid2']);

			$_SESSION['mesaj3'][3]="Seçilen üyenin moderatör yetkisi kaldırıldı!";

			$adres="Location: adminpanel.php#6";
			header($adres);
			exit;
		}
	}

	//ksil butonuna basıldıysa kategoriyi sil
	elseif (isset($_POST['ksil'])) {
		//Kategori seçilmediyse hata mesajı oluştur ve geri gönder
		if ($_POST['kategoriid3']=="") {
			$_SESSION['hata3'][4]="Lütfen bir kategori seçiniz!";

			$adres="Location: adminpanel.php#1";
			header($adres);
			exit;
		}

		else{
			//Kategori seçildiyse kategoriye dair her şeyi sil ve silindiğine dair bir mesaj oluştur adminpanel.php de göster
			include "inc/vtbaglanti.inc.php";

			//önce kategorideki yorumları sil
			$sql = "DELETE yorum FROM yorum INNER JOIN acilankonu ON acilankonu.id = yorum.konuid INNER JOIN altkonu ON acilankonu.altkonuid = altkonu.id AND altkonu.konuid = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['kategoriid3']));

			//sonra ketgorideki konuları sil
			$sql = "DELETE acilankonu FROM acilankonu INNER JOIN altkonu ON altkonuid = altkonu.id AND altkonu.konuid = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['kategoriid3']));

			//sonra kategorideki bölümleri sil
			$sql = "DELETE FROM altkonu WHERE konuid = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['kategoriid3']));

			//en son kategoriyi sil
			$sql = "DELETE FROM konular WHERE id = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['kategoriid3']));

			$vt = null; 

			$_SESSION['mesaj3'][4]="Seçilen kategori silindi!";

			$adres="Location: adminpanel.php#1";
			header($adres);
			exit;
		}
	}

	//bsil butonuna basıldıysa bölümü sil
	elseif (isset($_POST['bsil'])) {
		//bölüm seçilmediyse hata mesajı oluştur ve geri gönder
		if ($_POST['bolumid']=="") {
			$_SESSION['hata3'][5]="Lütfen bir bölüm seçiniz!";

			$adres="Location: adminpanel.php#3";
			header($adres);
			exit;
		}

		else{
			
			//Bölüm seçildiyse bölüme dair her şeyi sil ve silindiğine dair bir mesaj oluştur adminpanel.php de göster
			include "inc/vtbaglanti.inc.php";

			//Bölümdeki yorumları sil
			$sql = "DELETE yorum FROM yorum INNER JOIN acilankonu ON acilankonu.id = yorum.konuid AND acilankonu.altkonuid = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['bolumid']));

			//bölümdeki konuları sil
			$sql = "DELETE FROM acilankonu WHERE altkonuid = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['bolumid']));

			//bölümü sil
			$sql = "DELETE FROM altkonu WHERE id = :id";
			$ifade = $vt->prepare($sql);
			$ifade->execute(Array(":id"=>$_POST['bolumid']));

			$vt = null; 

			$_SESSION['mesaj3'][5]="Seçilen bölüm silindi!";

			$adres="Location: adminpanel.php#3";
			header($adres);
			exit;
		}
	}

 ?>
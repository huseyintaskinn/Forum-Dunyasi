<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//rütbe Admin değilse ana sayfaya yönlendir
	if (isset($_SESSION["rutbe"]) and $_SESSION['rutbe'] == 'Admin') {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

	$uye=array();
	$mod=array();
	$kategoriler=array();
	$bolumler=array();

	include "inc/vtbaglanti.inc.php";

	// Herhangi bir yetkiye sahip olmayan üyeleri alma
	$sql='SELECT id, kullaniciadi FROM uye WHERE rutbe != "Moderatör" AND rutbe != "Admin"';
	$ifade = $vt->prepare($sql);
    $ifade->execute();

    while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
    	$uye[$kayit['id']]=$kayit['kullaniciadi'];
    }

    //Moderatör olan üyeleri alma
    $sql='SELECT id, kullaniciadi FROM uye WHERE rutbe = "Moderatör" AND rutbe != "Admin"';
	$ifade = $vt->prepare($sql);
    $ifade->execute();

    while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
    	$mod[$kayit['id']]=$kayit['kullaniciadi'];
    }

    //Forumdaki tüm kategorileri alma
    $sql='SELECT * FROM konular';
	$ifade = $vt->prepare($sql);
    $ifade->execute();

    while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
    	$kategoriler[$kayit['id']]=$kayit['konu'];
    }

    //Forumdaki tüm bölümleri alma
    $sql='SELECT id, altkonu FROM altkonu';
	$ifade = $vt->prepare($sql);
    $ifade->execute();

    while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
    	$bolumler[$kayit['id']]=$kayit['altkonu'];
    }

    $vt = null;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="resimler/favicon.ico">
	<title>Forum Dünyası</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/astyle.css">
</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>

	<main class="panel" id="1">
		<div class="blok">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Kategori Ekle</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][0])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][0],'</label><br><br>';

							unset($_SESSION['hata3'][0]);
						}
						elseif (isset($_SESSION['mesaj3'][0])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][0],'</label><br><br>';

							unset($_SESSION['mesaj3'][0]);
						}
					?>
					<label>Kategori adı giriniz:</label>
					<input type="text" name="kategori" required maxlength="255" style="margin-left: 10px;">
					<input type="submit" name="kekle" value="Ekle">
				</div>
				
			</form>
		</div>

		<div class="blok" id="2">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Kategoriyi Sil</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][4])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][4],'</label><br><br>';

							unset($_SESSION['hata3'][4]);
						}
						elseif (isset($_SESSION['mesaj3'][4])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][4],'</label><br><br>';

							unset($_SESSION['mesaj3'][4]);
						}
					?>
					<label>Kategori adı seçiniz:</label>
					<select name="kategoriid3">
						<option value="">Seçiniz</option>
						<?php 
							foreach ($kategoriler as $id => $kategori) {
								echo '<option value="',$id,'">',htmlentities($kategori),'</option>';
							}
						?>
					</select>
					<input type="submit" name="ksil" value="Kategoriyi Sil">
				</div>
			</form>
		</div>

		<div class="blok" id="3">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Bölüm Ekle</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][1])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][1],'</label><br><br>';

							unset($_SESSION['hata3'][1]);
						}
						elseif (isset($_SESSION['mesaj3'][1])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][1],'</label><br><br>';

							unset($_SESSION['mesaj3'][1]);
						}
					?>
					<label>Kategori adı seçiniz:</label>
					<select name="kategoriid">
						<option value="">Seçiniz</option>
						<?php 
							foreach ($kategoriler as $id => $kategori) {
								echo '<option value="',$id,'">',htmlentities($kategori),'</option>';
							}
						?>
					</select>
				</div>
				<div>
					<label>Bölüm adı giriniz:</label>
					<input type="text" name="bolum" required maxlength="255" style="margin-left: 33px;">
					<input type="submit" name="bekle" value="Ekle">
				</div>
			</form>
		</div>

		<div class="blok" id="4">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Bölümü Sil</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][5])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][5],'</label><br><br>';

							unset($_SESSION['hata3'][5]);
						}
						elseif (isset($_SESSION['mesaj3'][5])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][5],'</label><br><br>';

							unset($_SESSION['mesaj3'][5]);
						}
					?>
					<label>Bölüm adı seçiniz:</label>
					<select name="bolumid">
						<option value="">Seçiniz</option>
						<?php 
							foreach ($bolumler as $id => $bolum) {
								echo '<option value="',$id,'">',htmlentities($bolum),'</option>';
							}
						?>
					</select>
					<input type="submit" name="bsil" value="Bölümü Sil">
				</div>
			</form>
		</div>

		<div class="blok" id="5">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Moderatör Yetkisi Ver</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][2])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][2],'</label><br><br>';

							unset($_SESSION['hata3'][2]);
						}
						elseif (isset($_SESSION['mesaj3'][2])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][2],'</label><br><br>';

							unset($_SESSION['mesaj3'][2]);
						}
					?>
					<label>Kullanıcı adı seçiniz:</label>
					<select name="uyeid">
						<option value="">Seçiniz</option>
						<?php 
							foreach ($uye as $id => $kad) {
								echo '<option value="',$id,'">',htmlentities($kad),'</option>';
							}
						?>
					</select>
					<input type="submit" name="ver" value="Yetki Ver">
				</div>
				
			</form>
		</div>

		<div class="blok" id="6">
			<form action="adminislem.php" method="POST">
				<label id="baslik3">Moderatör Yetkisini Kaldır</label>
				<br>
				<div>
					<?php 
						//Hata veya mesaj varsa göster sonra sil
						if (isset($_SESSION['hata3'][3])) {
							echo '<label style="color: red;">',$_SESSION['hata3'][3],'</label><br><br>';

							unset($_SESSION['hata3'][3]);
						}
						elseif (isset($_SESSION['mesaj3'][3])) {
							echo '<label style="color: green;">',$_SESSION['mesaj3'][3],'</label><br><br>';

							unset($_SESSION['mesaj3'][3]);
						}
					?>
					<label>Kullanıcı adı seçiniz:</label>
					<select name="uyeid2">
						<option value="">Seçiniz</option>
						<?php 
							foreach ($mod as $id => $kad) {
								echo '<option value="',$id,'">',htmlentities($kad),'</option>';
							}
						?>
					</select>
					<input type="submit" name="kaldir" value="Yetki Kaldır">
				</div>
				
			</form>
		</div>
		
	</main>

	<?php
		include "inc/footer.inc.php";
	?>
</body>
</html>
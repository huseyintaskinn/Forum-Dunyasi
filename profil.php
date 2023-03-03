<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	if (!isset($_SESSION['yetki'])) {
		git("Profilinizi görüntüleyebilmek için önce giriş yapmalısınız!", "giris.php");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="resimler/favicon.ico">
	<title>Forum Dünyası | Ana Sayfa</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/anasayfa.css">
	<link rel="stylesheet" type="text/css" href="css/profil.css">
</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>

	<main class="profil">
		
		<div class="panel">
			<div class="resim">
				<h1>Profil Fotoğrafı</h1>

				<?php 

					if (isset($_SESSION['mesaj'])) {
						echo '<p style="align-self:center; margin: 5px 0px 12px 0px; color: green; text-align: center; padding: 0px 5px;">';
						echo $_SESSION['mesaj'];
						echo '</p>';

						unset($_SESSION['mesaj']);
					}

					if (isset($_SESSION['hata'])) {
						echo '<p style="align-self:center; margin: 5px 0px 12px 0px; color: red;text-align: center; padding: 0px 5px;">';
						echo $_SESSION['hata'];
						echo '</p>';

						unset($_SESSION['hata']);
					}

					echo '<div class="cerceve" style="min-height:250px; min-width: 250px; max-width: 250px; max-height: 250px;">';
					echo '<img src="',$_SESSION["resim"],'" alt="user" height="250px" ></div>'; 

				?>

				<form style="display: flex; flex-direction: column;" enctype="multipart/form-data" action="resimguncelle.php" method="POST">
					<label for="profilp" style="align-self:center; margin-top: 10px;">Yeni Profil Fotoğrafı Ekle</label>
					<input type="file" name="profilp" id="profilp" accept="image/png,image/jpeg">
					<input type="submit" name="yukle" value="Profil Resmimi Değiştir">
				</form>
			</div>

			<span style="height: 600px; width: 2px; background-color: #bbbbbb; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);"></span>

			<div class="bilgilerim">
				<h1>Bilgilerim</h1>
				<?php 
					if (isset($_SESSION['hata2'][8])) {
						echo '<label style="color: red;">';
						echo $_SESSION['hata2'][8];
						echo '</label>';

						unset($_SESSION['hata2'][8]);
					}

					if (isset($_SESSION['mesaj2'])) {
						echo '<label style="color: green;">';
						echo $_SESSION['mesaj2'];
						echo '</label>';

						unset($_SESSION['mesaj2']);
					}
				?>
				<form style="margin-top:30px;" action="uyeguncelle" method="POST">
					
					<div class="inputalan">
						<input type="text" name="ad" id="ad" required maxlength="255" value=<?php echo "'".tirnak(htmlentities($_SESSION['adsoyad']))."'"; ?>>	
						<label for="ad">Ad Soyad</label>
					</div>
					<?php 
						if (isset($_SESSION['hata2'][0])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][0];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][0]);
		            	}

		            	if (isset($_SESSION['hata2'][1])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][1];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][1]);
		            	}
					?>
					<div class="inputalan">
						<input type="email" name="mail" id="mail" required placeholder=" " maxlength="255" value=<?php echo "'".tirnak(htmlentities($_SESSION['email']))."'"; ?>>
						<label for="mail">Email</label>
						
					</div>
					<?php 
						if (isset($_SESSION['hata2'][2])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][2];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][2]);
		            	}

		            	if (isset($_SESSION['hata2'][3])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][3];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][3]);
		            	}
					?>
					<div class="inputalan">
						<input type="text" name="kad" id="kad" required maxlength="255" value=<?php echo "'".tirnak(htmlentities($_SESSION['kullaniciadi']))."'"; ?>>
						<label for="kad">Kullanıcı Adı</label>
					</div>
					<?php 
						if (isset($_SESSION['hata2'][4])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][4];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][4]);
		            	}

		            	if (isset($_SESSION['hata2'][5])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][5];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][5]);
		            	}
					?>
					<div class="inputalan">
						<input type="password" name="sifre" id="sifre" required maxlength="255">
						<label for="sifre">Şifre</label>
					</div>
					<?php 
						if (isset($_SESSION['hata2'][6])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][6];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][6]);
		            	}

		            	if (isset($_SESSION['hata2'][7])) {
		            		echo '<label id="hatamesaj">';
		            		echo $_SESSION['hata2'][7];
		            		echo '</label>';

		            		unset($_SESSION['hata2'][7]);
		            	}
					?>
					<input style="margin-top: 40px;" type="submit" name="guncelle" value="Bilgilerimi Güncelle">

				</form>
			</div>
		</div>
		
	</main>

	<?php
		include "inc/footer.inc.php";
	?>
</body>
</html>
<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	//Eğer giriş yapıldıysa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (isset($_SESSION['yetki']) and $_SESSION['yetki'] == true) {
		header("Location: index.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="resimler/favicon.ico">
	<title>Forum Dünyası | Üye Ol</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>

	<main class="girisuye">

		<form id="uye" method="POST" action="uyeolustur.php">
			<label id="baslik">Üye Ol</label>
			<?php 
				if (isset($_SESSION['hata2'][8])) {
					echo '<label style="color: red; width: 80%; text-align: center;">';
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

			<div class="inputalan">
				<input type="text" name="ad" id="ad" required maxlength="255"
				<?php 
					if (isset($_SESSION['ad'])) {
						echo " value='";
						echo $_SESSION['ad'];
						echo "'";

						unset($_SESSION['ad']);
					}
				?>
				>		
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
				<input type="email" name="mail" id="mail" required placeholder=" " maxlength="255"
				<?php 
					if (isset($_SESSION['mail'])) {
						echo " value='";
						echo $_SESSION['mail'];
						echo "'";

						unset($_SESSION['mail']);
					}
				?>
				>	
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
				<input type="text" name="kad" id="kad" required maxlength="255"
				<?php 
					if (isset($_SESSION['kad'])) {
						echo " value='";
						echo $_SESSION['kad'];
						echo "'";

						unset($_SESSION['kad']);
					}
				?>
				>	
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
				<input type="password" name="sifre" id="sifre" required maxlength="255"<?php 
					if (isset($_SESSION['sifre'])) {
						echo " value='";
						echo $_SESSION['sifre'];
						echo "'";

						unset($_SESSION['sifre']);
					}
				?>
				>	
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
			<input style="margin-top: 10px;" type="submit" name="kayit" value="Üye Ol">
			<label><a id="link" href="giris.php">Giriş Yap</a> </label>
		</form>

		<div id="metin">
			<h1 style="color: white; text-align: right; margin-bottom: 30px; font-size: 2.5rem;">Forum Dünyasına 
				<span style="color: white; text-align: right; margin-bottom: 15px; font-size: 2.5rem; border-bottom: 4px solid black; border-image: linear-gradient(90deg, #42ca88, #32dde8, #f9d30d, #ed8b30, #834bde) 1;"> Hoş Geldin!</span>	
			</h1>
			<p style="color: white; text-align: right; width: 78%;">Seni aramızda görmekten mutluluk duyarız. Yan tarafta bulunan formu doldur ve hemen eğlenceye başla!</p>
		</div>
		

	</main>

	<?php
		include "inc/footer.inc.php";
	?>
</body>
</html>
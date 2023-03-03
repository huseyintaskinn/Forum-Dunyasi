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
	<title>Forum Dünyası | Giriş Yap</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>

	<main class="girisuye">

		<div id="metin2">
			<h1 style="color: white; text-align: left; margin-bottom: 30px; font-size: 2.5rem;">Forum Dünyasına 
				<span style="color: white; text-align: left; margin-bottom: 15px; font-size: 2.5rem; border-bottom: 4px solid black; border-image: linear-gradient(90deg, #42ca88, #32dde8, #f9d30d, #ed8b30, #834bde) 1;"> Hoş Geldin!</span>	
			</h1>
			<p style="color: white; text-align: left; width: 78%;">Seni aramızda görmekten mutluluk duyarız. Hemen giriş yap ve eğlenceye başla!</p>
		</div>

		<form id="giris" method="POST" action="giriskontrol.php" style="border-radius: 0px 10px 10px 0px;">
			<label id="baslik">Giriş Yap</label>
			<?php 
				if (isset($_SESSION['hata'])) {
					echo '<label style="color: red;">';
					echo $_SESSION['hata'];
					echo '</label>';

					unset($_SESSION['hata']);
				}

				if (isset($_SESSION['mesaj'])) {
					echo '<label style="color: green;">';
					echo $_SESSION['mesaj'];
					echo '</label>';

					unset($_SESSION['mesaj']);
				}
			?>
			<div class="inputalan">
				<input type="text" name="kad" id="kad" required maxlength="255">
				<label for="kad">Kullanıcı Adı</label>
			</div>
			<div class="inputalan">
				<input type="password" name="sifre" id="sifre" required maxlength="255">
				<label for="sifre">Şifre</label>
			</div>
			<input style="margin-top: 10px;" type="submit" name="giris" value="Giriş Yap">
			<label><a id="link" href="kayitol.php">Üye Ol</a> </label>
		</form>
		

	</main>

	<?php
		include "inc/footer.inc.php";
	?>
</body>
</html>

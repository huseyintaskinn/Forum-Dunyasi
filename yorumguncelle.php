<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Eğer giriş yapılmadıysa mesaj verip giris.php sayfasına yönlendirme yapıyor
	if (!isset($_SESSION['yetki'])) {
		git("Yorum güncelleyebilmek için önce giriş yapmalı ve moderatör olmalısınız!", "giris.php");
	}

	//Eğer urlde yorumid yoksa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_GET['yorumid'])) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT id FROM yorum';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute();
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
	}

	if (!in_array($_GET['yorumid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	$sql='SELECT acilankonu.altkonuid, yorum.* FROM yorum INNER JOIN acilankonu ON acilankonu.id = konuid AND yorum.id = :id';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute(Array(":id"=>$_GET['yorumid']));

  	$bilgi=$ifade->fetch(PDO::FETCH_ASSOC);

	$vt = null;

	if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
		
	}
	else{
		header('Location: index.php');
		exit;
	}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="resimler/favicon.ico">
	<title>Forum Dünyası | Yorum Güncelle</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/forum.css">
	<link rel="stylesheet" type="text/css" href="css/forumkonu.css">
	<link rel="stylesheet" type="text/css" href="css/konuekle.css">
	<script src="ckeditor/ckeditor.js"></script>

</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>


	<main class="anasayfa">
		<div id="forumlar">
			<div class="konu">
				<?php
					 include "inc/vtbaglanti.inc.php";

					 $sql="SELECT konular.konu, altkonu FROM altkonu INNER JOIN konular ON konuid = konular.id WHERE altkonu.id = :altkonuid";
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":altkonuid"=> $bilgi["altkonuid"]));
					  $kayit = $ifade->fetch(PDO::FETCH_ASSOC);


					  echo "<div style='display: flex;'><a class='konuadi' href='forum.php?bolumid=",$bilgi["altkonuid"],"&git=Konuları+Görüntüle'>",$kayit["konu"]," / ",$kayit["altkonu"]," Bölümü</a><a class='konuekle' href='konuekle.php?bolumid=",$bilgi["altkonuid"],"'>Konu Ekle</a></div>";
					  //Bağlantıyı yok edelim...


					  $vt = null;
					
				?>



				<div class="ilksatirk" style="border-radius: 15px; border: none; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);">
					<form action="yguncelle.php" method="POST" class="konuekleform">
						<input type="hidden" name="yorumid" value=<?php echo $_GET['yorumid'];?>>
						<?php 
							if (isset($_SESSION['hata'])) {
			            		for ($i=0; $i < count($_SESSION['hata']); $i++) { 
				            		echo '<label style="color: red;">';
				            		echo $_SESSION['hata'][$i];
				            		echo '</label>';
			            		}

			            		unset($_SESSION['hata']);
			            	}
						?>
						<label for="icerik">Yorum İçeriği:</label>
						<textarea id="yazi" name="yazi" required>
							<?php echo $bilgi['yorum'];?>
						</textarea>
						<script>
			                CKEDITOR.replace( 'yazi',{
							    height: 400,
							    width: 1000,
							    customConfig: 'custom/config.js',
							    font_names: 'Arial/Arial, Helvetica, sans-serif;' +
											'Comic Sans MS/Comic Sans MS, cursive;' +
											'Courier New/Courier New, Courier, monospace;' +
											'Georgia/Georgia, serif;' +
											'Times New Roman/Times New Roman, Times, serif;' +
											'Calibri/Calibri, sans-serif'

							});
							CKEDITOR.addCss(".cke_editable{font-family:calibri;font-size: 18px;}");
			            </script>

						<input type="submit" name="yguncelle" value="Yorumu Güncelle">
					</form>
				</div>
				
				
			</div>
		</div>
		<?php
			include "inc/mod.inc.php";
		?>
	</main>

	<?php
		include "inc/footer.inc.php";
	?>
</body>
</html>
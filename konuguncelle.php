<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Eğer giriş yapılmadıysa mesaj verip giris.php sayfasına yönlendirme yapıyor
	if (!isset($_SESSION['yetki'])) {
		git("Konu ekleyebilmek için önce giriş yapmalısınız!", "giris.php");
	}

	//Eğer urlde bolumid yoksa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_GET['konuid'])) {
		header("Location: index.php");
		exit;
	}

	include "inc/vtbaglanti.inc.php";

	$sql='SELECT id FROM acilankonu';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute();
  
  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
		$idler[]=$kayit["id"];
	}

	if (!in_array($_GET['konuid'], $idler)) {
		header("Location: index.php");
		exit;
	}

	$sql='SELECT * FROM acilankonu WHERE id = :id';
  	$ifade = $vt->prepare($sql);
  	$ifade->execute(Array(":id"=>$_GET['konuid']));

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
	<?php
		include "inc/vtbaglanti.inc.php";
		$sql="SELECT altkonu FROM altkonu WHERE id = :altkonuid";
		$ifade = $vt->prepare($sql);
		$ifade->execute(Array(":altkonuid"=> $bilgi["altkonuid"]));
		$baslik = $ifade->fetch(PDO::FETCH_ASSOC);
		echo '<title>Forum Dünyası | ',$baslik["altkonu"],'</title>';
	?>
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
					<form action="guncelle.php" method="POST" class="konuekleform">
						<input type="hidden" name="konuid" value=<?php echo "'".$_GET['konuid']."'";?>>
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
						<label for="baslik">Konu Başlığı</label>
						<input type="text" name="baslik" id="konubaslik" maxlength="255" required
						<?php 
							if (isset($_SESSION['baslik'])) {
								echo " value='";
								echo $_SESSION['baslik'];
								echo "'";

								unset($_SESSION['baslik']);
 							}
 							else{
 								echo " value='";
								echo $bilgi['baslik'];
								echo "'";
 							}
						?>
						>
						<label for="icerik">İçerik</label>
						<textarea id="yazi" name="yazi" required>
							<?php 
								if (isset($_SESSION['yazi'])) {								
									echo $_SESSION['yazi'];

									unset($_SESSION['yazi']);
 								}
 								else{
									echo $bilgi['icerik'];
								}
							?>
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

						<label for="tur">Konu Türü:</label>
						<select name="tur" id="tur">
						  <option value="normal" 
						  <?php 
							  if (isset($_SESSION['tur']) and $_SESSION['tur'] == "normal") { echo " selected "; unset($_SESSION['tur']);}
							  elseif ($bilgi["tur"] == "normal") { echo "selected";} 
						  ?>

						  >Normal Konu</option>
						  <option value="bas" 
						  <?php
							  if (isset($_SESSION['tur']) and $_SESSION['tur'] == "bas") { echo " selected "; unset($_SESSION['tur']);}
							  elseif ($bilgi["tur"] == "bas") { echo "selected";} 
						  ?>
						  >Başa Tutturulan Konu</option>
						</select>

						<label for="kilit">Kilit Durumu:</label>
						<select name="kilit" id="kilit">
						  <option value="kilit" 
						  <?php
						  	if (isset($_SESSION['kilit']) and $_SESSION['kilit'] == "kilit") { echo " selected "; unset($_SESSION['kilit']);}
						    elseif ($bilgi["kilit"] == "kilit") { echo "selected";} 
						  ?>
					      >Konu Kilitli</option>
						  <option value="yok" 
						  <?php 
						    if (isset($_SESSION['kilit']) and $_SESSION['kilit'] == "yok") { echo " selected "; unset($_SESSION['kilit']);}
						  	elseif ($bilgi["kilit"] == "yok") { echo "selected";} 
						  ?>
						  >Konu Yoruma Açık</option>
						</select>

						<input type="submit" name="guncelle" value="Konuyu Güncelle">
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
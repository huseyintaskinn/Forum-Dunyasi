<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

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
	
	$vt = null;

	goruntulenmearttir($_GET['konuid']);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="resimler/favicon.ico">
	<?php
		//Açılan konun başlığı neyse titleda o yazıyor
		include "inc/vtbaglanti.inc.php";
		$sql="SELECT baslik FROM acilankonu WHERE acilankonu.id = :id";
		$ifade = $vt->prepare($sql);
		$ifade->execute(Array(":id"=> $_GET['konuid']));
		$baslik = $ifade->fetch(PDO::FETCH_ASSOC);
		echo '<title>',$baslik["baslik"],'</title>';
	?>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/forum.css">
	<link rel="stylesheet" type="text/css" href="css/forumkonu.css">
	<link rel="stylesheet" type="text/css" href="css/konuekle.css">
	<script src="ckeditor/ckeditor.js" charset="utf-8"></script>

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

					 $sql="SELECT altkonu.konuid as konu, altkonuid FROM acilankonu INNER JOIN altkonu ON altkonuid = altkonu.id WHERE acilankonu.id = :id";
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":id"=> $_GET['konuid']));
					  $gelen = $ifade->fetch(PDO::FETCH_ASSOC);

$sql="SELECT konular.konu, altkonu FROM altkonu INNER JOIN konular ON konuid = konular.id WHERE konuid = :konuid and altkonu.id = :altkonuid";
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":konuid"=> $gelen['konu'],":altkonuid"=> $gelen['altkonuid']));
					  $kayit = $ifade->fetch(PDO::FETCH_ASSOC);


					  echo "<div style='display: flex;'><a class='konuadi' href='forum.php?bolumid=",$gelen['altkonuid'],"&git=Konuları+Görüntüle'>",$kayit["konu"]," / ",$kayit["altkonu"]," Bölümü</a><a class='konuekle' href='konuekle.php?bolumid=",$gelen['altkonuid'],"'>Konu Ekle</a></div>";

					  

					  $sql="SELECT uyeid, uye.kullaniciadi, uye.rutbe, uye.resim, uye.cevrimici, baslik, icerik, tarih, kilit FROM acilankonu INNER JOIN uye ON uyeid = uye.id WHERE acilankonu.id = :id";
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":id"=> $_GET['konuid']));
					  $icerik = $ifade->fetch(PDO::FETCH_ASSOC);

					  $sql2 ="SELECT COUNT(*) + (SELECT COUNT(*) FROM yorum WHERE yorum.uyeid=:id) as adet FROM acilankonu WHERE acilankonu.uyeid = :id";
					  $ifade2 = $vt->prepare($sql2);
					  $ifade2->execute(Array(":id"=> $icerik["uyeid"]));
					  $gonderi = $ifade2->fetch(PDO::FETCH_ASSOC);

					  if ($icerik["cevrimici"] == "1") {
				  	  	$renk='#15ef15;" title="Çevrimiçi"';
				  	  }
				  	  else{
				  		$renk='#ff3636;" title="Çevrimdışı"';
				  	  }

					  echo '

							<div class="ilksatirk" style="box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);">
								<div>
									<div class="kullanicik">
										<div class="cerceve" style="min-height:120px; min-width: 120px; max-width: 120px; max-height: 120px;">
											<img src="',$icerik["resim"],'" alt="user" height="120px">
										</div>
										<span id="cevrimici3" style="background-color:',$renk,';"></span>
										<div>
											<a href="#">',htmlentities($icerik["kullaniciadi"]),'</a>
											<p>Gönderi Sayısı: ',$gonderi["adet"],'</p>
											<p>',$icerik["rutbe"],'</p>
										</div>
									</div>
								</div>
								<div id="icerik">';

								if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
									echo '
										<div style="display:flex;">
											<p id="tarih">',$icerik["tarih"],'</p>
											<a id="modbuton" style="background-color: #389aff; text-decoration: none;" href="konuguncelle.php?konuid=',$_GET['konuid'],'">Konuyu Düzenle</a>
											<a id="modbuton" style="background-color: #ff5656; text-decoration: none;" href="konusil.php?konuid=',$_GET['konuid'],'">Konuyu Sil</a>
										</div>
									';
								}

								else{
									echo '
										<div style="display:flex;">
											<p id="tarih">',$icerik["tarih"],'</p>
										</div>
									';
								}
									

							echo	'<p id="konubaslik">',htmlentities($icerik["baslik"]),'</p>
									<div id="yazi">',$icerik["icerik"],'</div>
								</div>
							</div>

					  ';

					  $sql="SELECT uye.*, yorum.* , yorum.id as yorumid FROM yorum INNER JOIN uye ON uyeid = uye.id WHERE konuid = :id ORDER BY yorum.tarih";
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":id"=> $_GET['konuid']));


					  while($yorum = $ifade->fetch(PDO::FETCH_ASSOC)){
						  $sql2 ="SELECT COUNT(*) + (SELECT COUNT(*) FROM yorum WHERE yorum.uyeid=:id) as adet FROM acilankonu WHERE acilankonu.uyeid = :id";
						  $ifade2 = $vt->prepare($sql2);
						  $ifade2->execute(Array(":id"=> $yorum["uyeid"]));
						  $gonderi = $ifade2->fetch(PDO::FETCH_ASSOC);

						  if ($yorum["cevrimici"] == "1") {
					  	  	$renk='#15ef15;" title="Çevrimiçi"';
					  	  }
					  	  else{
					  		$renk='#ff3636;" title="Çevrimdışı"';
					  	  }

						  echo '

								<div class="ilksatirk" style="border-radius: 0px; margin-top: 0px; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);">
									<div>
										<div class="kullanicik">
											<div class="cerceve" style="min-height:120px; min-width: 120px; max-width: 120px; max-height: 120px;">
												<img src="',$yorum["resim"],'" alt="user" height="120px">
											</div>
											<span id="cevrimici3" style="background-color:',$renk,';"></span>
											<div>
												<a href="#">',htmlentities($yorum["kullaniciadi"]),'</a>
												<p>Gönderi Sayısı: ',$gonderi["adet"],'</p>
												<p>',$yorum["rutbe"],'</p>
											</div>
										</div>
									</div>
									<div id="icerik">';

									if (isset($_SESSION["rutbe"]) and ($_SESSION['rutbe'] == 'Moderatör' or $_SESSION['rutbe'] == 'Admin')) {
										echo '
											<div style="display:flex;">
												<p id="tarih">',$yorum["tarih"],'</p>
												<a id="modbuton" style="background-color: #389aff; text-decoration: none;" href="yorumguncelle.php?yorumid=',$yorum['yorumid'],'">Yorumu Düzenle</a>
												<a id="modbuton" style="background-color: #ff5656; text-decoration: none;" href="yorumsil.php?yorumid=',$yorum['yorumid'],'">Yorumu Sil</a>
											</div>
										';
									}

									else{
										echo '
											<div style="display:flex;">
												<p id="tarih">',$yorum["tarih"],'</p>
											</div>
										';
									}
										

								echo '<div id="yazi" style="min-height: 300px;">',$yorum["yorum"],'</div>
									</div>
								</div>

						  ';
					  }

					  //Bağlantıyı yok edelim...
					  $vt = null;
					
				?>
				
				<div id="son" style="background-color: #cdcdcd;box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);"></div>
			</div>
			
			<?php 

				if (isset($_SESSION['yetki']) and $_SESSION['yetki'] == true and $icerik["kilit"] == "yok") {

			?>

			<div class="ilksatirk" style="margin-top:25px; border-radius: 15px; border: none; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2);">
				<form action="yorumekle.php" method="POST" class="konuekleform">
					<input type="hidden" name="konuid" value=<?php echo $_GET['konuid'];?>>
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
					<label for="icerik">Yorum Yaz</label>
					<textarea id="yorum" name="yorum" required></textarea>
					<script>
		                CKEDITOR.replace( 'yorum',{
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
					<input type="submit" name="yukle" value="Yorumu Paylaş" style="margin-bottom: 15px;">
				</form>
			</div>

			<?php 
					
				}

				elseif (!isset($_SESSION["yetki"])) {
					echo '<div class="ilksatirk" style="margin-top:25px; border-radius: 15px; border: none; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2); height: 200px; flex-direction: column; justify-content: center;align-items: center;">';
					echo '<h1>Yorum yapabilmek için önce giriş yapmalısınız!</h1><br>';
					echo '<a id="link" href="giris.php">Giriş Yap</a>';
					echo '</div>';		
				}
				else{
					echo '<div class="ilksatirk" style="margin-top:25px; border-radius: 15px; border: none; box-shadow: 0px 0px 9px 0px rgba(0,0,0,0.2); height: 200px; align-items: center;">';
					echo '<h1>Bu konu yorum yapmaya kapatılmıştır!</h1>';
					echo '</div>';
				}

			?>

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
<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
	include "inc/fonksiyonlar.inc.php";

	//Eğer urlde ara yoksa sayfayı açmadan ana sayfaya yönlendirme yapıyor
	if (!isset($_GET['ara'])) {
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
	<?php
		echo '<title>Arama Sonuçları: ',$_GET['ara'],'</title>';
	?>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/forum.css">
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

				  	  $konular=array();

				  	  $kelime="%".$_GET['ara']."%";

					  $sql='SELECT (SELECT uye.kullaniciadi FROM uye WHERE uye.id = uyeid) AS kullaniciAdi, (SELECT uye.resim FROM uye WHERE uye.id = uyeid) AS resim, id, baslik, tarih, goruntulenme FROM acilankonu WHERE icerik LIKE :bul or baslik LIKE :bul ORDER BY sonyorumtarih DESC, tarih DESC';
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":bul"=> $kelime));
					  
					  while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
					  	$sql2='SELECT (SELECT COUNT(*) as adet FROM yorum WHERE konuid = :id GROUP BY konuid) AS adet, (SELECT uye.kullaniciadi FROM uye WHERE uye.id = uyeid) AS kullaniciadi, (SELECT uye.resim FROM uye WHERE uye.id = uyeid) AS resim, tarih FROM yorum WHERE konuid = :id ORDER BY tarih DESC LIMIT 1';
					    $ifade2 = $vt->prepare($sql2);
					    $ifade2->execute(Array(":id"=> $kayit['id']));
					    $yorum = $ifade2->fetch(PDO::FETCH_ASSOC);

					    if ($yorum == false) {
					    	$yorum["adet"] = 0;
					    	$yorum["kullaniciadi"] = null;
					    	$yorum["resim"] = null;
					    	$yorum["tarih"] = null;
					    }

				  		$konular[]=array($kayit["kullaniciAdi"],$kayit["id"],$kayit["baslik"],$kayit["tarih"], $kayit["resim"], $yorum["adet"], $yorum["kullaniciadi"], $yorum["resim"], $yorum["tarih"], $kayit["goruntulenme"]);
				  	  }

				  	  $sql='SELECT (SELECT uye.kullaniciadi FROM uye WHERE uye.id = acilankonu.uyeid) AS kullaniciAdi, (SELECT uye.resim FROM uye WHERE uye.id = acilankonu.uyeid) AS resim, acilankonu.id, acilankonu.baslik, acilankonu.tarih, acilankonu.goruntulenme FROM yorum INNER JOIN acilankonu ON acilankonu.id = konuid AND yorum LIKE :bul ORDER BY sonyorumtarih DESC, tarih DESC';
					  $ifade = $vt->prepare($sql);
					  $ifade->execute(Array(":bul"=> $kelime));
					  
					  while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
					  	
					  	$var=0;

					  	for ($i=0; $i < count($konular); $i++) { 
					  		if ($kayit["id"] = $konular[$i][1]) {
					  			$var=1;
					  			break;
					  		}

					  	}

					    if ($var==0) {

					    	$sql2='SELECT (SELECT COUNT(*) as adet FROM yorum WHERE konuid = :id GROUP BY konuid) AS adet, (SELECT uye.kullaniciadi FROM uye WHERE uye.id = uyeid) AS kullaniciadi, (SELECT uye.resim FROM uye WHERE uye.id = uyeid) AS resim, tarih FROM yorum WHERE konuid = :id ORDER BY tarih DESC LIMIT 1';
						    $ifade2 = $vt->prepare($sql2);
						    $ifade2->execute(Array(":id"=> $kayit['id']));
						    $yorum = $ifade2->fetch(PDO::FETCH_ASSOC);

						    if ($yorum == false) {
						    	$yorum["adet"] = 0;
						    	$yorum["kullaniciadi"] = null;
						    	$yorum["resim"] = null;
						    	$yorum["tarih"] = null;
						    }
					    	$konular[]=array($kayit["kullaniciAdi"],$kayit["id"],$kayit["baslik"],$kayit["tarih"], $kayit["resim"], $yorum["adet"], $yorum["kullaniciadi"], $yorum["resim"], $yorum["tarih"], $kayit["goruntulenme"]);
					    }
				  		
				  	  }

					  //Bağlantıyı yok edelim...
					  $vt = null;
					
				?>
				
				<p class="baslik">Arama Sonucu: <?php echo htmlentities($_GET['ara']); ?></p>
				
				<div class="ilksatir">
					<p class="altbaslik">Konu Başlığı</p>
					<div id="satirsonu">
						<span class="cizgi"></span>
						<p style="width: 150px; text-align: center;">Konuyu Yazan</p>
						<span class="cizgi"></span>
						<p style="width: 100px; text-align: center;">Cevaplar</p>
						<span class="cizgi"></span>
						<p style="width: 100px; text-align: center;">Görüntüleme</p>
						<span class="cizgi"></span>
						<p style="width: 150px; text-align: center;">Son Cevaplayan</p>
					</div>
				</div>

					<?php  

					  if ($konular==null) {
					  	echo '
						<div class="satirbos">
							<a class="altbaslik" style="text-align:center; color:grey;">Aradığınız ifadeyle eşleşen bir kayıt bulunamadı.</a>
						</div>';

					  }

					  for ($i=0; $i < count($konular); $i++) { 
					  echo '
				<div class="satir">
					<a class="altbaslik" href="konu.php?konuid=',$konular[$i][1],'" title="',$konular[$i][2],'">',htmlentities(kisalt($konular[$i][2])),'</a>
					<div id="satirsonu">
						<span class="cizgi"></span>
						<div class="kullanici">
							<div>
								<div class="cerceve" style="min-height:50px; min-width: 50px;max-width: 50px; max-height: 50px;">
									<img src="',$konular[$i][4],'" alt="user" height="50px">
								</div>
								<a href="#" style="font-weight: bold;">',htmlentities($konular[$i][0]),'</a>
								<p>',$konular[$i][3],'</p>
							</div>
						</div>
						<span class="cizgi"></span>
						<p class="sayi">',$konular[$i][5],'</p>
						<span class="cizgi"></span>
						<p class="sayi">',$konular[$i][9],'</p>
						<span class="cizgi"></span>
						<div class="kullanici">';

						if ($konular[$i][5] != 0) {
							echo '
								<div>
									<div class="cerceve" style="min-height:50px; min-width: 50px;max-width: 50px; max-height: 50px;">
										<img src="',$konular[$i][7],'" alt="user" height="50px">
									</div>
									<a href="#" style="font-weight: bold;">',htmlentities($konular[$i][6]),'</a>
									<p>',$konular[$i][8],'</p>
								</div>
							';
						}
							
					echo '</div>
					</div>
				</div>

					  ';	
					  }

					  
					
				?>

		
				<div id="son" style="background-color: #cdcdcd;"></div>
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
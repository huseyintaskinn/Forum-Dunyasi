<?php 
	
	include "inc/vtbaglanti.inc.php";

	$sql ="SELECT COUNT(*) as konuadet, COUNT(*) + (SELECT COUNT(*) FROM yorum) as mesajadet, (SELECT COUNT(*) FROM uye) as uyeadet, (SELECT SUM(cevrimici) FROM uye) as cevrimici FROM acilankonu";
  	$ifade = $vt->prepare($sql);
    $ifade->execute();
    $adet = $ifade->fetch(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<footer>
	<div style="width: 30%;">
		<a class="logo" href="index.php">
			<img src="resimler/logo.png" alt="logo" height="60px">
			<p>FORUM <span style="font-weight: 200; letter-spacing: 5px; font-size: 35px;">DÜNYASI</span></p>
		</a>
		<p style="font-size: 0.8rem; margin-top:25px; width: 80%;">Bu site 2021-2022 eğitim yılı güz döneminde Web Tabanlı Programlama dersi için Hüseyin Taşkın tarafından hazırlanmıştır.</p>
	</div>

	<div style="width: 15%;">
		<p style="font-weight: bold;">Kısayollar</p>
		<div id="kısayol">
			<a href="forum.php?bolumid=1">Yazılım Bölümü</a>
			<a href="forum.php?bolumid=2">Donanım Bölümü</a>
			<a href="forum.php?bolumid=3">Sohbet Bölümü</a>
			<a href="forum.php?bolumid=4">Oyun Bölümü</a>
			<a href="forum.php?bolumid=5">Televizyon Bölümü</a>
		</div>
	</div>

	<div style="width: 20%;">
		<p style="font-weight: bold;">Forum İstatistikleri</p>
		<div id="istatistik">
			<div id="satir">
				<p>Konular: </p>
				<p><?php echo $adet["konuadet"] ?></p>
			</div>
			<div id="satir">
				<p>Mesajlar: </p>
				<p><?php echo $adet["mesajadet"] ?></p>
			</div>
			<div id="satir">
				<p>Üyeler: </p>
				<p><?php echo $adet["uyeadet"] ?></p>
			</div>
			<div id="satir">
				<p>Çevrimiçi Üye: </p>
				<p><?php echo $adet["cevrimici"] ?></p>
			</div>
		</div>
	</div>

	<div style="width: 25%;">
		<p style="font-weight: bold; margin-bottom: 15px;">Sosyal Medya Hesaplarımız</p>
		<div style="display: flex; justify-content: flex-start; align-self: center; flex-wrap: wrap;"> 
			<a href="https://tr-tr.facebook.com/" class="fa fa-facebook"></a>
			<a href="https://twitter.com/?lang=tr" class="fa fa-twitter"></a>
			<a href="https://accounts.google.com/signin" class="fa fa-google"></a>
			<a href="https://www.youtube.com/?hl=TR" class="fa fa-youtube"></a>
			<a href="https://www.instagram.com/" class="fa fa-instagram"></a>
		</div>
	</div>
	
</footer>

<?php 
	$vt = null;
?>
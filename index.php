<?php 
	//Oturum bilgilerine ulaşmak için kullandım
	session_start();
	//error_reporting(0);
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
</head>
<body>
	<?php 
		include "inc/nav.inc.php";
	?>

	<main class="anasayfa">
		<div id="forumlar">

			<?php 
                  $forumlar=array();  
                  $adet=array();  
 
				  include "inc/vtbaglanti.inc.php";

				  $sql ="SELECT altkonuid, COUNT(*) as adet FROM acilankonu GROUP BY acilankonu.altkonuid";
				  $ifade = $vt->prepare($sql);
				  $ifade->execute();

				  while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {

				  		$sql2 ="SELECT COUNT(*) as adet FROM yorum INNER JOIN acilankonu ON konuid =acilankonu.id AND acilankonu.altkonuid = :altkonuid GROUP BY acilankonu.altkonuid";
				  		$ifade2 = $vt->prepare($sql2);
				  		$ifade2->execute(Array(":altkonuid"=> $kayit["altkonuid"]));
				  		$yadet = $ifade2->fetch(PDO::FETCH_ASSOC);

				  		$adet[$kayit["altkonuid"]]=$kayit["adet"]+$yadet["adet"];

				  }
				  

				  $sql ="SELECT konuid, altkonu.id as bolumid, konular.konu as konu, altkonu FROM altkonu INNER JOIN konular ON konuid = konular.id";
				  $ifade = $vt->prepare($sql);
				  $ifade->execute();

				  while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {

				  		$forumlar[$kayit["konu"]][]=array($kayit["altkonu"],$kayit["konuid"],$kayit["bolumid"]);
				  		if (!array_key_exists($kayit["bolumid"], $adet)) {
				  			$adet[$kayit["bolumid"]]=0;
				  		}

				  }

				  //Bağlantıyı yok edelim...
				  $vt = null;

				  foreach ($forumlar as $konu => $bilgi) {



				  	echo'
				  			<div class="konu">
								<p class="baslik">',$konu,'</p>
								<div class="menu">';

					for ($i=0; $i < count($forumlar[$konu]); $i++) { 
						echo '
							<div class="satir">
								<a class="altbaslik" href="forum.php?bolumid=',$bilgi[$i][2],'&git=Konuları+Görüntüle">',$bilgi[$i][0],'</a>
								<div id="satirsonu">
									<span class="cizgi"></span>
									<div id="gonderi">
										<p style="font-weight: bold;">Gönderi Sayısı</p>
										<p>',$adet[$bilgi[$i][2]],'</p>
									</div>
									<span class="cizgi"></span>
									<form action="forum.php" method="GET">
										<input type="hidden" name="bolumid" id="bolumid" value="',$bilgi[$i][2],'">
										<input type="submit" name="git" class="git" value="Konuları Görüntüle">
									</form>
								</div>
							</div>

						';
					}
					
					echo '<div id="son"></div>';

					echo			'</div>
							</div>

				  		';
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
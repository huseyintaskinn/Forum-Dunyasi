<?php 	
	include "inc/vtbaglanti.inc.php";

	$sql ="UPDATE uye SET cevrimici = 0 WHERE TIMESTAMPDIFF(SECOND, son, CURRENT_TIMESTAMP) > 3600";
  	$ifade = $vt->prepare($sql);
    $ifade->execute();

	if (isset($_SESSION['yetki'])) {
		$sql ="SELECT id FROM uye WHERE id = :id AND cevrimici = 0";
	  	$ifade = $vt->prepare($sql);
	    $ifade->execute(Array("id"=>$_SESSION['id']));
	    $kayit = $ifade->fetch(PDO::FETCH_ASSOC);

	    if ($kayit != false) {
	    	session_destroy();
	    	$_SESSION = null;
	    }
	}

    $vt = null;
?>

	<nav>
		<a class="logo" href="index.php">
			<img src="resimler/logo.png" alt="logo" height="60px">
			<p>FORUM <span style="font-weight: 200; letter-spacing: 5px; font-size: 35px;">DÜNYASI</span></p>
		</a>
		<form id="ara" method="GET" action="ara.php" style="display: flex;">
			<input id="arainput" name="ara" type="text" placeholder="Ara..." required minlength="3" <?php if(isset($_GET["ara"])){ echo 'value="'.tirnak(htmlentities($_GET["ara"])).'"';} ?>>
		</form>
		<div>
			<a class="buton" href="index.php">Ana Sayfa</a>
			<a class="buton" href="yenikonular.php">En Yeni Konular</a>
			<a class="buton" href="populerkonular.php">Popüler Konular</a>
			<div class="acilirmenu">
				<?php 
					//Eğer giriş yapılmışsa kullanıcı adı ve açılır menüde çıkış yap yazısı html'e eklenecek
					if (isset($_SESSION['yetki']) and $_SESSION['yetki'] == true) {
						echo '<a class="buton" id="ac" href="profil.php">',htmlentities($_SESSION['kullaniciadi']),'</a>
								<div id="menu">
									<a class="buton" href="profil.php">Profilim</a>
									<a class="buton" href="cikis.php">Çıkış Yap</a>
								</div>';
					}
					//Eğer giriş yapılmamışsa giriş yap ve açılır menüde giriş yap ve üye ol yazıları html'e eklenecek
					else{
						echo ('<a class="buton" id="ac" href="giris.php">Giriş Yap</a>
								<div id="menu">
									<a class="buton" href="giris.php">Giriş Yap</a>
									<a class="buton" href="kayitol.php">Üye Ol</a>
								</div>');
					}
				?>
			</div>
		</div>
	</nav>
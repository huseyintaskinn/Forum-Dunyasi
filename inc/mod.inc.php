    <div id="yoneticiler">
		<div class="konu">
			<p class="baslik2">Yöneticiler</p>

			<?php 

				include "inc/vtbaglanti.inc.php";

				$sql='SELECT id FROM uye WHERE rutbe = "Moderatör" OR rutbe = "Admin"';
			  	$ifade = $vt->prepare($sql);
			  	$ifade->execute();
			  
			  	while ($kayit = $ifade->fetch(PDO::FETCH_ASSOC)) {
					$sql2='SELECT kullaniciadi, resim, cevrimici FROM uye WHERE id = :id';
				  	$ifade2 = $vt->prepare($sql2);
				  	$ifade2->execute(Array(":id"=> $kayit['id']));
				  	$kayit2 = $ifade2->fetch(PDO::FETCH_ASSOC);

				  	if ($kayit2["cevrimici"] == "1") {
				  		$renk='#15ef15;" title="Çevrimiçi"';
				  	}
				  	else{
				  		$renk='#ff3636;" title="Çevrimdışı"';
				  	}

				  	echo '
				  		<div class="mod">
				  			<div class="cerceve" style="min-height:50px; min-width: 50px; max-width: 50px; max-height: 50px;">
								<img src="',$kayit2["resim"],'" alt="user" height="50px">
							</div>
							<span id="cevrimici" style="background-color:',$renk,' ;"></span>
							<p class="lakap">',htmlentities($kayit2["kullaniciadi"]),'</p>
						</div>
				  	';
				}
				
				$vt = null;
			?>
			<div id="son">

			</div>
		</div>
	</div>
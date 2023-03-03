<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Form</title>

	<style>
		#form{
			display: flex;
			flex-direction: column;
			width: 300px;
		}

		#form > * {
			margin: 5px;
		}
	</style>
</head>
<body>

	<form id="form" method="POST" action="#">

		<label for="ad">Adınızı giriniz:</label>
		<input id="ad" type="text" name="ad" required>

		<label for="sayi1">Sayı giriniz:</label>
		<input id="sayi1" type="number" name="sayi1" required>

		<label for="sayi2">Sayı giriniz:</label>
		<input id="sayi2" type="number" name="sayi2" required>

		<label>İşlem seçiniz:</label>
		<div>
			<input type="radio" id="topla" name="islem" value="topla" required>
			<label for="topla">Topla</label>
		</div>
		
		<div>
			<input type="radio" id="cikar" name="islem" value="cikar" required>
			<label for="cikar">Çıkar</label>
		</div>
		
		<div>
			<input type="radio" id="carp" name="islem" value="carp" required>
			<label for="carp">Çarp</label>
		</div>
		
		<div>
			<input type="radio" id="bol" name="islem" value="bol" required>
			<label for="bol">Böl</label>
		</div>

		<label for="renk">Renk seçiniz:</label>

	    <select name="renk" id="renk" required>
	      <option value="kirmizi">Kırmızı</option>
	      <option value="mavi">Mavi</option>
	      <option value="sari">Sarı</option>
	      <option value="yesil">Yeşil</option>
	    </select>
		
		<input type="submit" name="kaydet" value="Gönder">

	</form>

	<div>
		<?php 
			if (isset($_POST['kaydet'])) {

				$hata=0;

				switch ($_POST['islem']) {
					case 'topla':
						$sonuc = $_POST['sayi1'] + $_POST['sayi2'];
						$isaret = " + ";
						break;
					case 'cikar':
						$sonuc = $_POST['sayi1'] - $_POST['sayi2'];
						$isaret = " - ";
						break;
					case 'carp':
						$sonuc = $_POST['sayi1'] * $_POST['sayi2'];
						$isaret = " X ";
						break;
					case 'bol':
						$sonuc = $_POST['sayi1'] / $_POST['sayi2'];
						$isaret = " / ";
						break;
					
					default:
						$hata=1;
						break;
				}

				switch ($_POST['renk']) {
					case 'kirmizi':
						$renk="red";
						break;
					case 'mavi':
						$renk="blue";
						break;
					case 'sari':
						$renk="yellow";
						break;
					case 'yesil':
						$renk="green";
						break;
					
					default:
						$hata=1;
						break;
				}


				if ($hata == 0) {
					echo "<p style='color: ",$renk,";'> Merhaba ",$_POST['ad'],"!</p>";
					echo "<p style='color: ",$renk,";'> Yaptırdığın işlemin sonucu aşağıda!</p>";
					echo "<p style='color: ",$renk,";'>", $_POST['sayi1'], $isaret, $_POST['sayi2'], " = ", $sonuc, "</p>";
				}
				
			}
		?>
	</div>

</body>
</html>
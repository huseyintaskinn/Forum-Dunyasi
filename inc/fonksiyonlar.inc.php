<?php
function git($mesaj, $adres){
	//JavaScript Mesaj Kutusu
	echo ("<script LANGUAGE='JavaScript'>
			window.alert('$mesaj');
			window.location.href='$adres';
			</script>");
	//JavaScript çalışmazsa mesaj + link
	echo "<p>Lütfen sitenin daha düzgün çalışabilmesi için JavaScript aktif bir şekilde kullanın.</p>";
	echo "<p>$mesaj</p>";
	echo "<a href='$adres'>Git</a>";

}

//Konu başlığı çok uzun olduğunda başlığı kısaltıp sonuna 3 nokta ekliyor forum.phpdeki satır yüksekliği bozulmasın diye
function kisalt($kelime){
	$kelime2 = substr($kelime, 0, 90);
	if ($kelime != $kelime2) {
		$kelime2.="...";
	}

	return $kelime2;
}

//Herhangi bir konuya girildiğinde o konunun veri tabanında bulunan goruntulenme sütunundaki değerini alıp 1 fazlasıyla güncelliyor
function goruntulenmearttir($konuid){
	include "inc/vtbaglanti.inc.php";

	$sql = "SELECT goruntulenme FROM `acilankonu` WHERE id = :konuid";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":konuid"=>$konuid));
	$sayi = $ifade->fetch(PDO::FETCH_ASSOC);

	$sayi["goruntulenme"]+=1;

	$sql = "UPDATE acilankonu SET goruntulenme = :sayi WHERE id = :konuid";
	$ifade = $vt->prepare($sql);
	$ifade->execute(Array(":sayi"=>$sayi["goruntulenme"], ":konuid"=>$konuid));
	
	$vt = null;  
}

//Konuları gelen yanıt sayısı + (görüntülenme sayısı / 100) sonucuna göre sıralıyor
function populersirala($konular){
	for ($j=0; $j < count($konular)-1; $j++) { 	
		for ($i=0; $i < count($konular)-1; $i++) { 
			if ($konular[$i][5]+($konular[$i][9]/100) < $konular[$i+1][5]+($konular[$i+1][9]/100)) {
				$eski=$konular[$i];
				$konular[$i]=$konular[$i+1];
				$konular[$i+1]=$eski;
			}
		}
	}

	return $konular;
}

//Kullanıcı her konu açtığında veya yorum eklediğinde bu fonksiyon çalıştırılacak
//Gönderi sayısını view tablosuyla hesaplayıp gönderi sayısına göre rütbeyi güncelliyor
//Moderatör ve Adminse gönderi artsa bile rütbede değişiklik olmayacak
function rutbeguncelle($id){

	include "inc/vtbaglanti.inc.php";

	$sql2 ="SELECT (SELECT rutbe FROM uye WHERE uye.id=:id) as rutbe,COUNT(*) + (SELECT COUNT(*) FROM yorum WHERE yorum.uyeid=:id) as adet FROM acilankonu WHERE acilankonu.uyeid = :id";
    $ifade2 = $vt->prepare($sql2);
    $ifade2->execute(Array(":id"=> $id));
    $gonderi = $ifade2->fetch(PDO::FETCH_ASSOC);

    if ($gonderi["rutbe"] == "Admin") {
    	$rutbe="Admin";
    }
    elseif ($gonderi["rutbe"] == "Moderatör") {
    	$rutbe="Moderatör";
    }
    elseif ($gonderi["adet"] <= 15) {
    	$rutbe="Yeni Üye";
    }
    elseif ($gonderi["adet"] <= 50) {
    	$rutbe="Aktif Forumcu";
    }
    elseif ($gonderi["adet"] <= 100) {
    	$rutbe="Uzman Forumcu";
    }
    elseif ($gonderi["adet"] <= 500) {
    	$rutbe="Tecrübeli Forumcu";
    }
    else{
    	$rutbe="Forumların Efendisi";
    }

    $sql2 ="UPDATE uye SET rutbe=:rutbe WHERE id = :id";
    $ifade2 = $vt->prepare($sql2);
    $ifade2->execute(Array(":rutbe"=> $rutbe,":id"=> $id));
	
	$vt = null;  
}

//Gelen idye sahip kullanıcının rütbesini Moderatör olarak güncelliyor
function modekle($id){

	include "inc/vtbaglanti.inc.php";

    $sql2 ="UPDATE uye SET rutbe=:rutbe WHERE id = :id";
    $ifade2 = $vt->prepare($sql2);
    $ifade2->execute(Array(":rutbe"=> "Moderatör",":id"=> $id));
	
	$vt = null;  
}

//Gelen idye sahip kullanıcının rütbesini Yeni Üye olarak güncelliyor 
//Sonrasında gönderi sayısına göre doğru rütbeyi kullanıcıya veriyor
function modcikar($id){

	include "inc/vtbaglanti.inc.php";

    $sql2 ="UPDATE uye SET rutbe=:rutbe WHERE id = :id";
    $ifade2 = $vt->prepare($sql2);
    $ifade2->execute(Array(":rutbe"=> "Yeni Üye",":id"=> $id));
	
	$vt = null;

	rutbeguncelle($id);
}

//Üyelerin veri tabanındaki son aktivite zamanını güncelliyor
//Konu eklediğinde veya yorum yazdığına bu fonksiyon çağırılacak
function sonaktivite($id){

	include "inc/vtbaglanti.inc.php";

	$sql ="UPDATE uye SET son = CURRENT_TIMESTAMP WHERE id = :id";
    $ifade = $vt->prepare($sql);
    $ifade->execute(Array(":id"=> $id));
    
	$vt = null;  
}

//Area boş bırakısa bile ck editör içine html etiketleri eklediği için boş görünmüyordu
//Bu fonksiyonda gelen ifadeden html etiketlerini ve boşluk karakterini sildirdim
function areatemizle($metin){
	$metin=trim($metin);

	$sil=array("<p>", "</p>", "&nbsp;", "<h1>", "</h1>", "<h2>", "</h2>", "<h3>", "</h3>", "<h4>", "</h4>", "<h5>", "</h5>", "<h6>", "</h6>", "<span>", "</span>", "<a>", "<a>", "<strong>", "</strong>", "<s>", "</s>", "<em>", "</em>", "<u>", "</u>", "<sub>", "</sub>", "<sup>", "</sup>", "<img>", "</img>", "<ol>", "</ol>", "<ul>", "</ul>", "<li>", "</li>", "<sub>", "</sub>", "<sup>", "</sup>");

	for ($i=0; $i < count($sil); $i++) { 
		$dizi=explode($sil[$i], $metin);
		$metin=trim(implode("", $dizi));
		if ($metin=="") {
			break;
		}
	}

	return $metin;
}

//Tırnak işaretlerini yazdırırken karışıklık olamaması adına sayısal karşıığıyla değiştirdim
function tirnak($metin){
	$dizi=explode('"', $metin);
	$metin=implode('&#34;', $dizi);

	$dizi=explode("'", $metin);
	$metin=implode("&#39;", $dizi);

	return $metin;
}




?>
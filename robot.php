<?php
	include_once('config/Database.php');

	$produk = false;
	$str = '';

	$database = new Database();
	$connect = $database->koneksi();

	$token = '< MASUKAN TOKEN BOT DISINI >';

    $url = 'https://api.telegram.org/bot'.$token;

	$telegram = json_decode(file_get_contents("php://input"), TRUE);
	
	$chatId = $telegram['message']['chat']['id'];
	$message = $telegram['message']['text'];
	
	$kata = explode(' ', $message);
	$perintah = $kata[0]; //harga atau perintah
	$keyword = $kata[1]; //telur atau kata produk lainnya

	if($perintah == 'produk'){
		$stmt = $connect->prepare("SELECT * FROM produk");
		$stmt->execute();
		$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($produk AS $row){
			$str .= $row['produk'] . "\n";
		}
	}
	else if($perintah == 'harga'){
		
		if($keyword){
			$stmt = $connect->prepare("SELECT * FROM produk WHERE keyword LIKE :keyword");
			$stmt->execute(['keyword' => '%'.$keyword.'%']);
			$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}else{
			$stmt = $connect->prepare("SELECT * FROM produk");
			$stmt->execute();
			$produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		if($produk){
			foreach($produk AS $row){
				$str .= $row['produk'] . "\n";

				$arrHarga = json_decode($row['harga'], true);
				foreach($arrHarga AS $ukuran => $harga){
					$str .= $ukuran . ' = ' . number_format($harga) . "\n";
				}

				$str .= "\n";
			}
		}else{
			$str .= 'Produk yang anda cari tidak di temukan, silahkan cari produk lainnya';
		}
	}

	$reply = urlencode($str);
	file_get_contents($url . '/sendmessage?chat_id=' . $chatId . '&text=' . $reply);
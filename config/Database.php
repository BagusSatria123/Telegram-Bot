<?php

	class Database{
		
		private $host = 'localhost';
		private $user = 'tokokel';
		private $password = '2Onic06!';
		private $database = 'tokokelontong';
		
		public function koneksi(){
		
				try{
					$conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
                    return $conn;
				}
				catch(PDOException $e){
					echo "Koneksi gagal : " . $e->getMessage();
				}
		
		}
	
	}

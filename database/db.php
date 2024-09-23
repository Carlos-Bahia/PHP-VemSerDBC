<?php

$host = 'localhost';
$port = 3307;
$db = 'curriculodb';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

   die('Falha na conexão com o banco de dados: ' . $e->getMessage());
}

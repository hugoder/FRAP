<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


try{
    $db = new PDO('mysql:host=localhost;dbname=projetfrap','root','');
    $db->exec("set names utf8");
}catch(PDOException $exception){
    echo "Erreur de connexion : " . $exception->getMessage();
}

$sql = "SELECT * FROM residences";

// On prépare la requête
$query = $db->prepare($sql);

// On exécute la requête
$query->execute();

while($row = $query->fetch(PDO::FETCH_ASSOC)){
    extract($row);

    $point = [
        "id" => $id,
        "nom" => $nom,
        "latitude" => $latitude,
        "longitude" => $longitude,
        
    ];

    $tableauFiche['fiche'][] = $point;
}

// On encode en json et on envoie
echo json_encode($tableauFiche);
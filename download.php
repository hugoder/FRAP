<?php
$db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
$sql = "SELECT * FROM residences WHERE id = 9";

$query = $db->prepare($sql);

// On exécute la requête
$query->execute();
$data = $query -> fetchAll();

//Build the path 
//$file = "fiches/" .  $data[0]['fiche'];

$filename= $data[0]["path"]; // YOUR File name retrive from database
        $file= "fiches/".$filename; // YOUR Root path for pdf folder storage
        $len = filesize($file); // Calculate File Size
        ob_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public"); 
        header("Content-Description: File Transfer");
        header("Content-Type:application/pdf"); // Send type of file
        $header="Content-Disposition: attachment; filename=$filename;"; // Send File Name
        header($header );
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len); // Send File Size
        @readfile($file);
        exit;
/*
header("Content-type:application/pdf");
//It will be called downloaded.pdf
header("Content-Disposition:attachment;filename='$file'");

readfile($file);  
*/
?>
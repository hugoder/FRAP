<?php
ob_start();

$db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');


if(isset($_GET['id'])){
        $id = $_GET['id'];
        $stat = $db->prepare("select * from residences where id = ?");
        $stat -> bindParam(1, $id);
        $stat->execute();
        $data = $stat -> fetch();

        $file= 'fiches/' .$data["path"];
        header("Content-Description: File Transfer");
        header('Content-disposition: attachment; filename="'.basename($file).'"');
        header("Content-Type:application/pdf");
        header("Expires: 0");

        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public"); 
        header("Pragma: public");
        header("Content-Length: ".filesize($file));
        readfile($file);
        exit;

}

ob_end_flush();
?>
<?php 

session_start();

if (isset($_POST['submit']))
{
    $id = $_POST['id'];
    $password = $_POST['pass'];
    $address = $_POST['address'];



    $db = new PDO('mysql:host=localhost;dbname=frap','root','');

    $sql = "INSERT INTO intervention(id,password,address)
            VALUES (NULL, '$password' , '$address'); ";
    $result = $db->prepare($sql);
    $result->execute();

    echo $sql;



    

}

?>
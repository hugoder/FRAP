<?php 

session_start();

if (isset($_POST['submit']))
{
    $num = $_POST['num'];
    $password = $_POST['pass'];
    $address = $_POST['address'];
    $code = $_POST['code'];
    $ville = $_POST['ville'];



    $db = new PDO('mysql:host=localhost;dbname=projetfrap','root','');

    $sql = "INSERT INTO intervention(id,num,password,adress,code,ville)
            VALUES (NULL,'$num', '$password' , '$address','$code' , '$ville'); ";
    $result = $db->prepare($sql);
    $result->execute();

    header("Location:admin.php");

   



    

}

?>
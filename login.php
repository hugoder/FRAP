<?php 

session_start();

if (isset($_POST['submit']))
{
    $id = $_POST['id'];
    $password = $_POST['pass'];


    $db = new PDO('mysql:host=localhost;dbname=frap','root','');

    $sql = "SELECT * FROM intervention where id ='$id' ";
    $result = $db->prepare($sql);
    $result->execute();

    if($result->rowCount() > 0)
    {
        $data = $result->fetchAll();
        if($password == $data[0]["password"])
        {
            echo "Connexion effectué";
            $_SESSION["id"] = $id;
        }
        else
        {
            echo "Mauvais MDP";         
            
        }
    }
    else
    {
        echo "Cette intervention n'est pas dans le système, veuillez vous connecter pour en créer une !";
    }
}

?>
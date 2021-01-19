<?php 

session_start();

if (isset($_POST['submit']))
{
    $mail = $_POST['mail'];
    $password = $_POST['pass'];


    $db = new PDO('mysql:host=localhost;dbname=projetfrap','root','');

    $sql = "SELECT * FROM admin where mail ='$mail' ";
    $result = $db->prepare($sql);
    $result->execute();

    if($result->rowCount() > 0)
    {
        $data = $result->fetchAll();
        if($password == $data[0]["password"])
        {
            echo "Connexion effectué";
            $_SESSION["statut"] = "admin";
            $_SESSION["mail"] = $mail; 
            header("Location:admin.php"); 
            
        }
        else
        {
            echo "Mauvais MDP";         
            
        }
    }
    else
    {
        echo "Vous n'etes pas admin, veuillez en référer avec votre supérieur";
    }
}

?>
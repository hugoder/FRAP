<?php 

if (isset($_POST['submit']))
{
    $mail = $_POST['mail'];
    $password = $_POST['pass'];


    $db = new PDO('mysql:host=localhost;dbname=frap','root','');

    $sql = "SELECT * FROM admin where mail ='$mail' ";
    $result = $db->prepare($sql);
    $result->execute();

    if($result->rowCount() > 0)
    {
        $data = $result->fetchAll();
        if($password == $data[0]["password"])
        {
            echo "Connexion effectué";
            $_SESSION["mail"] = $mail;
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
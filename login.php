<?php 

session_start();

if (isset($_POST['submit']))
{
    $num = $_POST['num'];
    $password = $_POST['pass'];


    $db = new PDO('mysql:host=localhost;dbname=projetfrap','root','');

    $sql = "SELECT * FROM intervention where num ='$num' ";
    $result = $db->prepare($sql);
    $result->execute();

    if($result->rowCount() > 0)
    {
        $data = $result->fetchAll();
        if($password == $data[0]["password"])
        {
            echo "Connexion effectué";
            $_SESSION['address'] = $data[0]["adress"];
            $_SESSION['code'] = $data[0]["code"];
            $_SESSION['ville'] = $data[0]["ville"];
            header("Location:inter.php");
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
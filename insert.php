
<?php
    
    $mission = $_POST['mission'];

    $db = new PDO('mysql:host=localhost;dbname=projetfrap','root','');
          
    $sql = "INSERT INTO missionsepas(id,description)
            VALUES (NULL,'$mission') ";
    $resultat = $db->prepare($sql);
    $resultat->execute();

    //header("Location:inter.php");
    



?>
<?php
    include("../config/database.php");
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    

    $login = $_GET['log'];
    $cle = $_GET['cle'];
    

    $requete = $bdd->prepare("SELECT clef, actif FROM users WHERE login like \"".$login."\";");
    $requete->execute();
    $row = $requete->fetch();
    $clebdd = $row['clef'];
    $actif = $row['actif'];
    
    if($actif == '1')
    {
        echo "Votre compte est déjà actif !";
    }
    else
    {
        if($cle == $clebdd)	
        {
            echo "Votre compte a bien été activé !";
            $requete = $bdd->prepare("UPDATE users SET actif = 1 WHERE login like \"".$login."\";");
            $requete->execute();
            header("Location: ../index.php?account=actif");
        }
        else
        {
            echo "Erreur ! Votre compte ne peut être activé...";
        }
    }
 ?>
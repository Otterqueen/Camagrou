<?php
    function aff()
    {
        include("./config/database.php");
        try 
        {
            $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) 
        {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        if (is_logged() == false)
        {
            echo "<center><h1>Bienvenue sur Camagru!</h1></center>";
            echo "<center><p>vous pouvez voir les photos des utilisateurs, mais pour en prendre vous-meme ou pour les commenter/aimer il faut vous connecter ! </p></center>";
        }
        else
        {
            $req = "SELECT `login` FROM users WHERE mail = \"".$_SESSION['user']."\"";
            $requete = $bdd->prepare($req);
            $requete->execute();
            $result = $requete->fetch();
            echo "<center><h1>Bienvenue sur Camagru, ".$result['login']."!</h1></center>";
            echo "<center><p>vous pouvez voir les photos des utilisateurs, modifier votre compte, commenter et aimer les photos !</p></center>";
        }
    }
    
?>

<div class="acc"><?php aff();?></div>
        
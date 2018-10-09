<?php
    
    function install_db()
    {     
        include("./config/database.php");   
        try 
        {
            $connexion = new PDO('mysql:host=mysql;', $DB_USER, $DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) 
        {
            echo 'Connexion échouée 2: ' . $e->getMessage();
        }
        $requete = "CREATE DATABASE IF NOT EXISTS Camagrou; USE Camagrou;";
        $connexion->prepare($requete)->execute();
        try 
        {
            $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) 
        {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        create_users_table($bdd);
        create_images_table($bdd);
        create_likes_table($bdd);
        create_coms_table($bdd);
		return ($bdd);
    }
   
    function create_users_table($bdd)
    {
        $sql = "CREATE TABLE IF NOT EXISTS users(usersid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                nom VARCHAR(64) NOT NULL, prenom VARCHAR(64) NOT NULL, login VARCHAR(12) NOT NULL UNIQUE, 
                mdp VARCHAR(256) NOT NULL,  mail VARCHAR(64) NOT NULL UNIQUE, adr VARCHAR(256) NOT NULL, 
                tel CHAR(10), `admin` BOOLEAN NOT NULL, clef VARCHAR(64) NOT NULL DEFAULT '', 
                actif BOOLEAN NOT NULL DEFAULT FALSE, notif BOOLEAN NOT NULL DEFAULT TRUE)";
        try
        {
            $bdd->prepare($sql)->execute();
        }
        catch(PDOException $e) 
        {
            echo "Erreur lors de la création de la table users".$e->getMessage();
        }   
    }

    function create_images_table($bdd)
    {
        $sql = "CREATE TABLE IF NOT EXISTS images(imagesid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                src LONGBLOB NOT NULL, usersid INT NOT NULL,
                dateajout DATETIME NOT NULL, nblike INT NOT NULL DEFAULT 0,
                nbcom INT NOT NULL DEFAULT 0)";
        $sql2 = "ALTER TABLE images
                ADD FOREIGN KEY (usersid) REFERENCES users(usersid);";
        try
        {
            $bdd->prepare($sql)->execute();
            $bdd->prepare($sql2)->execute();
        }
        catch(PDOException $e) 
        {
            echo "Erreur lors de la création de la table images".$e->getMessage();
        }
    }

    function create_likes_table($bdd)
    {
        $sql = "CREATE TABLE IF NOT EXISTS likes(likesid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                imagesid INT NOT NULL, 
                usersid INT NOT NULL)";
        $sql2 = "ALTER TABLE likes
                ADD FOREIGN KEY (imagesid) REFERENCES images(imagesid),
                ADD FOREIGN KEY (usersid) REFERENCES users(usersid);";
        try
        {
            $bdd->prepare($sql)->execute();
            $bdd->prepare($sql2)->execute();
        }
        catch(PDOException $e) 
        {
            echo "Erreur lors de la création de la table likes".$e->getMessage();
        }      
    }

    function create_coms_table($bdd)
    {
        $sql = "CREATE TABLE IF NOT EXISTS coms(comsid INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                imagesid INT NOT NULL,usersid INT NOT NULL,
                comment VARCHAR(1024) NOT NULL)";
        $sql2 = "ALTER TABLE coms
                ADD FOREIGN KEY (imagesid) REFERENCES images(imagesid),
                ADD FOREIGN KEY (usersid) REFERENCES users(usersid);";
        try
        {
            $bdd->prepare($sql)->execute();
            $bdd->prepare($sql2)->execute();
        }
        catch(PDOException $e) 
        {
            echo "Erreur lors de la création de la table coms".$e->getMessage();
        }      
    }
        
?>
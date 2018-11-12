<?php
    include("../config/database.php");
    session_start();
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }

    
    function mdpisvalid($mdp)
    {
        if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdp))
            return (TRUE);
        else
            return (FALSE);
    }

    function auth($login, $passwd, $bdd)
    {
        $passwdhash = hash("whirlpool",$passwd);
        try 
        {
            $reqlog = $bdd->prepare('SELECT `login`, mdp FROM users');
            $reqlog->execute();
        }catch (PDOExption $e)
        {
            echo 'reqlog échouée : ' . $e->getMessage();
        }
        $res = $reqlog->fetchAll();
    
        foreach($res as $donnees)
        {
            if ($donnees['login'] == $login)
            {
                if ($donnees['mdp'] === $passwdhash)
                {
                    return(TRUE);
                }
                else
                    return (FALSE);
            }
        }
        
    }

    if (isset($_POST['login']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']))
    {
        if ($_POST['submit'] == 'Modifier mon compte')
        {            
            $requete = "UPDATE users SET login =\"".htmlspecialchars($_POST['login'])."\", nom = \"".htmlspecialchars($_POST['nom'])."\", 
                        prenom = \"".htmlspecialchars($_POST['prenom'])."\", tel = \"".htmlspecialchars($_POST['telephone'])."\" , 
                        mail = \"".htmlspecialchars($_POST['mail'])."\", adr = \"".htmlspecialchars($_POST['adresse'])."\"
                        WHERE login =\"".htmlspecialchars($_POST['oldlogin'])."\"";
            try 
            {
                $bdd->prepare($requete)->execute();
            }catch(PDOExption $e){echo 'requete1 échouée : ' . $e->getMessage();}
            if (!isset($_POST['notif']))
            {
                try
                {
                    $requeteNotif = "UPDATE users SET notif=FALSE WHERE login =\"".htmlspecialchars($_POST['oldlogin'])."\"";
                }catch(PDOExption $e){echo 'requeteNotif échouée : ' . $e->getMessage();}
                $bdd->prepare($requeteNotif)->execute();
            }
            else
            {
                try
                {
                    $requeteNotif = "UPDATE users SET notif=TRUE WHERE login =\"".htmlspecialchars($_POST['oldlogin'])."\"";
                }catch(PDOExption $e){echo 'requeteNotif échouée : ' . $e->getMessage();}
                $bdd->prepare($requeteNotif)->execute();
            }

            if ($_POST['passwd'] != "" && $_POST['newpasswd'] != "")
            {
                if (mdpisvalid($_POST['newpasswd']))
                {
                   if (auth(htmlspecialchars($_POST['login']), $_POST['passwd'], $bdd))
                    {
                        $newpswd = hash("whirlpool",$_POST['newpasswd']);
                        try
                        {
                            $requete2 = "UPDATE users SET mdp =\"".$newpswd."\"WHERE login =\"".htmlspecialchars($_POST['oldlogin'])."\"";
                        }catch(PDOExption $e){echo 'requete2 échouée : ' . $e->getMessage();}
                        $bdd->prepare($requete2)->execute();
                        header('Location: ../index.php');
                    }
                    else
                    {
                        header('Location: ../index.php?account=modifwrong');
                    } 
                }
                else
                {
                    header('Location: ../index.php?account=modifwrong');
                }   
            }
            else
            {
                header('Location: ../index.php');
            }
               
        }
        if ($_POST['submit'] == 'Supprimer mon compte')
        {
            $requete5 = "SELECT usersid FROM users WHERE login=\"".htmlspecialchars($_POST['login'])."\"";
            try
            {
                $req5 = $bdd->prepare($requete5);
            }catch(PDOExption $e){echo 'requete5 échouée : ' . $e->getMessage();}
            $req5->execute();
            $donnees = $req5->fetch();
            $id = $donnees['usersid'];
            echo ("<form method=\"POST\" action=\"./modify.php\">
                Etes vous sur ?<br>
                <input type=\"hidden\" name=\"id\" value=\"".$id."\" ></br>
                <input type=\"radio\" name=\"nouo\" value=\"oui\"> oui<br>
                <input type=\"radio\" name=\"nouo\" value=\"non\"> non<br>");
            echo "<input type=\"submit\" name=\"submit\" value=\"valider\" /></form>";
        }
    }
    else if(isset($_POST['submit']))
    {
        if ($_POST['submit'] == "valider")
        {
            /* ----------------------- suppression des image prise par l'utilisateur  --------------------------------------*/
            $reqImage = "SELECT imagesid FROM images 
                        INNER JOIN users on images.usersid = users.usersid 
                        WHERE images.usersid = \"".$_POST['id']."\"";
            $reqImagexec = $bdd->prepare($reqImage);
            $reqImagexec->execute();
            $donnees = $reqImagexec->fetchAll();
            foreach($donnees as $image)
            {
                $requeteLike = "DELETE FROM likes WHERE imagesid=\"".$image['imagesid']."\";";
                $bdd->prepare($requeteLike)->execute();
                $requeteCom = "DELETE FROM coms WHERE imagesid=\"".$image['imagesid']."\";";
                $bdd->prepare($requeteCom)->execute();
                $requeteSupr = "DELETE FROM images WHERE imagesid=\"".$image['imagesid']."\";";
                $bdd->prepare($requeteSupr)->execute();
            }
            /* ----------------------- suppression des commentaire postes par l'utilisateur  --------------------------------------*/
            $reqCom = "SELECT comsid, imagesid FROM coms 
                        INNER JOIN users on coms.usersid = users.usersid 
                        WHERE coms.usersid = \"".$_POST['id']."\"";
            $reqComexec = $bdd->prepare($reqCom);
            $reqComexec->execute();
            $donnees = $reqComexec->fetchAll();
            foreach($donnees as $com)
            {
                $requeteCom = "DELETE FROM coms WHERE comsid=\"".$com['comsid']."\";";
                $bdd->prepare($requeteCom)->execute();
                $minCom = "UPDATE images SET nbcom = nbcom - 1 WHERE imagesid=\"".$com['imagesid']."\"";
                $bdd->prepare($minCom)->execute();
            }
            /* ----------------------- suppression des likes laisser par l'utilisateur  --------------------------------------*/
            $reqLike = "SELECT likesid, imagesid FROM likes 
                        INNER JOIN users on likes.usersid = users.usersid
                        WHERE likes.usersid = \"".$_POST['id']."\"";
            $reqLikexec = $bdd->prepare($reqLike);
            $reqLikexec->execute();
            $donnees = $reqLikexec->fetchAll();
            foreach($donnees as $like)
            {
                $reqLike = "DELETE FROM likes WHERE likesid=\"".$like['likesid']."\";";
                $bdd->prepare($reqLike)->execute();
                $minLike = "UPDATE images SET nblike = nblike - 1 WHERE imagesid=\"".$like['imagesid']."\"";
                $bdd->prepare($minLike)->execute();
            }
            /* ----------------------- suppression de l'utilisateur meme  --------------------------------------*/
            try
            {
                $requete3 = "DELETE FROM users WHERE usersid=\"".$_POST['id']."\"";
            }catch(PDOExption $e){echo 'requete3 échouée : ' . $e->getMessage();}
            $bdd->prepare($requete3)->execute();
            $_SESSION["user"] = "";
            header("Location: ../index.php");
        }
    }
    else
        echo "Error";
    exit;
?>
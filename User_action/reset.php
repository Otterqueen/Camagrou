<?php
    function mdpisvalid()
    {
        $mdp = $_POST['mdp'];
        if (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdp))
            return (TRUE);
        else
            return (FALSE);
    }
    include("../config/database.php");
    if(isset($_POST['submit']))
    {
        if ($_POST['submit'] == "OK")
        {
            try 
            {
                $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            } catch (PDOException $e) 
            {
                echo 'Connexion échouée : ' . $e->getMessage();
            }
            $login = $_GET['log'];
            $cle = $_GET['cle'];
            if (isset($_POST['mdp']))
            {
                if (mdpisvalid())
                {
                    $psw = hash("whirlpool" , $_POST['mdp']);
                    try 
                    {
                        $requete ="UPDATE users SET mdp = \"".$psw."\"WHERE login like \"".$login."\";";
                        $requete1= $bdd->prepare($requete);
                        $requete1->execute();
                        header("Location: ../index.php?account=login");
                    }catch (PDOException $e) 
                    {
                        echo 'requete échouée : ' . $e->getMessage();
                    }
                    
                }
                else
                    header('Location: ../Aff/aff_reset.php?account=mdpwrong&log='.$_GET['log'].'&cle='.$_GET['cle']);
            }
        }
    }
    
 ?>
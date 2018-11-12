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
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }

    if (isset($_POST['submit']))
    {
        if ($_POST['submit'] == "OK")
        {
            if (mdpisvalid())
            {
                $ok = TRUE;
                $str = "";
                $keys = "";

                foreach($_POST as $key=>$value)
                {
                    if (($key != "mdp") && ($key != "submit"))
                    {
                        $str = $str."\"".htmlspecialchars($value)."\", ";
                        $keys = $keys.$key.", ";
                    }
                    if ($key == "mdp")
                    {
                        $keys = $keys.$key.", ";
                        $Hmdp = hash("whirlpool",$value);
                        $str = $str."\"".$Hmdp."\", ";
                    }
                }
                
                try
                {
                    $requete4 = "INSERT INTO users (".$keys." admin) VALUES (".$str." 0)";
                    $bdd->prepare($requete4)->execute();
                }
                catch(PDOException $e)
                {
                    $ok = FALSE;
                    $my_tab = preg_split('/\'/', trim($e));
                    $entry = $my_tab[3];
                    header("Location: ../index.php?account=create&wrong=".$entry);
                }
                

                if ($ok)
                {
                    $email = htmlspecialchars($_POST['mail']);
                    $login = htmlspecialchars($_POST['login']);
                    // creation et insertion de la clé dans la base de données
                    $cle = md5(microtime(TRUE)*100000);
                    $requete5 = "UPDATE users SET clef=\"".$cle."\" WHERE mail like \"".$email."\"";
                    $bdd->prepare($requete5)->execute();

                    $destinataire = $email;
                    $sujet = "Activer votre compte" ;
                    $entete = "From: inscription@camagrou.com" ;
                    $message = 'Bienvenue sur Camagrou,
                    
                    Pour activer votre compte, veuillez cliquer sur le lien ci dessous
                    ou copier/coller dans votre navigateur internet.
                    
                    http://localhost:8008/User_action/activation.php?log='.urlencode($login).'&cle='.urlencode($cle).'
                    
                    
                    ---------------
                    Ceci est un mail automatique, Merci de ne pas y répondre.';
                    
                    
                    mail($destinataire, $sujet, $message, $entete) ; // Envoi du mail
        
                    header("Location: ../index.php"); 
                }
                
            }
            else
            {
                header('Location: ../index.php?account=mdpwrong');
            }
        }
            
    }
?>
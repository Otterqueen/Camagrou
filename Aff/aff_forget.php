<?php
    include("../config/database.php");
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    if (isset($_GET['reset']))
    {
        if (isset($_POST['submit']))
        {
            if ($_POST['submit'] == "OK")
            {
                $email = htmlspecialchars($_POST['mail']);
                $reqlog = $bdd->prepare("SELECT login FROM users WHERE mail=\"".htmlspecialchars($_POST['mail'])."\"");
                $reqlog->execute();
                $res = $reqlog->fetch();
                $login = $res['login'];

                $cle = md5(microtime(TRUE)*100000);
                $requete5 = "UPDATE users SET clef=\"".$cle."\" WHERE mail like \"".$email."\"";
                $bdd->prepare($requete5)->execute();

                $destinataire = $email;
                $sujet = "reset password de votre compte" ;
                $entete = "From: reset@camagrou.com" ;
                $message = 'Bonjour de Camagrou,
                
                Pour rest votre mot de passe, veuillez cliquer sur le lien ci dessous
                ou copier/coller dans votre navigateur internet.
                
                http://localhost:8008/Aff/aff_reset.php?log='.urlencode($login).'&cle='.urlencode($cle).'
                
                
                ---------------
                Ceci est un mail automatique, Merci de ne pas y répondre.';
                
                
                mail($destinataire, $sujet, $message, $entete);// Envoi du mail
    
                header("Location: ../index.php?account=reset");
            }
        }
    }
?>
<div class ="user_form">
	<form method="POST" action="aff_forget.php?reset=ok" style="margin-top: 10px; padding-top: 20px; padding-bottom: 40px;">
		<p>Votre adresse email <input type="text" name="mail" value="" required></p>
		<input type="submit" name="submit" value="OK"/>
	</form>
</div>
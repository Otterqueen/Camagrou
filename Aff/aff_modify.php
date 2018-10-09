<?php
    include("./config/database.php");
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    $requete = $bdd->prepare("SELECT * FROM users WHERE mail=\"".$_SESSION['user']."\"");
    $requete->execute();
    $donnees = $requete->fetch();
	echo "<div class =\"user_form\" style=\"padding-top: 50px;\">
            <form method=\"POST\" action=\"../User_action/modify.php\">
                <p style= \"text-decoration: underline;\">Mes informations de connection :</p>
                <p>nom d'utilisateur : <input type=\"text\" name=\"login\" value=\"".$donnees['login']."\" required></p>
                <p><input type=\"hidden\" name=\"oldlogin\" value=\"".$donnees['login']."\"></p>
				<p>Votre adresse email : <input type=\"text\" name=\"mail\" value=\"".$donnees['mail']."\" required></p>
				<p>Mot de passe : <input type=\"password\" name=\"passwd\" value=\"\"/></p>
				<p>Nouveau Mot de passe : <input type=\"password\" name=\"newpasswd\" value=\"\"/></p></br>
				<p style= \"text-decoration: underline;\">Mes coordonneées :</p>
				<p>Nom : <input type=\"text\" name=\"nom\" value=\"".$donnees['nom']."\" required>
				<p>Prénom :<input type=\"text\" name=\"prenom\" value=\"".$donnees['prenom']."\" required></p>
				<p>Mon adresse : <input type=\"text\" name=\"adresse\" value=\"".$donnees['adr']."\" required></p>
                <p>Mon numéro de téléphone : <input type=\"text\" name=\"telephone\" value=\"".$donnees['tel']."\" required></p></br>";
    if ($donnees['notif'])
        echo    "<p><input style=\"width: 15px;\" type=\"checkbox\" name=\"notif\" checked>Recevoir les notification de commentaire </p></br>";
    else
        echo    "<p><input style=\"width: 15px;\" type=\"checkbox\" name=\"notif\">Recevoir les notification de commentaire </p></br>";
    echo        "<p><input type=\"submit\" name=\"submit\" value=\"Modifier mon compte\" style=\"color: #FFF; background: #0a8c0a;\"/>
                <input type=\"submit\" name=\"submit\" value=\"Supprimer mon compte\" style=\"color: #FFF; background: #F00;\"/></p></br>
                </form></div>";
	
?>

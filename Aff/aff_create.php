<?php

	if(isset($_GET['wrong']))
	{
		echo '<script>confirm("le '.$_GET['wrong'].' exite deja, choisis-en un autre");</script>';
	}
	
?>
<h2 style="text-align: center;"> Inscription à Camagru</h2>
<div class ="user_form">
		<form method="POST" action="./User_action/create.php" style="padding-top: 50px; padding-bottom: 50px;">
			<p style="text-decoration: underline";>Mes identifiants :</p>
			<p>Nom d'utilisateur : <input type="text" name="login" value=""  maxlength="12" required></p>
			<blockquote style="font-size: small; color: #676161;">
				Le nom d'utilisateur doit faire 12 caractères maximum.
			</blockquote>
			<p>Votre adresse email : <input type="email" name="mail" value="" required></p>
			<p>Mot de passe : <input type="password" name="mdp" value="" minlength="8" required/></p>
			<blockquote style="font-size: small; color: #676161;">
				Le mot de passe doit contenir au moins : un caractère spécial, une majuscule, une minuscule, 
				un chiffre et faire 8 caractères minimum
			</blockquote>
			</br>
			<p style="text-decoration: underline";>Mes coordonneées :</p>
			<p>Nom : <input type="text" name="nom" value="" required>
			Prénom :<input type="text" name="prenom" value="" required></p>
			<p>Mon adresse : <input type="text" name="adr" value="" required></p>
			<p>Mon numéro de téléphone : <input type="text" name="tel" value="" maxlength="10"  minlength="10" required></p></br>
			<input type="submit" name="submit" value="OK" />
		</form>
</div>
<?php
?>
<div class ="user_form">
		<form method="POST" action="./User_action/create.php" style="padding-top: 50px; padding-bottom: 50px;">
			<p>Nom d'utilisateur : <input type="text" name="login" value="" required></p>	
			<p>Votre adresse email : <input type="email" name="mail" value="" required></p>
			<p>Mot de passe : <input type="password" name="mdp" value="" required/></p>
			</br>
			<p style="text-decoration: underline";>Mes coordonneées :</p>
			<p>Nom : <input type="text" name="nom" value="" required>
			Prénom :<input type="text" name="prenom" value="" required></p>
			<p>Mon adresse : <input type="text" name="adr" value="" required></p>
			<p>Mon numéro de téléphone : <input type="text" name="tel" value="" required></p></br>
			<input type="submit" name="submit" value="OK" />
		</form>
</div>
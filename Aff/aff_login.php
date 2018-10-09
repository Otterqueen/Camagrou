<?php
?>
<div class ="user_form">
	<form method="POST" action="./User_action/login.php" style="margin-top: 10px; padding-top: 20px; padding-bottom: 40px;">
		<p>Votre adresse email <input type="text" name="login" value="" required></p>
		<p>Mot de passe <input type="password" name="passwd" value="" required/></p>
		<input type="submit" name="submit" value="OK"/>
	</form>
	<a href="Aff/aff_forget.php" style="padding:10px">j'ai oublié mon mot de passe</a>
</div>
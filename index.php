<?php
	session_start();
	include("./config/database.php");
	include_once("./config/setup.php");
	$bdd = install_db();

	function getdiv()
	{
			if (isset($_GET['category']))
				include ("./Aff/aff_photo.php");
			else if (isset($_GET['account']))
			{	
				if ($_GET['account'] == 'login')
					include ("./Aff/aff_login.php");
				else if ($_GET['account'] == 'mdpwrong')
				{
					include ("./Aff/aff_create.php");
					echo "<p style=\"font-size: x-large; font-style: italic; color: #F00;\"> mot de passe non valide. il doit contenir au moins une majuscule un chifre et un caractère special</p>";
				}
				else if ($_GET['account'] == 'modifwrong')
				{
					include ("./Aff/aff_modify.php");
					echo "<p style=\"font-size: x-large; font-style: italic; color: #F00;\"> Mauvais mot de passe OU  mot de passe non valide. il doit contenir au moins une majuscule un chifre et un caractère special</p>";
				}
				else if ($_GET['account'] == 'reset')
				{
					include ("./Aff/aff_login.php");
					echo "<p style=\"font-size: x-large; font-style: italic; color: #00F;\"> mail envoyé, veuillez changer votre mot de passe</p>";
				}
				else if ($_GET['account'] == 'loginwrong')
				{
					include ("./Aff/aff_login.php");
					echo "<p style=\"font-size: x-large; font-style: italic; color: #F00;\"> mauvais mot de passe/login ou compte non actif</p>";
				}
				else if ($_GET['account'] == 'actif')
				{
					include ("./Aff/aff_login.php");
					echo "<p style=\"font-size: x-large; font-style: italic; color: #0F0;\"> votre compte a bien ete active </p>";
				}
				else if ($_GET['account'] == 'create')
					include ("./Aff/aff_create.php");
				else if ($_GET['account'] == 'logout')
					include ("./Aff/aff_logout.php");
				else if ($_GET['account'] == 'modify')
					include ("./Aff/aff_modify.php");
				else
					echo "ERROR\n";
			}
			else if (isset($_GET['photo']))
			{
				if ($_GET['photo'] == 'add')
					include ("./Aff/cam.php");
				if ($_GET['photo'] == 'my')
					include ("./Aff/my.php");
			}
			else
				include ("./Aff/aff_ac.php");
	}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Camagrou</title>
        <link rel="stylesheet" href="index.css">
		<link rel="stylesheet" href="./Aff/aff.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    </head>
    <header style="background-color=#FF0">
        <?php  include("./Aff/nav.php"); ?>
    </header>
    <body>
		<div class ="principale"><?php getdiv();?></div>
    </body>
    <footer id="pied_de_page">
        <p>Copyright mchapard, tous droits réservés</p>
    </footer>
</html>
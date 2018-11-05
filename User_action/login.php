<?php
session_start();
function auth($login, $passwd)
{
	include("../config/database.php");
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    $requete = $bdd->prepare('SELECT mail, mdp, `admin`, actif FROM users');
    $requete->execute();
    $result = $requete->fetchAll();
	$passwdhash = hash("whirlpool",$passwd);
	foreach($result as $donnees)
    {
		if ($donnees['actif'])
		{
			if ($donnees['mail'] == $login)
			{
				if ($donnees['mdp'] === $passwdhash)
				{
					return(TRUE);
				}
				else
				{
					return (FALSE);
				}
				
			}
		}
	}
	return (FALSE);
}


if (isset($_POST['login']) && isset($_POST['passwd']) && $_POST['submit'])
{
	if ($_POST['submit'] == 'OK')
	{
		if (auth(htmlspecialchars($_POST['login']), $_POST['passwd']))
		{
			$_SESSION['user'] = htmlspecialchars($_POST['login']);
			header('Location: ../index.php');
			exit;
		}
		else
		{
			header('Location: ../index.php?account=loginwrong');
		}
			
	}
}
else
	echo "Error";
exit;
?>
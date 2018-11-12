<?php
    function evoiemail($imageId, $bdd)
    {
        $reqmail = "SELECT `login`, mail, dateajout
                    FROM users INNER JOIN images ON images.usersid = users.usersid 
                    WHERE images.usersid = users.usersid AND images.imagesid = ".$imageId.";";
        $resmail = $bdd->prepare($reqmail);
        $resmail->execute();
        $result = $resmail->fetch();
        $mail = $result['mail']; 
        $user = $result['login'];
        $date = $result['dateajout'];

        $sujet = "votre photo a du succès" ;
        $entete = "From: commentaire@camagrou.com" ;
        $message = 'Bonjour de Camagrou, '.$user.'
        
        Votre photo du '.$date.' a un nouveau commentaire !
        Allez vite voir :)
        
        http://localhost:8008/index.php?photo=my
        
        ---------------
        Ceci est un mail automatique, Merci de ne pas y répondre.';
        
        
        mail($mail, $sujet, $message, $entete);
    }
?>
<?php
    include("../config/database.php");
    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    session_start();

    if (isset($_POST['filtre']) && ($_POST['filtre'] != ""))
    {
        $filtre = $_POST['filtre'];
        $data = preg_split('/[,]/',$_POST['data']);
        $data2 = base64_decode($data[1]);
        $name ="../img/".$_SESSION['user'].".png";
        file_put_contents($name, $data2);

        
        $source = imagecreatefrompng("../img/".$filtre.".png");
        $destination = imagecreatefrompng($name);
        imagecolortransparent($source, imagecolorat($source,0,0));

        $largeur_source = imagesx($source);
        $hauteur_source = imagesy($source);
        $largeur_destination = imagesx($destination);
        $hauteur_destination = imagesy($destination);
        
        $destination_x = $largeur_destination - $largeur_source;
        $destination_y =  $hauteur_destination - $hauteur_source;
        imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source);
        
        imagepng($destination, $name);

        $image = file_get_contents($name);
        $image = base64_encode($image);
        $image = "data:image/png;base64,".$image;
        unlink($name);
    }
    else
    {
        $image = $_POST['data'];
    }
    
    $requete1 = "SELECT usersid FROM users WHERE mail= \"".$_SESSION["user"]."\";";
    $userid = $bdd->prepare($requete1);
    $userid->execute();
    $result = $userid->fetch();
    $requete2 = "INSERT INTO images (src, usersid, dateajout) VALUES (\"".$image."\",\"".$result['usersid']."\" ,\"".date("m-d-y H:i:s")."\")";
    $bdd->prepare($requete2)->execute();
    header('Location: ../index.php?photo=add');
?>

<?php
    include("./config/database.php");
    
    
    function affiche_com($bdd)
    {
        $sql = "SELECT comment, usersid FROM coms WHERE imagesid=\"".$_POST['imagesid']."\"";
        $userid = $bdd->prepare($sql);
        $userid->execute();
        $result = $userid->fetchAll();
        if ($result)
        {
            foreach($result as $elem)
            {
                $requete1 = "SELECT login FROM users WHERE usersid= \"".$elem["usersid"]."\";";
                $userid = $bdd->prepare($requete1);
                $userid->execute();
                $result = $userid->fetch();
                $comUser = $result['login'];
                echo "<div class=\"commentaire\"> by : ".$comUser."<br>".$elem['comment']."</div>";
            }
        }
        else
        {
            echo "<div class=\"commentaire\" style=\"text-align: center;\">il n'y a pas de commentaire sur cette photo</div>";
        }
        if (is_logged())
        {
            echo "<form class=\"add_comment\" method=\"POST\" action=\"./index.php?category=".$_GET['category']."\">
                    votre commentaire :<textarea rows=\"10\" cols=\"70\" name=\"com\"></textarea>
                    <input type=hidden name=imagesid value=".$_POST['imagesid'].">
                    <input type=\"submit\" value=\"publier\">";
            echo "</form>";
        }
        echo "<a href=\"./index.php?category=".$_GET['category']."\"><i class=\"fas fa-arrow-left fa-2x\"></i></a>";
    }

    function aimedeja($CurentUserid, $imagesid, $bdd)
    {
        $sql = "SELECT usersid FROM likes WHERE imagesid=\"".$imagesid."\"";
        $userid = $bdd->prepare($sql);
        $userid->execute();
        $result = $userid->fetchAll();
        if ($result)
        {
            foreach($result as $elem)
            {
                if($elem['usersid'] == $CurentUserid)
                {
                    return TRUE;
                }
            }
        }
        return FALSE;

    }

    function aff_photo($res, $curentUser, $bdd)
    {
        $res->execute();
        $srcs = $res->fetchAll();
        foreach($srcs as $elem)
        {
            echo "<li><img src=\"".$elem['src']."\"></img><div>";
            echo "<form class=\"boutons\" method=\"POST\" action=\"./index.php?category=".$_GET['category']."\">";
            if (aimedeja($curentUser, $elem['imagesid'], $bdd))
                echo "<button style=\"background-color: #71c2d6;\" id=\"like\" name=\"like\" ><i class=\"fas fa-thumbs-up fa-2x\"></i>  ".$elem['nblike']."</button>";
            else
                echo "<button id=\"like\" name=\"like\"><i class=\"fas fa-thumbs-up fa-2x\"></i>  ".$elem['nblike']."</button>";
            echo "<button id=\"com\" name=\"com\"><i class=\"far fa-comment-alt fa-2x\"></i>  ".$elem['nbcom']."</button>
            <input type=hidden name=\"imagesid\" value=\"".$elem['imagesid']."\">
            </form>";
            echo "</div></li>";
        }
    }

    try 
    {
        $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (PDOException $e) 
    {
        echo 'Connexion échouée : ' . $e->getMessage();
    }
    if (isset($_SESSION["user"]))
    {
       $requete1 = "SELECT usersid FROM users WHERE mail= \"".$_SESSION["user"]."\";";
        $userid = $bdd->prepare($requete1);
        $userid->execute();
        $result = $userid->fetch();
        $curentUser = $result['usersid']; 
    }
    else
        $curentUser = 0;
    
    if (isset($_POST['com']))
    {
        if ($_POST['com']!="")
        {
            $com = htmlspecialchars($_POST['com']);
            $sql = "INSERT INTO coms (imagesid, usersid, comment) VALUES (\"".$_POST['imagesid']."\",\"".$curentUser."\", :com)";
            $req = $bdd->prepare($sql);
            $req->bindValue(':com', $com, PDO::PARAM_STR);
            $req->execute();
            $addcom = "UPDATE images SET nbcom = nbcom + 1 WHERE imagesid=\"".$_POST['imagesid']."\"";
            $bdd->prepare($addcom)->execute();


            $reqNotif = "SELECT notif FROM users 
                        INNER JOIN images ON images.usersid = users.usersid 
                        WHERE images.usersid = users.usersid AND images.imagesid = ".$_POST['imagesid'].";";
            $notif = $bdd->prepare($reqNotif);
            $notif->execute();
            $resultNotif = $notif->fetch();
            if($resultNotif['notif'] == 1)
            {
                require_once("./User_action/evoiemail.php");
                evoiemail($_POST['imagesid'], $bdd);
            }
        }
        $requete = "SELECT src, nblike, nbcom, imagesid  FROM images WHERE imagesid=\"".$_POST['imagesid']."\";";
        $res = $bdd->prepare($requete);
        $res->execute();
        $srcs = $res->fetch();
        echo "<div class=\"affImage\"><img src=\"".$srcs['src']."\"></img>";
        affiche_com($bdd);
        echo "</div>";
    }
    else
    {
        if (isset($_POST['like']))
        {
            if (is_logged())
            {
                if (!aimedeja($curentUser, $_POST['imagesid'], $bdd))
                {
                    $sql = "INSERT INTO likes (imagesid, usersid) VALUES (\"".$_POST['imagesid']."\",\"".$curentUser."\")";
                    $bdd->prepare($sql)->execute();
                    $addlike = "UPDATE images SET nblike = nblike + 1 WHERE imagesid=\"".$_POST['imagesid']."\"";
                    $bdd->prepare($addlike)->execute();
                }
                else
                {
                    $sql = "DELETE FROM likes WHERE imagesid=\"".$_POST['imagesid']."\" AND usersid=\"".$curentUser."\"";
                    $bdd->prepare($sql)->execute();
                    $suplike = "UPDATE images SET nblike = nblike - 1 WHERE imagesid=\"".$_POST['imagesid']."\"";
                    $bdd->prepare($suplike)->execute();
                }
            }
            header("Location: ./index.php?category=".$_GET['category']);
        }
        
        echo "<ul>";
        $limite = 20;
        $offset = 0;
        $nbPhoto = 0;
        if (!(isset($_GET['page'])))
            $_GET['page'] = 0;
        if (!(isset($_GET['category'])))
            $_GET['category'] = "default";
        switch($_GET['category'])
        {
            case 'last':
                $requete1 = "SELECT count(*)  FROM images;";
                $res1 = $bdd->prepare($requete1);
                $res1->execute();
                $nbPhoto = $res1->fetch();
                $limite = $limite*$_GET['page'] +20;
                $offset = $offset+($_GET['page'] * 20);
                $requete = "SELECT src, nblike, nbcom, imagesid  FROM images ORDER BY imagesid DESC LIMIT ".$limite." OFFSET ".$offset.";";
                $res = $bdd->prepare($requete);
                aff_photo($res, $curentUser, $bdd);
                break;
            case 'like':
                $requete1 = "SELECT count(*)  FROM images WHERE nblike > 0;";
                $res1 = $bdd->prepare($requete1);
                $res1->execute();
                $nbPhoto = $res1->fetch();
                $limite = $limite*$_GET['page'] +20;
                $offset = $offset+($_GET['page'] * 20);
                $requete = "SELECT src, nblike, nbcom, imagesid FROM images WHERE nblike > 0 ORDER BY nblike DESC, imagesid  DESC LIMIT ".$limite." OFFSET ".$offset.";";
                $res = $bdd->prepare($requete);
                aff_photo($res, $curentUser, $bdd);
                break;
            default :
                echo "pas trouvé. Déso";
                break;
        }
        echo "</ul>";
        if ($nbPhoto[0]%20 != 0)
        {
            $nbpage_max = ($nbPhoto[0] / 20) +1;
        }
        else
        {
            $nbpage_max = ($nbPhoto[0] / 20);
        }

        if ($nbPhoto[0] > 20)
        {
            $page = $_GET['page'] + 1;
            echo "<center>";
            if($page-1 !=0)
                echo "<a id=\"bouton_next\" href=\"./index.php?category=".$_GET['category']."&page=".($page - 2)."\"><i class=\"fas fa-arrow-left fa-2x\"></i></a>";
            echo "<b style = \"padding: 5px;\">".$page."</b>";
            if($page+1 <= $nbpage_max)
                echo "<a id=\"bouton_next\" href=\"./index.php?category=".$_GET['category']."&page=".$page."\"><i class=\"fas fa-arrow-right fa-2x\"></i></a>";
            
            echo "</center>";
        }
    }
?>
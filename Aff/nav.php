<?php
    function is_logged()
    {
        if (isset($_SESSION['user']))
        {
            if($_SESSION['user'] != "")
                return TRUE;
            else
                return FALSE;
        }
        else
            return false;
    }
?>
<nav>
    <ul>
        <li ><a href="./index.php" title="home"><img class="logo" src="./img/logo.jpg"></a></li>
        <li class="type"><a href="./index.php?category=last"><i class="far fa-clock fa-2x"></i> last add</a></li>
        <li class="type"><a href="./index.php?category=like"><i class="far fa-thumbs-up fa-2x"></i> by like</a></li>	
		<?php if (is_logged() == false){?>
        <li class="acthome"><a href="./index.php?account=create"><i class="fas fa-user fa-2x"></i> Creer un compte</a></li>
		<li class="acthome"><a href="./index.php?account=login"><i class="far fa-user fa-2x"></i> Se connecter</a></li>
        <?php } else { ?>
        <li class="type"><a href="./index.php?photo=add"><i class="fas fa-plus-circle fa-2x"></i> add</a></li>
        <li class="type"><a href="./index.php?photo=my"><i class="fas fa-images fa-2x"></i> mes photos</a></li>
		<li class="acthome"><a href="./index.php?account=modify"><i class="fas fa-user fa-2x"></i> Mon Compte</a></li>
        <li class="acthome"><a href="./index.php?account=logout"><i class="far fa-user fa-2x"></i> Se déconnecter</a></li> <?php } ?>
    </ul>
</nav>
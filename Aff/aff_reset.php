<?php
    if(isset($_GET['account']))
    {
        if ( $_GET['account'] == 'mdpwrong')
        {
            echo "<p style=\"font-size: x-large; font-style: italic; color: #F00;\"> mot de passe non valide. il doit contenir au moins une majuscule un chifre et un caractère special</p>";
        }
    }
    echo " <div class =\"user_form\">
    <form method=\"POST\" action=\"../User_action/reset.php?log=".$_GET['log']."&cle=".$_GET['cle']."\" style=\"margin-top: 10px; padding-top: 20px; padding-bottom: 40px;\">
        <p>Mot de passe <input type=\"password\" name=\"mdp\" value=\"\" required/></p>
        <input type=\"submit\" name=\"submit\" value=\"OK\"/>
    </form> </div>";
?>
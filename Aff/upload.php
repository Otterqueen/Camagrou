<?php

    if (!($_SESSION['user']) || ($_SESSION['user'] ==""))
    {
        header('Location: ../index.php?account=login');
    }
    
    if (isset($_FILES['photo']))
    {
        $target_dir = "./img";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"]))
        {
            if ($_POST["submit"] == "up")
            $check = getimagesize($_FILES["photo"]["tmp_name"]);
            if($check !== false)
            {
                echo "<p style=\"text-align: center;\">File is an image - ".$check["mime"].".\"</p>";
                $uploadOk = 1;
            } 
            else
            {
                echo "<p style=\"text-align: center;\">File is not an image.</p>";
                $uploadOk = 0;
            }
        }
        // Check if $uploadOk is set to 0 by an error else = everything is ok, affiche file + suppr
        if ($uploadOk == 0)
        {
            echo "<p style=\"text-align: center;\">Sorry, your file was not uploaded.</p>";
        } 
        else
        {
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) 
            {
                $image = file_get_contents($target_file);
                $image = base64_encode($image);
                $image = "data:image/png;base64,".$image;
                echo "<center><canvas id=\"filtre_canvas\" style=\"position: absolute; width: 600px; height:450px\"></canvas>";
                echo "<img id =\"photo_current\" class=\"current_img\" src=\"".$image."\">";
                
                echo "<form method=\"POST\" action=\"../all/add_photo.php\" style=\"padding-top:20px\">
                        <input type=\"hidden\" id=\"hidden\" name=\"data\" value=\"\"/>
                        <input type=\"hidden\" id=\"filtre2\" name=\"filtre\" value=\"\"/>
                        filtres : <select id=\"filtreSel\" name=\"filtreSel\">
                            <option value=\"\">-</option>
                            <option value=\"chi\">chi2</option>
                            <option value=\"chat\">chat</option>
                            <option value=\"chat_visage\">chat_visage</option>
                            <option value=\"chaton_visage\">chaton_visage</option>
                            <option value=\"chien_visage\">chien_visage</option>
                        </select>
                        <button  type=\"submit\" id=\"savebutton\"><i class=\"far fa-save fa-2x\"></i></button>
                    </form></center>";
                unlink($target_file);
            }
            else
            {
                echo "<p style=\"text-align: center;\">Sorry, there was an error uploading your file.</p>";
            }
        }   
    }
?>
<h2 style="text-align: center;">Veuillez selectionner une image à la taille 600*450px : </h2>
<center><form method="post" action="./index.php?photo=upl" enctype="multipart/form-data">
    <p>
        <input type="file" id="choosebutton" name="photo" style="width: 19%;" required>
        <button  type="submit" id="uplbutton" style="border-radius: 10px;" value="up">
                <i class="fas fa-upload fa-3x"></i>
        </button>
    </p>
</form></center>

<script>
    (function() 
    {
        var choosebutton = document.querySelector('#choosebutton'),
            canvas       = document.querySelector('#canvas'),
            filtre       = document.querySelector('#filtreSel'),
            uplbutton    = document.querySelector('#uplbutton'),
            savebutton   = document.querySelector('#savebutton'),
            image_current = document.querySelector('#photo_current');
            
            uplbutton.addEventListener('click', function()
            {
                var filtre        = document.getElementById('filtreSel'),
                    image_current = document.getElementById('photo_current');
            }, false);

            if(filtre)
            {
                filtre.addEventListener('mouseleave', function()
                {
                    var context = document.getElementById('filtre_canvas').getContext("2d");
                    if(filtre.value)
                    {
                        var img = new Image(600,450);
                        img.onload = function ()
                        {
                            context.clearRect(0,0, 600, 450);
                            context.drawImage(img, 10, 0,280,150);
                        }
                        img.src = "../img/"+filtre.value+".png";
                    }
                    else
                        context.clearRect(0,0, 600, 450);
                });
            }
            
            if(savebutton)
            {
                savebutton.addEventListener('click', function(ev)
                {
                    var data = image_current.src;
                    var hidden = document.getElementById("hidden");
                    var filtre2 = document.getElementById("filtre2");
                    hidden.value = data;
                    filtre2.value = filtre.value;
                }, false);
            }   
    })();
</script>
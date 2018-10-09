<?php 

    function affiche_gallery()
    {
        include("./config/database.php");
        try 
        {
            $bdd = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) 
        {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        $requete1 = "SELECT usersid FROM users WHERE mail= \"".$_SESSION["user"]."\";";
        $userid = $bdd->prepare($requete1);
        $userid->execute();
        $result = $userid->fetch();

        $requete2 = "SELECT src FROM images WHERE usersid = \"".$result['usersid']."\";";
        $res = $bdd->prepare($requete2);
        $res->execute();
        $result2 = $res->fetchAll();
        foreach($result2 as $elem)
        {
            echo "<img src=\"".$elem['src']."\">";
        }
    }
?>

<div class="photo">
    <center><canvas id="filtre" style="position: absolute; width: 600px; height:450px"></canvas><video id="video"></video>
    <p><button id="startbutton"><i class="fas fa-camera fa-2x"></i></button></p></center>
    <form method="POST" action="../all/add_photo.php">
        <input type="hidden" id="hidden" name="data" value=""/>
        <input type="hidden" id="filtre2" name="filtre" value=""/>
        filtres : <select id="filtreSel" name="filtreSel">
            <option value="">-</option>
            <option value="chi">chi2</option>
            <option value="chat">chat</option>
        </select>
        <button  type="submit" id="savebutton" disabled="disabled"><i class="far fa-save fa-2x"></i></button>
    </form>
    <canvas id="lol" style="position: absolute; width: 600px; height: 450px;"></canvas>
    <canvas id="canvas"></canvas>
    <div class="gallery"><?php affiche_gallery()?></div>
</div>
<script>
    (function() {

    var streaming = false,
        video        = document.querySelector('#video'),
        cover        = document.querySelector('#cover'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        savebutton  = document.querySelector('#savebutton'),
        lol          = document.querySelector('#lol'),
        filtreSel    = document.querySelector('#filtreSel'),
        width = 600,
        height = 0;

    navigator.getMedia = ( navigator.getUserMedia ||
                        navigator.webkitGetUserMedia ||
                        navigator.mozGetUserMedia ||
                        navigator.msGetUserMedia);

    navigator.getMedia(
    {
        video: true,
        audio: false
    },
    function(stream) {
        if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
        } else {
        var vendorURL = window.URL || window.webkitURL;
        video.srcObject = stream;
        }
        video.play();
    },
    function(err) {
        console.log("An error occured! " + err);
    }
    );

    video.addEventListener('canplay', function(ev)
    {
        if (!streaming) 
        {
            height = video.videoHeight / (video.videoWidth/width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            streaming = true;
            var filtre = document.getElementById('filtreSel');
            filtre.addEventListener('mouseleave', function(){
                console.log(filtre.value);
                var context = document.getElementById('filtre').getContext("2d");
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
    }, false);

    function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
        
        if(filtreSel.value)
        {
            var img = new Image();
            img.src = "../img/"+filtreSel.value+".png";
            img.onload = function ()
            {
                lol.getContext('2d').clearRect(0,0, 600, 450);
                lol.getContext('2d').drawImage(img, 10, 0,280,150);
            }
        }
        else
            lol.getContext('2d').clearRect(0,0, 600, 450);
        
    }

    startbutton.addEventListener('click', function(ev){
        takepicture();
        savebutton.disabled = false;
        ev.preventDefault();
    }, false);

    savebutton.addEventListener('click', function(ev){
        var data = canvas.toDataURL('image/png');
        var hidden = document.getElementById("hidden");
        var filtre2 = document.getElementById("filtre2");
        hidden.value = data;
        filtre2.value = filtreSel.value;
    }, false);

    })();
</script>
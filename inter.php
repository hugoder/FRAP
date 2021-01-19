<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
    <style type="text/css">
        body {
            font-family: "Lato", sans-serif;
        }

        #conteneur {
            display: flex;
        }

        #detailsmap {
            min-height: 100%;
            min-width: 1024px;
            border-right: 20%;

            width: 100%;
            height: auto;

            position: fixed;
            top: 0;
            left: 0;
        }


        .sidenav {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            height: 100%;
            width: 14%;

            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #E57B4A;
            overflow-x: hidden;
        }

        .sidenav a {
            padding: 6px 6px 6px 6px;
            text-decoration: none;
            font-size: 28px;
            color: #111;
            width: 94%;
            height: 23%;
            text-align: center;
            border: solid;
            border-color: black;

        }

        .sidenav a:hover {
            color: #f1f1f1;
        }


        @media screen and (max-height: 450px) {
            .sidenav {
                padding-top: 15px;
            }

            .sidenav a {
                font-size: 18px;
            }
        }
    </style>
    <title>F R A P - Assistant Program</title>
</head>

<body>
    </div id="conteneur">

    <!-- Cette DIV affichera le menu -->
    <div class="sidenav">
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Clients</a>
        <a href="#">Contact</a>
    </div>

    <!-- Cette DIV affichera la carte -->
    <div id="detailsmap"></div>

    </div>


    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script type="text/javascript">
        let mymap, marqueur // Variable qui permettra de stocker la carte et marqueur
        //mymap.on("load", setCity)

        window.onload = () => {
            // Nous initialisons la carte et nous la centrons sur Paris

            mymap = L.map('detailsmap').setView([48.852969, 2.349903], 11)
            L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                attribution: 'Carte fournie par la caserne de Villier le Bel',
                minZoom: 1,
                maxZoom: 20
            }).addTo(mymap)


            // On place ensuite la vue sur l'adresse de l'intervention

            var address = <?php echo json_encode($_SESSION['address']); ?>;
            var code = <?php echo json_encode($_SESSION['code']); ?>;
            var ville = <?php echo json_encode($_SESSION['ville']); ?>;

            let adresse = address + ", " + code + " " + ville

            // On initialise une requête Ajax
            const xmlhttp = new XMLHttpRequest

            xmlhttp.onreadystatechange = () => {
                //Si la requête est terminée 
                if (xmlhttp.readyState == 4) {
                    //Si on a une réponse
                    if (xmlhttp.status == 200) {
                        //On récupère la réponse
                        let response = JSON.parse(xmlhttp.response)

                        let lat = response[0]["lat"]
                        let lon = response[0]["lon"]



                        let pos = [lat, lon]
                        addMarker(pos)

                        //On met à jour le zoom de la carte
                        mymap.setView(pos, 14)
                    }
                }
            }



            //On ouvre la requête 
            xmlhttp.open("get", `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&addressdetails=1&limit=1&polygon_svg=1`)

            //On envoie la requête
            xmlhttp.send()


            console.log("slt");
            //navigator.geolocation.getCurrentPosition(geoSuccess, geoFail)

            navigator.geolocation.getCurrentPosition(success, error);

            function success(pos) 
            {
                {
                    // Ceci s'exécutera si l'utilisateur accepte la géolocalisation
                    startPos = pos;
                    userlat = startPos.coords.latitude;
                    userlon = startPos.coords.longitude;
                    let posuser = [userlat, userlon]
                    addMarker(posuser)
                    console.log("lat: " + userlat + " - lon: " + userlon);
                };


            }

            function error(err) 
            {
                console.warn(`ERREUR (${err.code}): ${err.message}`);
                console.log("ca marche PO");
            }

            /*mymap.on("click", mapClickListen)*/

        }


        /* var geoSuccess = function(position) 
         { 
             // Ceci s'exécutera si l'utilisateur accepte la géolocalisation
                 startPos = position;
                 userlat = startPos.coords.latitude;
                 userlon = startPos.coords.longitude;
                 let posuser = [userlat, userlon]
                 addMarker(posuser)
                 console.log("lat: " + userlat + " - lon: " + userlon);
         };
             var geoFail = function() 
         { 
             // Ceci s'exécutera si l'utilisateur refuse la géolocalisation
                 console.log("refus");
         };

         // La ligne ci-dessous cherche la position de l'utilisateur et déclenchera la demande d'accord
         navigator.geolocation.getCurrentPosition(geoSuccess, geoFail);*/






        /*function setCity() {
            //On fabrique l'adresse
            var address = <?php echo json_encode($_SESSION['address']); ?>;
            var code = <?php echo json_encode($_SESSION['code']); ?>;
            var ville = <?php echo json_encode($_SESSION['ville']); ?>;
        
            let adresse = address + ", " + code + " " + ville
            
            // On initialise une requête Ajax
            const xmlhttp = new XMLHttpRequest

            xmlhttp.onreadystatechange = () => {
                //Si la requête est terminée 
                if (xmlhttp.readyState == 4) {
                    //Si on a une réponse
                    if (xmlhttp.status == 200) {
                        //On récupère la réponse
                        let response = JSON.parse(xmlhttp.response)

                        let lat = response[0]["lat"]
                        let lon = response[0]["lon"]

                     

                        let pos = [lat, lon]
                        addMarker(pos)

                        //On met à jour le zoom de la carte
                        mymap.setView(pos, 12)
                    }
                }
            }

            //On ouvre la requête 
            xmlhttp.open("get", `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&addressdetails=1&limit=1&polygon_svg=1`)

            //On envoie la requête
            xmlhttp.send()

        }*/

        // On attend que le DOM soit chargé



        function addMarker(pos) {
            // On vérifie si un marqueur existe
            /*if (marqueur != undefined) 
            {
                mymap.removeLayer(marqueur)
            }*/

            // On rend le marqueur déplaçable
            marqueur = L.marker(pos, {
                draggable: true
            })

            marqueur.addTo(mymap)

        }



        function mapClickListen(e) {
            // On récupère les coordonnées du clic
            let pos = e.latlng

            // On crée un marqueur
            addMarker(pos)

            // On affiche les coordonnées dans le formulaire
            document.querySelector("#lat").value = pos.lat
            document.querySelector("#lon").value = pos.lng
        }
    </script>



</body>

</html>
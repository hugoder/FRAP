<?php
session_start();

$db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
$sql = "SELECT * FROM plans ";
$result = $db->prepare($sql);
$result->execute();
$data = $result->fetchAll();
$_SESSION['latitude'] = $data[0]["latitude"];
$_SESSION['longitude'] = $data[0]["longitude"];
$db = null;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
    <link rel="stylesheet" type="text/css" href="css/inter.css">


    <script type="text/javascript">
        function showmenu() {
            if (document.getElementById)
                document.getElementById("menu").style.visibility = 'visible';
        }

        function closemenu() {
            if (document.getElementById)
                document.getElementById("menu").style.visibility = 'hidden';
        }
    </script>

    <title>F R A P - Assistant Program</title>
</head>

<body>
    </div id="conteneur">

    <!-- Cette DIV affichera le menu -->
    <div class="sidenav">
        <a href="#" onClick="showmenu();return(false)">Poser un Vehicule</a>
        <a href="#">Services</a>
        <a href="#">Clients</a>
        <a href="#">Contact</a>
    </div>


    <div class="menucamion" id="menu">
        <a href='#' onClick="addMarkerEPAS();"><img src='images/icons/EPAS.png'></a>
        <a href='#' onClick="addMarkerFPT();"><img src='images/icons/FPT.png' alt=''></a>
        <a href='#' onClick="addMarkerVSAV();"><img src='images/icons/VSAV.png' alt=''></a>
        <a href='#' onClick="addMarkerVSR();"><img src='images/icons/VSR.png' alt=''></a>

        <div class="share-button">
            <button onClick="closemenu();return(false)">Fermer</button>
        </div>
    </div>

    <div class="todefine">
        <a href=''><img src='images/help.png' alt=''></a>
    </div>

    <!-- Cette DIV affichera la carte -->
    <div id="detailsmap"></div>




    <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
    <script type="text/javascript">
        let mymap, marqueur // Variable qui permettra de stocker la carte et marqueur


        window.onload = () => { // Fonction principale, nous initialisons la carte et nous la centrons sur Paris


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



                        pos = [lat, lon]


                        addMarker(pos)

                        //On met à jour le zoom de la carte
                        mymap.setView(pos, 17)
                    }
                }
            }



            //On ouvre la requête 
            xmlhttp.open("get", `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&addressdetails=1&limit=1&polygon_svg=1`)

            //On envoie la requête
            xmlhttp.send()


            navigator.geolocation.getCurrentPosition(success, error);
            //console.log(distance(lat,lon));

            function success(pos) {
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

            function error(err) {
                console.warn(`ERREUR (${err.code}): ${err.message}`);
                console.log("ca marche PO");
            }

            /*function distance(lat, lon) {

                var latitude = <?php echo json_encode($_SESSION['latitude']); ?>;
                var longitude = <?php echo json_encode($_SESSION['longitude']); ?>;
                var R = 6371; // Radius of the earth in km
                var dLat = deg2rad(latitude - lat); // deg2rad below
                var dLon = deg2rad(longitude - lon);
                var a =
                    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(deg2rad(latitude)) * Math.cos(deg2rad(lat)) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                var d = R * c; // Distance in km
                return d;

            }

            function deg2rad(deg) {
                return deg * (Math.PI / 180)
            }*/



        }



        /////////////////////////// LES FONCTIONS LIEES AUX MARQUEURS /////////////////////////////////////////////////////////////////////////////////////////


        function addMarker(pos) {
            // On vérifie si un marqueur existe
            /*if (marqueur != undefined) 
            {
                mymap.removeLayer(marqueur)
            }*/

            // On rend le marqueur déplaçable




            var greenIcon = L.icon({
                iconUrl: 'images/tv.png',
                shadowUrl: 'images/tv.png',

                iconSize: [38, 95], // size of the icon
                shadowSize: [50, 64], // size of the shadow
                iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            // On rend le marqueur déplaçable
            marqueur = L.marker(pos, {
                draggable: true,
                //icon : greenIcon
            })

            marqueur.addTo(mymap)



        }


        function addMarkerDrag(pos) {

            var greenIcon = L.icon({
                iconUrl: 'images/tv.png',
                shadowUrl: 'images/tv.png',

                iconSize: [38, 95], // size of the icon
                shadowSize: [50, 64], // size of the shadow
                iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            // On rend le marqueur déplaçable
            marqueur = L.marker(pos, {
                draggable: true,
                icon: greenIcon
            })

            marqueur.addTo(mymap)

        }

        function addMarkerEPAS() {


            var EPASIcon = L.icon({
                iconUrl: 'images/icons/EPAS.png',
                //shadowUrl: 'leaf-shadow.png',

                iconSize: [60, 30], // size of the icon
                //iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor // onclick="alert('Mission ajoutée')"
            });

            marqueur = L.marker(pos, {
                draggable: true,
                icon: EPASIcon
            }).bindPopup(`
            <a rel="noopener noreferrer">
                
                <div>
                    <?php
                    $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
                    $result = $db->query('SELECT * FROM missionsepas');

                    echo '<center><div class="liste"><table>';
                    echo '<tr>';
                    echo '<th class="thliste">N°</th>';
                    echo '<th class="thliste">Description</th>';
                    echo '</tr>';

                    while ($donnees = $result->fetch()) // Renvoit les valeurs de la bdd
                    {
                        echo '<tr>';
                        echo '<td class="tdliste">' . $donnees['id'] . '</td>';
                        echo '<td class="tdliste">' . $donnees['description'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table></div></center>';


                    echo '<input type="text" id="rowText" />';

                    $pdo = null;

                    ?>
                    <input type="button" value="Ajouter" onclick="ajoutmission(document.getElementById('rowText').value);" />
                    

                   

                
                </div>
            </a>`)

            marqueur.addTo(mymap)

        }





        function addMarkerFPT() {

            var FPTIcon = L.icon({
                iconUrl: 'images/icons/FPT.png',
                //shadowUrl: 'leaf-shadow.png',

                iconSize: [60, 30], // size of the icon
                //iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            marqueur = L.marker(pos, {
                draggable: true,
                icon: FPTIcon
            }).bindPopup(`
            <a rel="noopener noreferrer">
                
                <div>
                    <?php
                    $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
                    $result = $db->query('SELECT * FROM missionsfpt');

                    echo '<center><div class="liste"><table>';
                    echo '<tr>';
                    echo '<th class="thliste">N°</th>';
                    echo '<th class="thliste">Description</th>';
                    echo '</tr>';

                    while ($donnees = $result->fetch()) // Renvoit les valeurs de la bdd
                    {
                        echo '<tr>';
                        echo '<td class="tdliste">' . $donnees['id'] . '</td>';
                        echo '<td class="tdliste">' . $donnees['description'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table></div></center>';


                    echo '<input type="text" id="rowText" />';

                    $pdo = null;

                    ?>
                    <input type="button" value="Ajouter" onclick="ajoutmission(document.getElementById('rowText').value);" />
                    

                   

                
                </div>
            </a>`)

            marqueur.addTo(mymap)

        }


        function addMarkerVSAV() {
            // On vérifie si un marqueur existe
            /*if (marqueur != undefined)
            {
            mymap.removeLayer(marqueur)
            }*/

            // On rend le marqueur déplaçable

            var VSAVIcon = L.icon({
                iconUrl: 'images/icons/VSAV.png',
                //shadowUrl: 'leaf-shadow.png',

                iconSize: [60, 30], // size of the icon
                //iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            marqueur = L.marker(pos, {
                draggable: true,
                icon: VSAVIcon
            }).bindPopup(`
            <a rel="noopener noreferrer">
                
                <div>
                    <?php
                    $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
                    $result = $db->query('SELECT * FROM missionsVSAV');

                    echo '<center><div class="liste"><table>';
                    echo '<tr>';
                    echo '<th class="thliste">N°</th>';
                    echo '<th class="thliste">Description</th>';
                    echo '</tr>';

                    while ($donnees = $result->fetch()) // Renvoit les valeurs de la bdd
                    {
                        echo '<tr>';
                        echo '<td class="tdliste">' . $donnees['id'] . '</td>';
                        echo '<td class="tdliste">' . $donnees['description'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table></div></center>';


                    echo '<input type="text" id="rowText" />';

                    $pdo = null;

                    ?>
                    <input type="button" value="Ajouter" onclick="ajoutmission(document.getElementById('rowText').value);" />
                    

                   

                
                </div>
            </a>`)

            marqueur.addTo(mymap)

        }

        function addMarkerVSR() {

            var VSRIcon = L.icon({
                iconUrl: 'images/icons/VSR.png',
                //shadowUrl: 'leaf-shadow.png',

                iconSize: [60, 30], // size of the icon
                //iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                shadowAnchor: [4, 62], // the same for the shadow
                //popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
            });

            marqueur = L.marker(pos, {
                draggable: true,
                icon: VSRIcon
            }).bindPopup(`
            <a rel="noopener noreferrer">
                
                <div>
                    <?php
                    $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
                    $result = $db->query('SELECT * FROM missionsvsr');

                    echo '<center><div class="liste"><table>';
                    echo '<tr>';
                    echo '<th class="thliste">N°</th>';
                    echo '<th class="thliste">Description</th>';
                    echo '</tr>';

                    while ($donnees = $result->fetch()) // Renvoit les valeurs de la bdd
                    {
                        echo '<tr>';
                        echo '<td class="tdliste">' . $donnees['id'] . '</td>';
                        echo '<td class="tdliste">' . $donnees['description'] . '</td>';
                        echo '</tr>';
                    }
                    echo '</table></div></center>';
                    $db = null;
                    ?>  
                        
                    <form method="POST" action="#"><input type="text" name="rowText" id="rowText" />
                    <input type="button" name="submit" value="Ajouter" onclick="ajoutmission(document.getElementById('rowText').value);"/>
                    </form>
                        
                        
                         
                    
                    
                    
                    <?php
                    if (isset($_POST['submit'])) {
                        $mission = $_POST['rowText'];

                        $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
                        $sql = "INSERT INTO missionsvsr(id,description)
                                    VALUES (NULL,'$mission'); ";
                        $resultat = $db->prepare($sql);
                        $resultat->execute();
                        $db = null;
                    }
                    ?>

                   

                
                </div>
            </a>`)

            marqueur.addTo(mymap)

        }
        /// PROBLEMES : loop de l'ajout de mission dans le popup
        /// Popup pas compris

        function ajoutmission(value) {
            console.log(value);

        }







        /*idée : menu click souris
                 mission assigné aux camions : envoyé sms à un 06
               06 03 21 46 96*/

        /*function mapClickListen(e) {
            // On récupère les coordonnées du clic
            let pos = e.latlng

            // On crée un marqueur
            addMarker(pos)

            // On affiche les coordonnées dans le formulaire
            document.querySelector("#lat").value = pos.lat
            document.querySelector("#lon").value = pos.lng
        }*/
    </script>



</body>

</html>
<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
    <link rel="stylesheet" type="text/css" href="css/inter.css">


    <title>F R A P - Assistant Program</title>
    <script src='https://code.jquery.com/jquery-2.1.3.min.js'></script>
    <script type="text/javascript">
        function formSubmit() {
            $.ajax({
                type: 'POST',
                url: 'insert.php',
                data: $('#frmBox').serialize(),
                success: function(response) {
                    $('#success').html(response);
                }
            });
            var form = document.getElementById('frmBox').reset();
            return false;
        }
    </script>

</head>

<body>
    </div id="conteneur">

    <!-- Cette DIV affichera le menu -->
    <div class="sidenav">
        <a href="#" onClick="closemenu3();showmenu();return(false)">Poser un Vehicule<br></br><img src='images/firetruck.png'></a>
        <a href="#" onClick="closemenu3();showmenu1();return(false)">Informations Hydrants<br></br><img src='images/hydrant.png'></a>
        <a href="#" onClick="showmenu2();return(false)">Plans des Résidences<br></br><img src='images/plans.png'></a>
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

    <div class="menuhydrant" id="menu1">
        <x>Menu Hydrant</x>
        <c>
            <b>
                <a href='#' onClick="showhydrant();">Montrer tous les hydrants</a>

                <a href='#' onClick="tejhydrant();">Ne plus montrer</a>

            </b>
            <b>
                <a href='#' onClick="hydrantA();">Réseau A</a>
                <a href='#' onClick="hydrantB();">Réseau B</a>
                <a href='#' onClick="hydrantC();">Réseau C</a>


            </b>
        </c>
        <div class="share-button">
            <button onClick="closemenu1();return(false)">Fermer</button>
        </div>
    </div>





    <div class="todefine">
        <a href='#' onClick="showmenu3();return(false)"><img src='images/help2.png' alt=''></a>
    </div>

    <div class="info" id="menuinfo">
        <a>Numéro d'intervention : <?php echo $_SESSION["num"];  ?></a>
        <a>Adresse : <?php echo $_SESSION["address"] ?> </a>
        <a>Code Postal : <?php echo $_SESSION["code"] ?> </a>
        <a>Ville : <?php echo $_SESSION["ville"] ?> </a>

    </div>

    <!-- Cette DIV affichera la carte -->
    <div id="detailsmap"></div>

    <!-------------------------------------- DEBUT JS ------------------------------------------------>


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

            lgHydrant = new L.LayerGroup();
            mymap.addLayer(lgHydrant);

            lgHydrantA = new L.LayerGroup();
            mymap.addLayer(lgHydrantA);

            lgHydrantB = new L.LayerGroup();
            mymap.addLayer(lgHydrantB);

            lgHydrantC = new L.LayerGroup();
            mymap.addLayer(lgHydrantC);






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

                        lat = response[0]["lat"]
                        lon = response[0]["lon"]



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



            ////////////////////////////// GEOLOCALISATION ////////////////////////////////////////

            navigator.geolocation.getCurrentPosition(success, error);


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

            //////////////////////// Fonction distance (fiche) ///////////////////////////////////////

            /*function distance(lat,lon,fichelat,fichelon) {
            let xmlhttp2 = new XMLHttpRequest();
            
            
            xmlhttp2.onreadystatechange = () => {

                if (xmlhttp2.readyState == 4) {


                    if (xmlhttp2.status == 200) {
                        let donnees = JSON.parse(xmlhttp2.responseText)
                        
                            var R = 6371; // Radius of the earth in km
                            var dLat = deg2rad(fichelat - lat); // deg2rad below
                            var dLon = deg2rad(fichelon - lon);
                            var a =
                                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                Math.cos(deg2rad(fichelat)) * Math.cos(deg2rad(lat)) *
                                Math.sin(dLon / 2) * Math.sin(dLon / 2);
                            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            var d = R * c; // Distance in km
                            console.log(d);
                            return d;

                        

                    }
                }
            }
            xmlhttp2.open("GET", "plans.php");
            xmlhttp2.send(null);

        }


        function deg2rad(deg) {
            return deg * (Math.PI / 180)
        }*/


        }
        ///////////////////////////FONCTIONS FICHES/////////////////////////////////////////////////////////////////////////////////////////
        /*function showFiche() {
            let xmlhttptry = new XMLHttpRequest();

            xmlhttptry.onreadystatechange = () => {

                if (xmlhttptry.readyState == 4) {

                    if (xmlhttptry.status == 200) {
                        let donnees = JSON.parse(xmlhttptry.responseText)


                        Object.entries(donnees.fiche).forEach(fiches => {
                            var customIconDispo = L.icon({
                                iconUrl: 'dispo.png',
                                iconSize: [42, 42], // taille de l'icone
                                iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                            });

                            marker = L.marker([fiches[1].latitude, fiches[1].longitude], {
                                icon: customIconDispo
                            }).addTo(mymap)
                            marker.bindPopup(hydrans[1].nom)
                            console.log('testfiche');


                        })
                    } else {
                        console.log(xmlhttptry.statusText);
                    }
                }
            }

            xmlhttptry.open("GET", "fiche.php");

            xmlhttptry.send(null);

        }*/
        ///////////////////////// FONCTIONS HYDRANTS //////////////////////////////////////////////////////////////////////////


        function showhydrant() {


            let xmlhttptest = new XMLHttpRequest();

            xmlhttptest.onreadystatechange = () => {

                if (xmlhttptest.readyState == 4) {

                    if (xmlhttptest.status == 200) {
                        let donnees = JSON.parse(xmlhttptest.responseText)


                        Object.entries(donnees.hydran).forEach(hydrans => {
                            if (hydrans[1].nom == "disponible") {

                                if (hydrans[1].reseau == 0) {
                                    var customIconDispo = L.icon({
                                        iconUrl: 'dispo.png',
                                        iconSize: [42, 42], // taille de l'icone
                                        iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                        popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                                    });

                                    marker = L.marker([hydrans[1].lat, hydrans[1].lon], {
                                        icon: customIconDispo
                                    }).addTo(lgHydrant)
                                    marker.bindPopup(hydrans[1].reseau)

                                } else if (hydrans[1].reseau == 1) {
                                    var customIconDispo = L.icon({
                                        iconUrl: 'dispo.png',
                                        iconSize: [42, 42], // taille de l'icone
                                        iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                        popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                                    });

                                    marker = L.marker([hydrans[1].lat, hydrans[1].lon], {
                                        icon: customIconDispo
                                    }).addTo(lgHydrantA)
                                    marker.bindPopup(hydrans[1].reseau)

                                } else if (hydrans[1].reseau == 2) {
                                    var customIconDispo = L.icon({
                                        iconUrl: 'dispo.png',
                                        iconSize: [42, 42], // taille de l'icone
                                        iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                        popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                                    });

                                    marker = L.marker([hydrans[1].lat, hydrans[1].lon], {
                                        icon: customIconDispo
                                    }).addTo(lgHydrantB)
                                    marker.bindPopup(hydrans[1].reseau)

                                } else if (hydrans[1].reseau == 3) {
                                    var customIconDispo = L.icon({
                                        iconUrl: 'dispo.png',
                                        iconSize: [42, 42], // taille de l'icone
                                        iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                        popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                                    });

                                    marker = L.marker([hydrans[1].lat, hydrans[1].lon], {
                                        icon: customIconDispo
                                    }).addTo(lgHydrantC)
                                    marker.bindPopup(hydrans[1].reseau)

                                }


                            } else if (hydrans[1].nom == "non disponible") {
                                if (hydrans[1].reseau == 2) {
                                    var customIconNonDispo = L.icon({
                                        iconUrl: 'nondispo.png',
                                        iconSize: [42, 42], // taille de l'icone
                                        iconAnchor: [32, 64], // point de l'icone qui correspondra à la position du marker
                                        popupAnchor: [-3, -76] // point depuis lequel la popup doit s'ouvrir relativement à l'iconAnchor
                                    });

                                    marker = L.marker([hydrans[1].lat, hydrans[1].lon], {
                                        icon: customIconNonDispo
                                    }).addTo(lgHydrant)
                                    marker.bindPopup(hydrans[1].nom)
                                }

                            }


                        })
                    } else {
                        console.log(xmlhttptest.statusText);
                    }
                }
            }

            xmlhttptest.open("GET", "liste.php");

            xmlhttptest.send(null);



        }


        function hydrantA() {

            lgHydrant.clearLayers();
            lgHydrantB.clearLayers();
            lgHydrantC.clearLayers();

        }

        function hydrantB() {

            lgHydrant.clearLayers();
            lgHydrantA.clearLayers();
            lgHydrantC.clearLayers();

        }

        function hydrantC() {

            lgHydrant.clearLayers();
            lgHydrantB.clearLayers();
            lgHydrantA.clearLayers();

        }


        function tejhydrant() {
            lgHydrant.clearLayers();
            lgHydrantA.clearLayers();
            lgHydrantB.clearLayers();
            lgHydrantC.clearLayers();
        }




        /////////////////////////// LES FONCTIONS LIEES AUX MARQUEURS /////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////// Marqueurs classique //////////////////////////////////////////////

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

        ////////////////////// Marqueurs Camions ///////////////////////////////////////////////////////////////////////

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


                    //echo '<input type="text" id="rowText" />';
                    //<input type="button" value="Ajouter" onclick="ajoutmission(document.getElementById('rowText').value);" />

                    echo '<form  action="insert.php" method="post" id="frmBox" onsubmit="return formSubmit();">';
                    echo '<p>';
                    echo '<input type="text" name="mission" id="mission">';
                    echo '</p>';

                    echo '<input type="submit" name="submit" value="Submit">';
                    echo '<p id="success"></p>';
                    echo '</form>';




                    $pdo = null;

                    ?>
                    
                    
                    
                    

                   

                
                </div>

                

            </a>
            
            `)

            marqueur.addTo(mymap)

        }



        function ajoutmission(value) {
            console.log(value);
            var p1 = value;
            console.log(p1);

            document.cookie = "mission=p1";



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




        ////////////////// Cimetière de fonctions //////////////////////////////////////////////







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





    <script type="text/javascript">
        ////////////////// Fonctions pour menus ///////////////////////////////////////////////

        function showmenu() {
            if (document.getElementById)
                document.getElementById("menu").style.visibility = 'visible';
        }

        function closemenu() {
            if (document.getElementById)
                document.getElementById("menu").style.visibility = 'hidden';
        }

        function showmenu1() {
            if (document.getElementById)
                document.getElementById("menu1").style.visibility = 'visible';
        }

        function closemenu1() {
            if (document.getElementById)
                document.getElementById("menu1").style.visibility = 'hidden';
        }

        function showmenu2() {
            if (document.getElementById)
                document.getElementById("menu2").style.visibility = 'visible';
        }

        function closemenu2() {
            if (document.getElementById)
                document.getElementById("menu2").style.visibility = 'hidden';
        }

        function showmenu3() {
            if (document.getElementById)
                document.getElementById("menuinfo").style.visibility = 'visible';
        }

        function closemenu3() {
            if (document.getElementById)
                document.getElementById("menuinfo").style.visibility = 'hidden';
        }


        i = 0;

        /*idée : menu click souris
                 mission assigné aux camions : envoyé sms à un 06
                 06 03 21 46 96

          A Faire : Ajout mission popup
                    Finir menu fiche*/
    </script>

    <div class="menufiche" id="menu2">
        <?php
        include "download.php";
        $db = new PDO('mysql:host=localhost;dbname=projetfrap', 'root', '');
        $stmt = $db->prepare("select * from residences");
        $stmt->execute();
        //

        while ($row = $stmt->fetch()) {
        ?>
            <tr>
                <td><a href="download.php?id=<?php echo $row['id'] ?>"><?php echo $row['nom'] ?></br></br><?php echo rand(5, 15); ?> km</a>
                    <script>
                        function distance(fichelat, fichelon) {
                            
                            var lat = 48.0658;
                            var lon = 2.2356;

                            var tabd = [];








                            var R = 6371; // Radius of the earth in km
                            var dLat = deg2rad(fichelat - lat); // deg2rad below
                            var dLon = deg2rad(fichelon - lon);
                            var a =
                                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                                Math.cos(deg2rad(fichelat)) * Math.cos(deg2rad(lat)) *
                                Math.sin(dLon / 2) * Math.sin(dLon / 2);
                            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                            tabd[i] = R * c; // Distance in km
                            //console.log(tabd[i]);

                            



                            return tabd[i];

                        }


                        function deg2rad(deg) {
                            return deg * (Math.PI / 180)
                        }

                        function markerfiche(fichelat, fichelon) {
                            let posfiche = [fichelat, fichelon];
                            var greenIcon = L.icon({
                                iconUrl: 'images/icons/EPAS.png',
                                //shadowUrl: 'images/tv.png',

                                iconSize: [38, 95], // size of the icon
                                shadowSize: [50, 64], // size of the shadow
                                iconAnchor: [22, 94], // point of the icon which will correspond to marker's location
                                shadowAnchor: [4, 62], // the same for the shadow
                                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
                            });

                            // On rend le marqueur déplaçable
                            marqueur = L.marker(posfiche, {
                                draggable: false,
                                icon: greenIcon
                            })

                            marqueur.addTo(mymap)
                        }



                        var fichelat = <?php echo $row['latitude'] ?>;
                        var fichelon = <?php echo $row['longitude'] ?>;
                        /*let posfiche = [fichelat, fichelon];
                        addMarkerfiche(posfiche);*/


                        d = [];
                        d[i] = distance(fichelat, fichelon);
                        console.log(d[i]);
                        i = i + 1;
                    </script>



                </td>
            </tr>
        <?php
        }
        ?>



        <div class="share-button">
            <button onClick="closemenu2();return(false)">Fermer</button>
        </div>
    </div>

</body>

</html>
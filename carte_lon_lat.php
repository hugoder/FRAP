
  
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />
        <style type="text/css"> 
			#detailsMap{
				height: 400px;
			}
        </style>
        <title>Carte</title>
    </head>
    <body>
        
	<!-- Remplacer # par votre fichier de traitement du formulaire -->
<form action="#" method="post">
    <div>
        <label for="adresse">Adresse</label>
        <input type="text" name="adresse" id="adresse">
    </div>
    <div>
        <label for="cp">Code postal</label>
        <input type="text" name="cp" id="cp">
    </div>
    <div>
        <label for="ville">Ville</label>
        <input type="text" name="ville" id="ville">
    </div>
    <!-- Cette DIV affichera la carte -->
    <div id="detailsMap"></div>
    <!-- Les champs ci-dessous afficheront la latitude et la longitude -->
    <div>
        <label for="lat">Latitude</label>
        <input type="text" name="lat" id="lat" readonly>
    </div>
    <div>
        <label for="lon">Longitude</label>
        <input type="text" name="lon" id="lon" readonly>
    </div>
    <button>Valider</button>
</form>
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
<script type="text/javascript">
	let mymap, marqueur // Variable qui permettra de stocker la carte et marqueur
	// On attend que le DOM soit chargé
	window.onload = () => {
		// Nous initialisons la carte et nous la centrons sur Paris
		mymap = L.map('detailsMap').setView([48.852969, 2.349903], 11)
		L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
			attribution: 'Carte fournie par Wikimedia',
			minZoom: 1,
			maxZoom: 20
		}).addTo(mymap)
		
		// On écoute le clic sur la carte et on lance la fonction "mapClickListen"
		mymap.on("click", mapClickListen)
		document.querySelector("#ville").addEventListener("blur", getCity)
	}
	/**
	 * Cette fonction se déclenche au clic, crée un marqueur et remplit les champs latitude et longitude
	 * @param {event} e 
	 */
	function mapClickListen(e) {
		// On récupère les coordonnées du clic
		let pos = e.latlng
		// On crée un marqueur
		addMarker(pos)
		// On affiche les coordonnées dans le formulaire
		document.querySelector("#lat").value=pos.lat
		document.querySelector("#lon").value=pos.lng
	}
	
	function addMarker(pos){
		// On vérifie si un marqueur existe
		if(marqueur != undefined){
			mymap.removeLayer(marqueur)	
		}
		
		
		marqueur = L.marker(pos, {
		// On rend le marqueur déplaçable 
			draggable: true
		})
		
		// On écoute le glisser/déposer sur le marqueur
		marqueur.on("dragend", function(e){
			pos = e.target.getLatLng()
			// On affiche les coordonnées dans le formulaire
			document.querySelector("#lat").value=pos.lat
			document.querySelector("#lon").value=pos.lng
				
		})
		
		
		marqueur.addTo(mymap)
		
	}
	
	
	function getCity(){
		//On fabrique l'adresse
		let adresse = document.querySelector("#adresse").value + ", " + document.querySelector("#cp").value + " " + document.querySelector("#ville").value
		document.write(adresse.lon)
		// On initialise une requête Ajax
		const xmlhttp = new XMLHttpRequest 
		
		xmlhttp.onreadystatechange = () => {
			//Si la requête est terminée 
			if(xmlhttp.readyState == 4){
				//Si on a une réponse
				if(xmlhttp.status == 200){
					//On récupère la réponse
					let response = JSON.parse(xmlhttp.response)
					
					let lat = response[0]["lat"]
					let lon = response[0]["lon"]
					
					//on met à jour le formulaire
					document.querySelector("#lat").value=lat
					document.querySelector("#lon").value=lon
					
					let pos = [lat, lon]
					addMarker(pos)
					
					//On met à jour le zoom de la carte
					mymap.setView(pos, 12)
				}
			}
		}
		
		//On ouvre la requête 
		xmlhttp.open("get", `https://nominatim.openstreetmap.org/search?q=${adresse}&format=json&addressdetails=1&limit=1&polygon_svg=1` )
		
		//On envoie la requête
		xmlhttp.send()
		
	}
	
	
</script>

<a href="#"><h1>OEEEEEEEEEEEEEEEEEEEEE

            <script type="text/javascript">
	
			document.write(adresse.status)
			
            </script>

            </h1></a>
	
		
    </body>
</html>


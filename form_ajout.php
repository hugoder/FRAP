<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>FRAP</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>

	<?php

	if (isset($_SESSION['mail']))
	{
        ?>
        <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/pompier.jpg);">
					<span class="login100-form-title-1">
						Créer une intervention
					</span>
				</div>

				<form class="login100-form validate-form" method="POST" action="ajout.php">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Assigner un numéro d'intervention est necessaire">
						<span class="label-input100">Numéro</span>
						<input class="input100" type="number" name="num" placeholder="Entrer le numéro d'intervention">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password est necessaire">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="pass" placeholder="Entrer password">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "L'addresse est necessaire">
						<span class="label-input100">Addresse</span>
						<input class="input100" type="text" name="address" placeholder="Entrer l'addresse">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Le code postal est necessaire">
						<span class="label-input100">Code Postal</span>
						<input class="input100" type="number" name="code" placeholder="Entrer le code postal">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "La ville est necessaire">
						<span class="label-input100">Ville</span>
						<input class="input100" type="text" name="ville" placeholder="Entrer la ville" >
						<span class="focus-input100"></span>
					</div>

					

						<div class="container-login100-form-btn">
							<button class="login100-form-btn" name="submit">
								Créer
							</button>
						</div>
						
						<div class="container-login100-form-btn">
							<button class="login100-form-btn" name="submit" onclick="location.href=('index.php')">
								Retour
							</button>
						</div>

				</form>

			</div>
		</div>
	</div>
		
		<?php
	}
	else
	{
        echo "Vous n'avez pas les droits necessaires !"
        ?>

        <div class="container-login101-form-btn">
		<button class="login101-form-btn" name="submit" onclick="location.href=('index.php')">
		OUST
		</button>
        </div>
        
        <?php
	}

	?>
	
	
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>

?>
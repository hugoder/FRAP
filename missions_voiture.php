<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>show demo</title>
  <style>
  span {
    display: none;
  }
  div {
    display: none;
  }
  p {
    font-weight: bold;
    text-decoration:underline;
  }
  </style>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
</head>
<body>
 
<img src="sports-car.png" height="6%" width="6%" >
<span>Ajouter des missions </span>
<div>
  <form>
    <input type="text"  value="Insérer ici">
  </form>
</div>
<p style="display:none;">Fait!!!!</p>
 
<script>
function doIt() {
  $( "span,div" ).show( "slow" );
}
// Can pass in function name
$( "img" ).click( doIt );
 
$( "form" ).submit(function( event ) {
  if ( $( "input" ).val() === "ok" ) {
    $( "p" ).show( 4000, function() {
      $( this ).text( "Mission validée !" );
    });
  }
  $( "span,div" ).hide( "fast" );
 
  // Prevent form submission
  event.preventDefault();
});
</script>
 
</body>
</html>
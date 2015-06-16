<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Save variété dans la bdd</title>
</head>
<body>
  <?php 
  
  	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");

  	echo "<b>Ajout d'une variété pour une photo :</b>";
	if (isset($_POST["idVariete"])&&isset($_POST["idPhoto"])){
		//récupération données formulaire
		$idVariete = $_POST["idVariete"];
		echo "IdVariete : $idVariete";
		$idPhoto=$_POST["idPhoto"];
		echo "IdPhoto : $idPhoto";
		//commande SQL pour ajouter un enregistrement dans la table varietes_photos 
		$sql = "INSERT varietes_photos SET IdVariete=$idVariete, IdPhoto=$idPhoto;";
		$resultQuery = mysqli_query($dbc,$sql);
		if ($resultQuery){
			echo "<p>Variété ajoutée pour la photo suivante : $idPhoto !</p>";	
		}
		else {
				echo "<p>Problème d'ajout !</p>";
			}	
		}
		else {
			echo "Erreur transfert données formulaire !";	
	}
 ?>
 </body>
</html>

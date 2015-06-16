<?php # catalogue.php

/* 
 *	This page represents a specific variety.
 *	This page shows all the features of that variety.
 *	The page expects to receive a $_GET['vid'] value.
 */

//fonction de remplacement de caractères accentués
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


// Redirect if this page was accessed directly:
if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");

	
	// Redirect to the index page:
	$url = BASE_URL . 'index.php';
	header ("Location: $url");
	exit;
	
} // End of defined() IF.

// Check for a variety ID (vid) in the URL:
$vid = NULL;
$idVariete = 0; //initialise idVariete=-1
if (isset($_GET['vid'])) {
		// Typecast it to an integer:
	$vid = (int) $_GET['vid'];
	// An invalid $_GET['vid'] value would be type-casted to 0.
	// $vid must have a valid value.
	if ($vid > 0) {
		// Get the information from the database for this variety:
		$q = "SELECT IdVariete, Nom, NomCode FROM varietes WHERE idVariete=$vid";
		$r = mysqli_query($dbc, $q);
		// Fetch the information:
		if (mysqli_num_rows($r) >= 1) {
			list ($idVariete, $nom, $nomCode) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.
	} // End of ($vid > 0) IF.
} // End of isset($_GET['vid']) 
// Use the variety as the page title:
if ($nom) {
	$page_title = $nom;
}

if ($nom) { // Show the features of the variety.
	echo "<h1>".$nom." ".$nomCode."</h1>\n<br />";
	echo "<h2>".$nomCode."</h2>\n<br />";
}
########################################
# Affichage photos
########################################

if ($idVariete>-1){
		//calcule le nombre de photos disponibles à partir de la base de données pour cette variété
		$qNbPhotos = "SELECT count(photos.idPhoto) FROM varietes_photos INNER JOIN photos ON varietes_photos.idPhoto = photos.idPhoto WHERE varietes_photos.idVariete = $idVariete;";
		$rNbPhotos = mysqli_query($dbc, $qNbPhotos);
		// Fetch the information:
		if (mysqli_num_rows($rNbPhotos) >= 1) {
			list ($nbPhotos) = mysqli_fetch_array($rNbPhotos, MYSQLI_NUM);
			//affiche le nombre de photos
			echo "Nombre de photos disponibles : $nbPhotos <br />";
			}
	//cherche les photos qui correspondent à la variété identifiée par $idVariete
		$qPhotos = "SELECT photos.idPhoto, NomFichier, NomDossier, Camera, ExposureTime, Aperture, ISO, Date FROM varietes_photos INNER JOIN photos ON varietes_photos.idPhoto = photos.idPhoto WHERE varietes_photos.idVariete = $idVariete ORDER BY Date;";
		$rPhotos = mysqli_query($dbc, $qPhotos);
		// Fetch the information:
		if (mysqli_num_rows($rPhotos) >= 1) {
			while (list ($idPhoto, $nomFichier, $nomDossier, $camera, $exposureTime, $aperture, $iso, $datePhoto) = mysqli_fetch_array($rPhotos, MYSQLI_NUM))
			{
				//construire le chemin vers l'image (si elle est dans un sous-dossier)
				$pathImage = "./images/".$nomDossier;
				//On récupère la largeur et l'hauteur de l'image
				$size = getimagesize($pathImage."/".$nomFichier);
				//change sizes if mode portrait
				if ($size[0]>$size[1]){//largeur > hauteur (mode paysage)
					//Largeur maximale de l'image pour la création des miniatures
					$largeur_maxi = 180;
					}
					
					else{//mode portrait
						$largeur_maxi = 80;
						}
					//Si la largeur dépasse la limite autorisée...
					if ($size[0] > $largeur_maxi){
						//...la nouvelle largeur est égale à la limite à ne pas dépasser
						$width = $largeur_maxi;
						//La largeur d'origine divisée par la largeur limitée (on obtient un chiffre qui sert à faire la même proportion pour la hauteur)
						$theight = ($size[0]/$largeur_maxi);
						//La hauteur originale est divisée par le chiffre obtenu précédemment afin que l'image conserve les mêmes proportions que l'originale (mais en mode vignette)
						$height = ($size[1]/$theight);
					} //end if $size[0]>$largeur_maxi
					else{
						//Sinon on garde la taille originale
						$width = $size[0]; 
						$height = $size[1];
					}
					//afficher photo miniature et link vers la page photo avec comme parametres idVariete et idPhoto
					echo "<a href=\"index.php?p=photo&vid=$idVariete&pid=$idPhoto\"><img style=\"border: none; width: ".$width."px; height: ".$height."px\" src=\"".$pathImage."/".$nomFichier."\" alt=\"".$nomFichier."\" /></a>";
				
				//echo "<a href=\"".$pathImage."/".$nomFichier."\" onclick=\"window.open(this.href,'_blank');return false;\"><img style=\"border: none; width: ".$width."px; height: ".$height."px\" src=\"".$pathImage."/".$nomFichier."\" alt=\"".$nomFichier."\" /></a>";
			}//end while resultats photos 
			
		} // End of mysqli_num_rows() IF.

	} // ind if idVariete>-1
	else{
		echo ("Problème de variété : inexistante dans la base de données");
	}

/* //affichage à partir des dossiers
//On indique le dossier images (le nom du dossier = IdVariete_NomVariete avec chaque mot en majuscule et sans espaces)
//transformer chaque debut de mot en majuscule, enlever les espaces
$nomDossier=str_replace(' ','',ucwords($nom));
//enlever les apostrophes
$nomDossier=str_replace("'",'',$nomDossier);
//enlever les .
$nomDossier=str_replace(".",'',$nomDossier);
//remplacer les caractères accentués
$nomDossier=stripAccents($nomDossier);
$pathImage = "./images/".$idVariete."_".$nomDossier;
if (is_dir($pathImage)) { // si c'est un nom de dossier valide
	//On ouvre le dossier images
	$handle=NULL;
	$handle = @opendir($pathImage) or die ("Le dossier n'existe pas !");
	if($handle){
	$listef=NULL;
	//On va parcourir chaque élément du dossier
	while ($file = readdir($handle))
	{ //Si les fichiers sont des images on va les mettre dans la liste des fichiers $listef[]
		if(preg_match ("!(\.jpg|\.jpeg|\.gif|\.bmp|\.png)$!i", $file)){
			$listef[] = $file;
		}
	} // end while
	if($listef){
		$nbImg=count($listef);
	} else {
		$nbImg=0;
	}
	for($noImg=0; $noImg<$nbImg; $noImg++){
		//On récupère la largeur et l'hauteur de l'image
		$size = getimagesize($pathImage."/".$listef[$noImg]);
				//Largeur maximale de l'image pour la création des miniatures
		$largeur_maxi = 180;
		//Si la largeur dépasse la limite autorisée...
		if ($size[0] > $largeur_maxi){
			//...la nouvelle largeur est égale à la limite à ne pas dépasser
			$width = $largeur_maxi;
			//La largeur d'origine divisée par la largeur limitée (on obtient un chiffre qui sert à faire la même proportion pour la hauteur)
			$theight = ($size[0]/$largeur_maxi);
			//La hauteur originale est divisée par le chiffre obtenu précédemment afin que l'image conserve les mêmes proportions que l'originale (mais en mode vignette)
			$height = ($size[1]/$theight);
		} //end if $size[0]>$largeur_maxi
		else{
			//Sinon on garde la taille originale
			$width = $size[0]; 
			$height = $size[1];
		}
		//On affiche l'image aléatoire (en respectant les standards ! <img src="http://forum.phpfrance.com/images/smilies/icon_smile.gif" alt=":)" title="Smile" /> )
		echo "<a href=\"".$pathImage."/".$listef[$noImg]."\" onclick=\"window.open(this.href,'_blank');return false;\"><img style=\"border: none; width: ".$width."px; height: ".$height."px\" src=\"".$pathImage."/".$listef[$noImg]."\" alt=\"".$listef[$noImg]."\" /></a>";
	} // end for each image
	//On ferme le dossier
	closedir($handle);
	}//end if $handle=opendir(...)
} // end if is_dir
else
	{echo("Le dossier n'existe pas !");}
*/
	//////////////////////////////////End add photos

?>

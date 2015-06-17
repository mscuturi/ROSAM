<?php # chargeImagesBDD.php

/* 
 *	This page load all photos from directories to photo table in the database.
 *	Links photo/variety.
 *	The page expects no $_GET['...'] value.
 *	Execute with : http://dionysos.univ-lyon2.fr/~mscuturi/Roses/modules/chargeImagesBDD.inc.php
 */

//fonction de remplacement de caractères accentués
function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}


// This function is used to determine the camera details for a specific image. It returns an array with the parameters.
function cameraUsed($imagePath) {

    // Check if the variable is set and if the file itself exists before continuing
    if ((isset($imagePath)) and (file_exists($imagePath))) {
    
      // There are 2 arrays which contains the information we are after, so it's easier to state them both
      $exif_ifd0 = read_exif_data($imagePath ,'IFD0' ,0);       
      $exif_exif = read_exif_data($imagePath ,'EXIF' ,0);
      
      //error control
      $notFound = "Unavailable";
      
      // Make 
      if (@array_key_exists('Make', $exif_ifd0)) {
        $camMake = $exif_ifd0['Make'];
      } else { $camMake = $notFound; }
      
      // Model
      if (@array_key_exists('Model', $exif_ifd0)) {
        $camModel = $exif_ifd0['Model'];
      } else { $camModel = $notFound; }
      
      // Exposure
      if (@array_key_exists('ExposureTime', $exif_ifd0)) {
        $camExposure = $exif_ifd0['ExposureTime'];
      } else { $camExposure = $notFound; }

      // Aperture
      if (@array_key_exists('ApertureFNumber', $exif_ifd0['COMPUTED'])) {
        $camAperture = $exif_ifd0['COMPUTED']['ApertureFNumber'];
      } else { $camAperture = $notFound; }
      
      // Date
	  //DateTime = date du fichier image, DateTimeOriginal = date de la prise de photo
      if (@array_key_exists('DateTimeOriginal', $exif_ifd0)) {
        $camDate = $exif_ifd0['DateTimeOriginal'];
      } else { $camDate = $notFound; }
      
      // ISO
      if (@array_key_exists('ISOSpeedRatings',$exif_exif)) {
        $camIso = $exif_exif['ISOSpeedRatings'];
      } else { $camIso = $notFound; }
	  
      // SubjectDistance
      if (@array_key_exists('SubjectDistance',$exif_exif)) {
        $subjectDistance = $exif_exif['SubjectDistance'];
      } else { $subjectDistance = $notFound; }
      
      $return = array();
      $return['make'] = $camMake;
      $return['model'] = $camModel;
      $return['exposure'] = $camExposure;
      $return['aperture'] = $camAperture;
      $return['date'] = $camDate;
      $return['iso'] = $camIso;
      $return['distance'] = $subjectDistance;
	  
      return $return;
    
    } else {
      return false; 
    } 
}

// Redirect if this page was accessed directly:
//if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");
	
	// Redirect to the index page:
//	$url = BASE_URL . 'index.php';
//	header ("Location: $url");
//	exit;
	
//} // End of defined() IF.

//define the form
echo "<form method=\"post\" action=\"identifierVarietesImagesSaveBDD.inc.php\" >";
// Get all the varieties for the list
// Define and execute the query:
$qVariete = 'SELECT IdVariete, Nom FROM varietes ORDER BY Nom';
echo $qVariete;
$rVariete = mysqli_query($dbc, $qVariete);

// Fetch the results:
echo "<select name=\"idVariete\">";
//on donne la possibilité de ne pas identifier une rose, mais la laisser en "Inconnue"
echo "<option value=\"0\">Inconnue</option>";
while (list($idVariete, $nom) = mysqli_fetch_array($rVariete, MYSQLI_NUM)) {
	// Print as a select option.
	echo "<option value=\"$idVariete\">$nom</option>\n";
} //end while varietes
echo "</select>";
echo "<input type=\"submit\" />";
echo "<input type=\"reset\" />";
// Get a photo which is not identified :
		$q = "select photos.IdPhoto, photos.NomFichier, photos.NomDossier, photos.Date, varietes_photos.idVariete from photos left join varietes_photos on photos.idPhoto=varietes_photos.IdPhoto where varietes_photos.idVariete is NULL limit 1
";
		$r = mysqli_query($dbc, $q);
		// Fetch the information:
		if (mysqli_num_rows($r) >= 1) {
			list ($idPhoto, $nomFichier, $nomDossier, $date, $idVariete) = mysqli_fetch_array($r, MYSQLI_NUM);
			//show the image
				echo "<input type=\"hidden\" name=\"idPhoto\" value=\"$idPhoto\" />";
				//construire le chemin vers l'image (si elle est dans un sous-dossier)
				$pathImage = "../images/".$nomDossier;
				//On récupère la largeur et l'hauteur de l'image
				$size = getimagesize($pathImage."/".$nomFichier);
				//afficher photo miniature et link vers la page photo avec comme parametres idVariete et idPhoto
				echo "<br /> Date : $date <br />";
				
				echo "<img style=\"float:left\" src=\"".$pathImage."/".$nomFichier."\" alt=\"".$nomFichier."\" /></a>";
				$exif = exif_read_data($pathImage."/".$nomFichier, 'IFD0');
				echo $exif===false ? "Aucun en-tête de donnés n'a été trouvé.<br />\n" : "L'image contient des en-têtes<br />\n";
				
				$exif = exif_read_data($pathImage."/".$nomFichier, 0, true);
				foreach ($exif as $key => $section) {
					foreach ($section as $name => $val) {
						echo "$key.$name: $val<br />\n";
					}
				}
				
			//show varieties list
			
			
			
		} // End of mysqli_num_rows() IF.

echo "</form>";

?>

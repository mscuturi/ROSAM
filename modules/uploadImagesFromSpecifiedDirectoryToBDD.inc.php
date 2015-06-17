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
#******* File/Image UPLOAD STUFF ********* #
#*********************************** #
/*
http://www.php.net/manual/fr/features.file-upload.php

uploader([int num_uploads [, arr file_types [, int file_size [, str upload_dir ]]]]); 
num_uploads = Number of uploads to handle at once. 
file_types = An array of all the file types you wish to use. The default is txt only. 
file_size = The maximum file size of EACH file. A non-number will results in using the default 1mb filesize. 
upload_dir = The directory to upload to, make sure this ends with a / 

This functions echo()'s the whole uploader, and submits to itself, you need not do a thing but put uploader(); to have a simple 1 file upload with all defaults.*/
function uploader($num_of_uploads=1, $file_types_array=array("txt"), $max_file_size=1048576, $upload_dir=""){ 
  if(!is_numeric($max_file_size)){ 
    $max_file_size = 1048576; 
  } 
  if(!isset($_POST["submitted"])){ 
    $form = "<form action='".$PHP_SELF."' method='post' enctype='multipart/form-data'>Upload files:<br /><input type='hidden' name='submitted' value='TRUE' id='".time()."'><input type='hidden' name='MAX_FILE_SIZE' value='".$max_file_size."'>"; 
    for($x=0;$x<$num_of_uploads;$x++){ 
      $form .= "<input type='file' name='file[]'><font color='red'>*</font><br />"; 
    } 
    $form .= "<input type='submit' value='Upload'><br /><font color='red'>*</font>Maximum file length (minus extension) is 15 characters. Anything over that will be cut to only 15 characters. Valid file type(s): "; 
    for($x=0;$x<count($file_types_array);$x++){ 
      if($x<count($file_types_array)-1){ 
        $form .= $file_types_array[$x].", "; 
      }else{ 
        $form .= $file_types_array[$x]."."; 
      } 
    } 
    $form .= "</form>"; 
    echo($form); 
  }else{ 
    foreach($_FILES["file"]["error"] as $key => $value){ 
      if($_FILES["file"]["name"][$key]!=""){ 
        if($value==UPLOAD_ERR_OK){ 
          $origfilename = $_FILES["file"]["name"][$key]; 
          $filename = explode(".", $_FILES["file"]["name"][$key]); 
          $filenameext = $filename[count($filename)-1]; 
          unset($filename[count($filename)-1]); 
          $filename = implode(".", $filename); 
          $filename = substr($filename, 0, 15).".".$filenameext; 
          $file_ext_allow = FALSE; 
          for($x=0;$x<count($file_types_array);$x++){ 
            if($filenameext==$file_types_array[$x]){ 
              $file_ext_allow = TRUE; 
            } 
          } 
          if($file_ext_allow){ 
            if($_FILES["file"]["size"][$key]<$max_file_size){ 
              if(move_uploaded_file($_FILES["file"]["tmp_name"][$key], $upload_dir.$filename)){ 
                echo("File uploaded successfully. - <a href='".$upload_dir.$filename."' target='_blank'>".$filename."</a><br />");
              }else{ 
                echo($origfilename." was not successfully uploaded<br />"); 
              } 
            }else{ 
              echo($origfilename." was too big, not uploaded<br />"); 
            } 
          }else{ 
            echo($origfilename." had an invalid file extension, not uploaded<br />"); 
          } 
        }else{ 
          echo($origfilename." was not successfully uploaded<br />"); 
        } 
      } 
    } 
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

/*
// Check for a variety ID (vid) in the URL:
$vid = NULL;
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

*/

########################################
# Load photos in database
########################################
$pathImage = "../images/";
$max_file_size=1048576;
$file_types_array=array("jpg");
$num_of_uploads=1;
////uploader($num_of_uploads=1, $file_types_array, $max_file_size, $pathImage);

/*
// Define and execute the query to find all varieties:
$q = 'SELECT IdVariete, Nom FROM varietes ORDER BY Nom';
$r = mysqli_query($dbc, $q);


echo($q);
// Fetch the results:
while (list($idVariete, $nom) = mysqli_fetch_array($r, MYSQLI_NUM)) {

	echo("Variété : $nom <br />");
		//On indique le dossier images (le nom du dossier = IdVariete_NomVariete avec chaque mot en majuscule et sans espaces)
	//transformer chaque debut de mot en majuscule, enlever les espaces
	$nomDossier=str_replace(' ','',ucwords($nom));
	//enlever les apostrophes
	$nomDossier=str_replace("'",'',$nomDossier);
	//enlever les .
	$nomDossier=str_replace(".",'',$nomDossier);
	//remplacer les caractères accentués
	$nomDossier=stripAccents($nomDossier);
	$nomDossierCompose=$idVariete."_".$nomDossier;
	$pathImage = "../images/".$idVariete."_".$nomDossier;
	
	//nom du sous-dossier dans le dossier images/ :
	$sousDossier = $idVariete."_".$nomDossier;
	echo("$pathImage<br /><br />");
	*/
	
	
	///// !!!!!!!!!!!!!!Attention : changer les 2 lignes suivantes !!!!!!!
	$pathImage = "../images/Roses04Juillet2013/";
	$sousDossier = "Roses04Juillet2013";
	
	if (is_dir($pathImage)) { // si c'est un nom de dossier valide
		//On ouvre le dossier images
		
		$handle=NULL;
		$handle = @opendir($pathImage) or die ("Le dossier n'existe pas !");
		if($handle){
			$listef=NULL;
			//On va parcourir chaque élément du dossier
			while ($file = readdir($handle)){ //Si les fichiers sont des images on va les mettre dans la liste des fichiers $listef[]
				if(preg_match ("!(\.jpg|\.jpeg|\.gif|\.bmp|\.png)$!i", $file)){
					$listef[] = $file;
				}
			} // end while readdir
			if($listef){
				$nbImg=count($listef);
			} else {
				$nbImg=0;
			} //end if $listef
			for($noImg=0; $noImg<$nbImg; $noImg++){
				//On récupère la largeur et l'hauteur de l'image
				////$size = getimagesize($pathImage."/".$listef[$noImg]);
				
				//get exif informations (see http://fr2.php.net/manual/fr/function.exif-read-data.php)
				$camera = cameraUsed($pathImage."/".$listef[$noImg]);
				//echo ("camera exif<br />");
				//$CameraUsed = $camera['make'] . " " . $camera['model'];
				$CameraUsed = $camera['model'];
				$ExposureTime=$camera['exposure'];
				$Aperture=$camera['aperture'];
				$ISO=$camera['iso'];
				$DateTaken=$camera['date'];
				$subjectDistance= $camera['distance'];
				//insert dans la table photos le nom du fichier et du dossier
				$insertSQL = "INSERT INTO photos SET NomFichier=\"$listef[$noImg]\", NomDossier=\"$sousDossier\", Camera=\"$CameraUsed\", ExposureTime=\"$ExposureTime\", Aperture=\"$Aperture\", ISO=$ISO, Date=\"$DateTaken\", SubjectDistance=\"$subjectDistance\";";
				echo($insertSQL);
				$resultQueryInsert = mysqli_query($dbc, $insertSQL);
			if ($resultQueryInsert){
				echo "<p>Photo $listef[$noImg] ajoutée !</p>";
			}
			else {
				echo "<p>Problème ajout !</p>";
				exit;
			} //end if resultQueryInsert
		} //end for each fichier image
		} //end if $handle
		} // end if is_dir
	else {//if is_dir
		echo("Le dossier n'existe pas pour l'instant");
	} //end else if is_dir
		
?>

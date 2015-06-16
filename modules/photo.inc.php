<a href="colors.inc.php">colors.inc</a><?php # photo.php

/* 
 *	This page presents a specific photo.
 *	This page shows all the features of that photo.
 *	The page expects to receive a variety id: $_GET['vid'] and a photo Id : $_GET['pid'] value.
 */
 
 //in order to calculate dominant color


include_once("colors.inc.php");

//in order to calculate dominant color - end
 
 //fonction pour extraire la couleur dominante de la photo
 function dominant_color($url){
	 
	 /*Allow you to select the quantization delta. The smaller the delta the more accurate the color. This also increases the number of similar colors though.
Added a filter to reduce brightness variants of the same color.
Added a filter to reduce gradient variants ( useful for logos ).
Changed color counts to percentages.*/

// Setting delta = 1 , reduce_brightness = false ,  reduce_gradient = false  will give exact color and near by color. we checked the same Hex  code also occurs while testing same in photoshop
	$delta = 2;
	$reduce_brightness = false;
	$reduce_gradients = false;
	$num_results = 20;
	$ex=new GetMostCommonColors();
	$colors=$ex->Get_Color( "$url", $num_results, $reduce_brightness, $reduce_gradients, $delta);
	echo "<table>\n<tr><td>Color</td><td>Color Code</td><td>Percentage</td><td rowspan=\"";
	echo (($num_results > 0)?($num_results+1):22500);
	echo "\"><img src=\"$url\" alt=\"test image\" /></td></tr>";
	foreach ( $colors as $hex => $count )
	{
		if ( $count > 0 && $hex!="000000"  )
		{
			echo "<tr><td style=\"background-color:#".$hex.";\"></td><td>".$hex."</td><td>$count</td></tr>";
		}
	}
	
	echo "</table><br />";

}

// function dominant_color($url){

	 /*   
		//pas bon, calcule la moyenne qui peut être une couleur qui n'existe pas dans l'image
		$i = imagecreatefromjpeg($url);
		$rTotal  = '';
		$bTotal  = '';
		$gTotal  = '';
		$total = '';
		for ($x=0;$x<imagesx($i);$x++) {
			for ($y=0;$y<imagesy($i);$y++) {
				$rgb = imagecolorat($i, $x, $y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				$rTotal += $r;
				$gTotal += $g;
				$bTotal += $b;
				$total++;
		}
	 
	}
	 
	$r = round($rTotal/$total);
	$g = round($gTotal/$total);
	$b = round($bTotal/$total);
	 */
	 
	/** 	
		//pas bon, tableau avec toutes les couleurs possibles, ne tient pas dans la mémoire
		$ImageChoisie = imagecreatefromjpeg($url);
		$TailleImageChoisie = getimagesize($url);
		 
		$largeur=0;
		$hauteur=0;
		
		$couleurs = array('#000000' => 0);
		
		
		while($hauteur<=$TailleImageChoisie[1]){
		 
			while($largeur<=$TailleImageChoisie[0]){
		 
	 
				$rgb=imagecolorat($ImageChoisie, $largeur, $hauteur);
				$r = ($rgb >> 16) & 0xFF; 
				$g = ($rgb >> 8) & 0xFF; 
				$b = $rgb & 0xFF;
				$code_rgb='rgb('.$r.','.$g.','.$b.')';
					if($couleurs[$code_rgb]){
					$couleurs[$code_rgb]++;
					}else{
					$couleurs[$code_rgb]=1;
					}
				$largeur++;
	 
		}
		$hauteur++;
		$largeur=0;
		}
		 
		$max = max($couleurs);
		$valeur_max=array_search($max, $couleurs, TRUE);
	 
	echo '<br /><br /><img style="float:left;margin-right:5px;" width="63" height="63" src="'.$url.'">';
	echo '<div style="float:left;"><div style="font-size:10px;font-family:Verdana;text-align:center;width:300px;padding:5px;margin-bottom:5px;border-radius:5px;border:3px solid;border-color:rgb('.($r-20).','.($g-20).','.($b-20).');background-color:$valeur_max">Rouge : '.$r.', Vert : '.$g.', Bleu : '.$b.'</div>';
	 
	echo '<div style="font-size:10px;font-family:Verdana;text-align:center;width:300px;padding:5px;margin-bottom:5px;border-radius:5px;border:3px solid;border-color:rgb('.($r-20).','.($g-20).','.($b-20).');background-color:$valeur_max">Url : '.$url.'</div></div>';
	
	**/
	
	/* 
	echo '<div style="float:left;"><div style="font-size:10px;font-family:Verdana;text-align:center;width:300px;padding:5px;margin-bottom:5px;border-radius:5px;border:3px solid;border-color:rgb('.($r-20).','.($g-20).','.($b-20).');background-color:rgb('.$r.','.$g.','.$b.')">Rouge : '.$r.', Vert : '.$g.', Bleu : '.$b.'</div>';
	 
	echo '<div style="font-size:10px;font-family:Verdana;text-align:center;width:300px;padding:5px;margin-bottom:5px;border-radius:5px;border:3px solid;border-color:rgb('.($r-20).','.($g-20).','.($b-20).');background-color:rgb('.$r.','.$g.','.$b.')">Url : '.$url.'</div></div>';
	 */
//}
 

 
 

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
$pid=NULL;
$idVariete = 0; //initialise idVariete=-1
$idPhoto=0;
if (isset($_GET['pid'])) {
		// Typecast it to an integer:
	$pid = (int) $_GET['pid'];
	$idPhoto=$pid;
}
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

if ($nom) { // Show the name of the variety.
	echo "<h1>".$nom." ".$nomCode."</h1>\n";
}
########################################
# Affichage photos
########################################
if ($idVariete>0 && $idPhoto>0){
	//cherche la photo qui correspondent à la variable $idPhoto
		$qPhotos = "SELECT NomFichier, NomDossier, Camera, ExposureTime, Aperture, ISO, Date, SubjectDistance FROM photos WHERE idPhoto = $idPhoto;";
		$rPhotos = mysqli_query($dbc, $qPhotos);
		// Fetch the information:
		if (mysqli_num_rows($rPhotos) >= 1) {
			while (list ($nomFichier, $nomDossier, $camera, $exposureTime, $aperture, $iso, $datePhoto, $subjectDistance) = mysqli_fetch_array($rPhotos, MYSQLI_NUM))
			{
				
				//construire le chemin vers l'image (si elle est dans un sous-dossier)
				$pathImage = "./images/".$nomDossier;
				//On récupère la largeur et l'hauteur de l'image
				//la taille de l'image affichée sera 2 fois celle des petites images affichées dans la page variété
				$size = getimagesize($pathImage."/".$nomFichier);
				//Largeur maximale de l'image pour la création des miniatures
				$largeur_maxi = 1024;
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
				echo "<a href=\"".$pathImage."/".$nomFichier."\" onclick=\"window.open(this.href,'_blank');return false;\"><img style=\"border: none; width: ".$width."px; height: ".$height."px\" src=\"".$pathImage."/".$nomFichier."\" alt=\"".$nomFichier."\" /></a>";
				dominant_color($pathImage."/".$nomFichier);
				//afficher les informations exif
				//echo "Camera Used: " . $camera . "<br />";
				echo "<br />Exposure Time: " . $exposureTime . "<br />";
				echo "Aperture: " . $aperture . "<br />";
				echo "ISO: " . $iso . "<br />";
				echo "Date: " . $datePhoto . "<br />";
				//echo "<br /><br /><br />";
				
				if ($subjectDistance != "Unavailable")
					echo "Subject Distance: " . $subjectDistance . "<br />";
				
				//Si on veut afficher la taille originale
				//echo "<a href=\"".$pathImage."/".$nomFichier."\" onclick=\"window.open(this.href,'_blank');return false;\"><img style=\"border: none; \" src=\"".$pathImage."/".$nomFichier."\" alt=\"".$nomFichier."\" /></a>";
			}//end while resultats photos 
			
		} // End of mysqli_num_rows() IF.

	} // ind if idVariete>-1
	else{
		echo ("Problème de variété : inexistante dans la base de données");
	}

	//////////////////////////////////End affiche photo

?>

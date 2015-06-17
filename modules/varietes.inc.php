<?php # varietes.inc.php

/* 
 *	This is the about varietes module.
 *	This page is included by index.php.
 */

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

// Get all the varieties
// Define and execute the query:
$q = 'SELECT IdVariete, Nom, NomCode FROM varietes ORDER BY Nom';
////$r = mysqli_query($dbc, $q);
$r = mysqli_query($dbc, $q);

// Fetch the results:
echo "<ol>";
while (list($idVariete, $nom, $nomcode) = mysqli_fetch_array($r, MYSQLI_NUM)) {
//while (list($idVariete, $nom, $nomcode) = mysql_fetch_array($r, MYSQLI_NUM)) {
			$nbPhotos = "";
		//calcule le nombre de photos disponibles à partir de la base de données pour cette variétée
		$qNbPhotos = "SELECT count(photos.idPhoto) FROM varietes_photos INNER JOIN photos ON varietes_photos.idPhoto = photos.idPhoto WHERE varietes_photos.idVariete = $idVariete;";
		$rNbPhotos = mysqli_query($dbc, $qNbPhotos);
		// Fetch the information:
		if (mysqli_num_rows($rNbPhotos) >= 1) {
			list ($nbPhotos) = mysqli_fetch_array($rNbPhotos, MYSQLI_NUM);
//			list ($nbPhotos) = mysql_fetch_array($rNbPhotos, MYSQLI_NUM);
				}
	// Print as a list item.
	echo "<li><a href=\"index.php?p=variete&vid=$idVariete\">$nom - $nomcode ($nbPhotos photos)</a></li>\n";

/*
//////////////////////////////////Add photos
        ########################################
        # Affichage image aléatoire //manucci.info
        ########################################
        //On indique le dossier images (le nom du dossier = IdVariete_NomVariete avec chaque mot en majuscule et sans espaces)
		$nomDossier=str_replace(' ','',ucwords($nom));
		//enlever les apostrophes
		$nomDossier=str_replace("'",'',$nomDossier);
		//enlever les .
		$nomDossier=str_replace(".",'',$nomDossier);
		//remplacer les é par e
		$nomDossier=str_replace('é','e',$nomDossier);
		//remplacer les è par e
		$nomDossier=str_replace('è','e',$nomDossier);
		//remplacer les ë par e
		$nomDossier=str_replace('ë','e',$nomDossier);
		//remplacer les ï par i
		$nomDossier=str_replace('ï','i',$nomDossier);
		//remplacer les ô par o
		$nomDossier=str_replace('ô','o',$nomDossier);
        $chem_img = "./images/".$idVariete."_".$nomDossier;
        //On ouvre le dossier images
        $handle  = opendir($chem_img);
        //On parcoure chaque élément du dossier
        while ($file = readdir($handle))
                {
                        //Si les fichiers sont des images
                        if(preg_match ("!(\.jpg|\.jpeg|\.gif|\.bmp|\.png)$!i", $file))
                                {
                                        $listef[] = $file;
                                }
                }
        
        $random_img = rand(0, count($listef)-1); //permet de prendre une image totalement au hasard (RANDom) parmi toutes les images trouvées.
        
        //On calcule la largeur et la hauteur de l'image aléatoire
        $size = getimagesize($chem_img."/".$listef[$random_img]);
        
        //Largeur maximale de l'image pour la création des miniatures
        $largeur_maxi = 180;
        //Si la largeur dépasse la limite autorisée...
        if ($size[0] > $largeur_maxi)
                {
                        //...la nouvelle largeur est égale à la limite à ne pas dépasser
                        $width = $largeur_maxi;
                        //La largeur d'origine divisée par la largeur limitée (on obtient un chiffre qui sert à faire la même proportion pour la hauteur)
                        $theight = ($size[0]/$largeur_maxi);
                        //La hauteur originale est divisée par le chiffre obtenu précédemment afin que l'image conserve les mêmes proportions que l'originale (mais en mode vignette)
                        $height = ($size[1]/$theight);
                }
        else
                {
                        //Sinon on garde la taille originale
                        $width = $size[0]; $height = $size[1];
                }

        //On affiche l'image aléatoire (en respectant les standards ! <img src="http://forum.phpfrance.com/images/smilies/icon_smile.gif" alt=":)" title="Smile" /> )

        echo "<a href=\"".$chem_img."/".$listef[$random_img]."\" onclick=\"window.open(this.href,'_blank');return false;\"><img style=\"border: none; width: ".$width."px; height: ".$height."px\" src=\"".$chem_img."/".$listef[$random_img]."\" alt=\"".$listef[$random_img]."\" /></a>";

        //On ferme le dossier

        closedir($handle);

//////////////////////////////////End add photos
*/
} // End of while loop.
echo "</ol>";

?>


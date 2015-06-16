<?php



$pathImage = "../images/7_JubileDuPrinceDeMonaco";
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
		//read Exif data
		echo "<br /><br />$listef[$noImg] :<br />\n";
		$exif = exif_read_data($pathImage."/".$listef[$noImg], 'EXIF');
		echo $exif===false ? "Aucun en-tête de donnés n'a été trouvé.<br />\n" : "L'image contient des en-têtes<br />\n";
		
		$exif = exif_read_data($pathImage."/".$listef[$noImg], 0, true);
		echo "test2.jpg:<br />\n";
		foreach ($exif as $key => $section) {
			foreach ($section as $name => $val) {
				echo "$key.$name: $val<br />\n";
			}
		}
	} // end for each image
	//On ferme le dossier
	closedir($handle);
	}//end if $handle=opendir(...)
} // end if is_dir
else
	{echo("Le dossier n'existe pas !");}

?>
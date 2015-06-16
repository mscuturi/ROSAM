<?php # catalogue.php

/* 
 *	This page represents a specific catalog.
 *	This page shows all the films classified
 *	under that catalog.
 *	The page expects to receive a $_GET['cid'] value.
 */


// Redirect if this page was accessed directly:
if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	
	// Redirect to the index page:
	$url = BASE_URL . 'index.php';
	header ("Location: $url");
	exit;
	
} // End of defined() IF.

// Check for a catalog ID in the URL:
$catalogue = NULL;
if (isset($_GET['cid'])) {

	// Typecast it to an integer:
	$cid = (int) $_GET['cid'];
	// An invalid $_GET['cid'] value would
	// be type-casted to 0.
	
	// $cid must have a valid value.
	if ($cid > 0) {
	
		// Get the information from the database
		// for this category:
		$q = "SELECT NomCatalogue, Fichier, Version, Annee, Mois FROM catalogue WHERE idCatalogue=$cid";
		$r = mysqli_query($dbc, $q);
		
		// Fetch the information:
		if (mysqli_num_rows($r) == 1) {
			list ($catalogue, $fichier, $version, $annee, $mois) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.
	
	} // End of ($cid > 0) IF.
	
} // End of isset($_GET['cid']) IF.

// Use the catalogue as the page title:
if ($catalogue) {
	$page_title = $catalogue." ".$annee." ".$mois." ".$version;
}

if ($catalogue) { // Show the films.
	if($mois){
		echo "<h1>".$catalogue." ".$annee."-".$mois." ".$version."</h1>\n<br />";
	}
	else {
		echo "<h1>".$catalogue." ".$annee." ".$version."</h1>\n<br />";
	}
	
	
	// Print the link to pdf file, if it's not empty.
/*	if (!empty($fichier)) {
		echo "<a href=\"$film \">Fichier pdf</a>\n";
	}
*/

	// Get the films in this catalog:
	$q = "SELECT film_catalogue.Film_idFilm, film_catalogue.Numero, film_catalogue.Titre, film_catalogue.Resume, film_catalogue.Version FROM film_catalogue INNER JOIN catalogue ON film_catalogue.Catalogue_idCatalogue=catalogue.idCatalogue WHERE film_catalogue.Catalogue_idCatalogue=$cid ORDER by CAST(film_catalogue.Numero AS UNSIGNED)";

	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) > 1) {
	
		// Print each:
		while (list($Film_idFilm, $numero, $Titre, $Resume, $Version) = mysqli_fetch_array($r, MYSQLI_NUM)) {
		
			// Link to the film.php page:
			if($numero){
			echo "<h2><a href=\"index.php?p=film&film_id=$Film_idFilm\">$numero. $Titre</a></h2><p>$Resume<br />$Version</p>\n";
			} else {
				echo "<h2><a href=\"index.php?p=film&film_id=$Film_idFilm\">$Titre</a></h2><p>$Resume<br />$Version</p>\n";
			}
			
		} // End of while loop.
		
	} else { // No widgets here!
		echo '<p class="error">Il n\'y a pas de films dans ce catalogue.</p>';
	}

} else { // Invalid $_GET['cid']!
	echo '<p class="error">Erreur d\'accès .</p>';
}


?>

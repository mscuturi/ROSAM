<?php # Script 2.6 - search.inc.php

/* 
 *	This is the search content module.
 *	This page is included by index.php.
 *	This page expects to receive $_GET['terms'].
 */

// Redirect if this page was accessed directly:
if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");
	
	// Redirect to the index page:
	$url = BASE_URL . 'index.php?p=search';
	
	// Pass along search terms?
	if (isset($_GET['terms'])) {
		$url .= '&terms=' . urlencode($_GET['terms']);
	}
	
	header ("Location: $url");
	exit;
	
} // End of defined() IF.

// Print a caption:
echo '<h2>Résultats de la recherche</h2>';

// Display the search results if the form
// has been submitted.

if (isset($_GET['terms']) && ($_GET['terms'] != 'Recherche...') ) {
	$recherche=$_GET['terms'];
	// Get the films like terms:
	$q = "SELECT IdVariete, Nom, NomCode FROM varietes WHERE Nom like \"%$recherche%\"";

	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) >= 1) {
	
		// Print each:
		while (list($idVariete, $nom, $nomCode) = mysqli_fetch_array($r, MYSQLI_NUM)) {
		
			// Link to the variete.php page:
			//echo "<h2><a href=\"film.inc.php?film_id=$Film_idFilm\">$Titre</a></h2><p>$Resume<br />$Version</p>\n";
			//a href=\"index.php?p=catalogue&cid=$fcid\"
			echo "<h2><a href=\"index.php?p=variete&vid=$idVariete\">$nom $nomCode </a></h2>\n";
			
		} // End of while loop.
		
	} else { // No widgets here!
		echo '<p class="error">Il n\'y a pas de variétés qui correspondent à la recherche.</p>';
	}
	
} else { // Tell them to use the search form.
	echo '<p class="error">Utilisez le champ de recherche en haut de la fenetre pour effectuer des recherches sur le site.</p>';
}
?>

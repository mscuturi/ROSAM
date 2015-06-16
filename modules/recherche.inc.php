<?php # recherche.inc.php

/* 
 *	This is the content based image retrieval module.
 *	This page is included by index.php.
 *	
 */

// Redirect if this page was accessed directly:
if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");
	
	// Redirect to the index page:
	$url = BASE_URL . 'index.php?p=rimg';
	
/*	// Pass along search terms?
	if (isset($_GET['terms'])) {
		$url .= '&terms=' . urlencode($_GET['terms']);
	}
	
	header ("Location: $url");
	exit;
*/
} // End of defined() IF.
	
	
// Print a caption:
	echo '<h2>Module de recherche par image</h2>';
	echo '<form name="formcbir" id="formcbir" method="post" action="../modules/recherche.inc.php">';
	echo '	<label for="motcle">Mot clé : </label><br />';
	echo '	<input type="text" name="motcle" value="" size="32"/><br />';
	echo '	<label for="image">Fichier image : </label><br />';
	echo '	<input type="file" name="image" value="" /><br />';
	echo '	<input type="hidden" name="p" value="search" /><br />';
	echo '	<input class=" button" type="submit" name="Submit" value="Rechercher" /><br />';
	echo '</form> <br /> <hr />';

// Display the search results if the form has been submitted.

if (isset($_POST['terms']) && ($_POST['terms'] != 'Recherche...') ) {
	$recherche=$_POST['terms'];
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
	
} 

?>

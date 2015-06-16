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
	include_once("../includes/analyticstracking.php");
	
	// Redirect to the index page:
	$url = BASE_URL . 'index.php';
	header ("Location: $url");
	exit;
	
} // End of defined() IF.

// Check for a groupe ID in the URL:
$catalogue = NULL;
if (isset($_GET['gid'])) {

	// Typecast it to an integer:
	$gid = (int) $_GET['gid'];
	// An invalid $_GET['gid'] value would
	// be type-casted to 0.
	
	// $vid must have a valid value.
	if ($gid > 0) {
	
		// Get the information from the database
		// for this groupe:
		$q = "SELECT IdGroupe, Nom, Type FROM groupes WHERE idGroupe=$gid";
		$r = mysqli_query($dbc, $q);
		
		// Fetch the information:
		if (mysqli_num_rows($r) == 1) {
			list ($idGroupe, $nom, $type) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.
	
	} // End of ($gid > 0) IF.
	
} // End of isset($_GET['gid']) IF.

// Use the catalogue as the page title:
if ($idGroupe) {
	$page_title = $nom." ".$type;
	echo "<h1>Groupe ".$nom." - ".$type."</h1>\n<br />";
//	}
	
	
	// Print the link to pdf file, if it's not empty.
/*	if (!empty($fichier)) {
		echo "<a href=\"$film \">Fichier pdf</a>\n";
	}
*/

	// Get the films in this catalog:
//	$q = "SELECT IdVariete, varietes.Nom, NomCode, varietes.Type, varietes.groupe, groupes.nom, groupes.type FROM `varietes` inner join groupes on varietes.groupe=groupes.IdGroupe WHERE varietes.groupe=$gid ORDER by groupes.type, groupes.nom, varietes.Nom";
$q = "SELECT IdVariete, varietes.Nom, NomCode FROM `varietes` inner join groupes on varietes.groupe=groupes.IdGroupe WHERE varietes.groupe=$gid ORDER by groupes.type, groupes.nom, varietes.Nom";

	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) >= 1) {
	
		// Print each:
		//while (list($idvariete, $nomvariete, $nomcode, $varietetype, $varietegroupe, $groupenom, $groupetype ) = mysqli_fetch_array($r, MYSQLI_NUM)) {
		echo "<ol>";
		while (list($idvariete, $nomvariete, $nomcode) = mysqli_fetch_array($r, MYSQLI_NUM)) {
		
			// Link to the variete.php page:
			//echo "<h2><a href=\"index.php?p=variete&variete_id=$idvariete\">$nomvariete</a></h2><p>$nomcode<br />$varietetype</p><p>$groupenom - $groupetype</p>\n";
			echo "<li><a href=\"index.php?p=variete&vid=$idvariete\">$nomvariete</a> <p>$nomcode</p></li>";
		} // End of while loop.
			echo "</ol>";

	} else { // No widgets here!
		echo '<p class="error">Il n\'y a pas de variétés dans ce groupe.</p>';
	}

} else { // Invalid $_GET['gid']!
	echo '<p class="error">Erreur d\'accès .</p>';
}


?>

<?php # catalogues.inc.php

/* 
 *	This is the about catalogues module.
 *	This page is included by index.php.
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

// Get all the catalogues and
// link them to catalog.php.

// Define and execute the query:
$q = 'SELECT idCatalogue, NomCatalogue, Version, Annee, Mois FROM catalogue ORDER BY NomCatalogue, Annee, Mois, Version';
$r = mysqli_query($dbc, $q);

// Fetch the results:
while (list($fcid, $fcat, $version, $annee, $mois) = mysqli_fetch_array($r, MYSQLI_NUM)) {

	// Print as a list item.
    if($mois){
		echo "<li><a href=\"index.php?p=catalogue&cid=$fcid\">$fcat $annee - $mois $version  </a></li>\n";
        }
        else {
			echo "<li><a href=\"index.php?p=catalogue&cid=$fcid\">$fcat $annee $version </a></li>\n";
        }

} // End of while loop.


?>
<!--
	  <h2>Comité scientifique</h2>
	  <p>Jean-Claude Seguin</p>
	  <p>Odile Leguern</p>
	  <h2>Informatique</h2>
	  <p><a href="#">Serge Miguet</a> </p>
	  <p><a href="#">Mihaela Scuturici</a> </p>
-->

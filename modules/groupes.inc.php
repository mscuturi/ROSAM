<?php # catalogues.inc.php

/* 
 *	This is the about catalogues module.
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

// Get all the catalogues and
// link them to catalog.php.

// Define and execute the query:
$q = 'SELECT IdGroupe, Nom, Type FROM groupes ORDER BY Type, Nom';
$r = mysqli_query($dbc, $q);

// Fetch the results:

echo "***En construction...***";
echo "<ol>";
while (list($fgid, $fgnom, $fgtype) = mysqli_fetch_array($r, MYSQLI_NUM)) {

	// Print as a list item.
	echo "<li><a href=\"index.php?p=groupe&gid=$fgid\">$fgnom - $fgtype  </a></li>\n";
} // End of while loop.
echo "</ol>";

?>


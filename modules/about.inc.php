<?php # about.inc.php

/* 
 *	This is the about content module.
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
?>

	  <h2>Projet </h2> 
      <h3><a href="http://www.univ-lyon2.fr">Université Lumière Lyon 2</a></h3>
	  <p></p>

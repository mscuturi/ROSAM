<?php # contact.inc.php

/* 
 *	This is the contact content module.
 *	This page is included by index.php.
 */

// Redirect if this page was accessed directly:
if (!defined('BASE_URL')) {

	// Need the BASE_URL, defined in the config file:
	require_once ('../includes/config.inc.php');
	include_once("../includes/analyticstracking.php");
	
/*	// Redirect to the index page:
	$url = BASE_URL . 'index.php';
	header ("Location: $url");
	exit;
	*/
} // End of defined() IF.

?>

	  <h2>Informatique</h2>
	  <p><img src="./images/MailMihaelaScuturici.jpg" width="314" height="77" alt="Adresse e-mail de Mihaela Scuturici" /></p>

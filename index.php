<?php # index.php

/* 
 *	This is the main page.
 *	This page includes the configuration file, 
 *	the templates, and any content-specific modules.
 */

// Require the configuration file before any PHP code:
require_once ('./includes/config.inc.php');

// Validate what page to show:
if (isset($_GET['p'])) {
	$p = $_GET['p'];
} elseif (isset($_POST['p'])) { // Forms
	$p = $_POST['p'];
} else {
	$p = NULL;
}

$parameters=NULL;
// Determine what page to display:
switch ($p) {

	case 'about':
		$page = 'about.inc.php';
		$page_title = 'À propos de ce site';
		break;
//liste des groupes de la base
	case 'groupes':
		$page = 'groupes.inc.php';
		$page_title = 'Groupes';
		break;
//affiche les variétés d'un groupe particulier donné comme parametre à travers son id (gid)
	case 'groupe':
		if (isset($_GET['gid'])) {
			$gid = $_GET['gid'];
		} elseif (isset($_POST['gid'])) { // Forms
			$gid = $_POST['gid'];
		} else {
			$gid = NULL;
		}
		$page = 'groupe.inc.php';
		$page_title = 'Groupe';
//		$parameters="?gid=".$gid;
		break;
//liste compléte des variétés de la base
	case 'varietes':
		$page = 'varietes.inc.php';
		$page_title = 'Variétés';
		break;
//affiche les informations+images en petit format d'une variété particulière donné comme parametre à travers son id (vid)
	case 'variete':
		if (isset($_GET['vid'])) {
			$vid = $_GET['vid'];
		} elseif (isset($_POST['vid'])) { // Forms
			$vid = $_POST['vid'];
		} else {
			$vid = NULL;
		}
		$page = 'variete.inc.php';
		$page_title = 'Variété';
//		$parameters="?vid=".$vid;
		break;
//affiche en grand une photo particulière d'une variété particulière donnée comme paramètre à travers son id (vid)
	case 'photo':
		if (isset($_GET['vid'])) {
			$vid = $_GET['vid'];
		} elseif (isset($_POST['vid'])) { // Forms
			$vid = $_POST['vid'];
		} else {
			$vid = NULL;
		}
		if (isset($_GET['pid'])) {
			$pid = $_GET['pid'];
		} elseif (isset($_POST['pid'])) { // Forms
			$pid = $_POST['pid'];
		} else {
			$pid = NULL;
		}

		$page = 'photo.inc.php';
		$page_title = 'Photo';
		$parameters="?vid=".$vid." & pid=".$pid;
		break;
//affiche les informations d'une personne donnée comme paramètre à travers son id (personne_id)
	//liste des catalogues de la base
	case 'personnes':
		$page = 'personnes.inc.php';
		$page_title = 'Personnes';
		break;

	case 'personne':
		if (isset($_GET['personne_id'])) {
			$personne_id = $_GET['personne_id'];
		} elseif (isset($_POST['personne_id'])) { // Forms
			$personne_id = $_POST['personne_id'];
		} else {
			$personne_id = NULL;
		}
		$page = 'personne.inc.php';
		$page_title = 'Personne';
		break;

	case 'rimg':
		$page = 'recherche.inc.php';
		$page_title = 'Recherche';
		break;
	
	case 'contact':
		$page = 'contact.inc.php';
		$page_title = 'Contact';
		break;
	
	case 'search':
		$page = 'search.inc.php';
		$page_title = 'Resultats de recherche';
		break;
	
	// Default is to include the main page.
	default:
		$page = 'main.inc.php';
		$page_title = 'Roses &amp; Images...';
		break;
		
} // End of main switch.

// Make sure the file exists:
if (!file_exists('./modules/' . $page)) {
	$page = 'main.inc.php';
	$page_title = 'ROSAM';
}

// Include the header file:
include_once ('./includes/header.html');

// Include the content-specific module:
// $page is determined from the above switch.
////include ('./modules/' . $page.$parameters);
include ('./modules/' . $page);

// Include the footer file to complete the template:
include_once ('./includes/footer.html');

?>

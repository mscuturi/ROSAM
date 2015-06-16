<?php # personne.inc.php
/* 
 *	This is the personne module.
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

// Check for a general product ID in the URL.
$titre = NULL;
if (isset($_GET['personne_id'])) {
	// Typecast it to an integer:
	$idPersonne = (int) $_GET['personne_id'];
	
	// $idPersonne must have a valid value.+
	if ($idPersonne > 0) {
		// Get the information from the database
		// for this person:
		
		/////A faire : get idMetier + metier dans la table des Metiers
		////		//données personne + idMetier + role dans le film + société de production
/*	$q = "SELECT film_has_personne.role, personne.idPersonne, personne.nom, personne.prenom, personne.DateNaissance, personne.DateDeces, personne.biographie, personne.MetierPersonne_idMetierPersonne, personne.SocieteProduction_idSocieteProduction FROM (film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm) left join personne on film_has_personne.Personne_idPersonne=personne.idPersonne WHERE film_catalogue.Film_idFilm=$film_id";
*/
		
		
		$q = "SELECT personne.nom, personne.prenom, personne.DateNaissance, personne.DateDeces, personne.biographie, personne.idPersonne, personne.MetierPersonne_idMetierPersonne, personne.SocieteProduction_idSocieteProduction FROM  personne WHERE idPersonne=$personne_id";
		
		
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_num_rows($r) == 1) {
			list($nom, $prenom, $datenaissance, $datedeces, $biographie, $idPersonne, $idMetierPersonne, $idSocieteProduction) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.
	
	} // End of ($idPersonne > 0) IF.
	
} // End of isset($_GET['film_id']) IF.

// Use the name as the page title:
if ($nom) {
	$page_title = $nom." ".$prenom;
	echo "<h1>$nom"." "."$prenom</h1>\n";
	
	//récuperer metier dans la table metierpersonne
	if ($idMetierPersonne){
		$qMetier = "SELECT nommetier FROM metierpersonne WHERE idMetierPersonne=$idMetierPersonne;";					
		$rMetier = mysqli_query($dbc, $qMetier);

		if (mysqli_num_rows($rMetier) == 1) {
			list ($strMetier) = mysqli_fetch_array($rMetier, MYSQLI_NUM);
		}
		
		if($strMetier){echo "<p><em>Métier</em> :  $strMetier </em></p>";}									
	}
	//récuperer societe production dans la table societeproduction
	if ($idSocieteProduction){
		$qSociete = "SELECT nom FROM societeproduction WHERE idSocieteProduction=$idSocieteProduction;";					
		$rSociete = mysqli_query($dbc, $qSociete);

		if (mysqli_num_rows($rSociete) == 1) {
			list ($strSociete) = mysqli_fetch_array($rSociete, MYSQLI_NUM);
		}
		
		if($strSociete){echo "<p><em>Société de production</em> :  $strSociete </em></p>";}									
	}
	
	
	if ($datenaissance) {echo "<p><em>Date de naissance</em> : $datenaissance </p>";}
	if ($datedeces) {echo "<p><em>Date de décés</em> : $datedeces </p>";}
	if ($biographie) {echo "<p><em>Biographie</em> : $biographie </p>";}
	
} else { // Invalid $_GET['film_id']!
	echo '<p class="error">Erreur d\'access.</p>';
}

?>

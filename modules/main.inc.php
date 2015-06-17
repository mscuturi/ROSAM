<?php # main.inc.php

/* 
 *	This is the main content module.
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

	  <h2>Objectifs</h2> 
      <!-- <h3><a href="http://www.univ-lyon2.fr">Université Lumière Lyon 2</a></h3> -->
	  <p>Utiliser les nouvelles technologies au service de :
		<ul>
        	<li>la capitalisation des connaissances sur les plantes en général, les roses en spécial (à travers des informations textuelles mais aussi des images et des vidéos) <!-- réunir les informations souvent partielles quon peut trouver dans des livres, encyclopédies dans une base de données en-ligne, accessible de partout (sans ou avec identification ?)--></li>
			<li>la valorisation des collections des roses anciennes et nouvelles</li>
			<li>la diffusion d'informations sur les anciennes/nouvelles variétés</li>
			<li>l'aide à l'identification des variétés de roses</li>
			<!--<li>l'aide à la localisation des variétés (<a href="http://www.worldrose.org/conservation/wfrssrch.asp"> The World Federation of Rose Societies </a>) a créé une base de données qui permet de localiser l'endroit de conservation d'une variété au niveau d'un (grand) jardin public, mais pas la localisation dans le jardin, on n'a pas des informations sur les mises à jour de la bdd).</li>
			<li>l'aide à la recherche/localisation des pépinières (jardineries) qui proposent certaines variétés dans une région spécifiée</li>
			<li>aménagement du territoire > variétés les plus appropriées en fonction de zone climatique, etc., mise en situation > google maps/street view + simulations aménagements paysagers ?</li>
			<li>base de données avec images géolocalisées (ou au moins une information sur la région) pour pouvoir voir le comportement/l'apparence (growth habit la croissance) des plantes dans les mêmes conditions climatiques (ou dans des conditions différentes)</li>-->
            </ul>
	</p>
	<h2><a href="https://docs.google.com/forms/d/1vZlhgLyM8c_lp6xWIB-AEPXFGK4pPUiKQf1GOv0W1Hc/viewform"> Enquête Tête d'Or - 12.06.2015</a></h2>
	//<iframe src="https://docs.google.com/forms/d/1vZlhgLyM8c_lp6xWIB-AEPXFGK4pPUiKQf1GOv0W1Hc/viewform?embedded=true" width="760" height="1000" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>

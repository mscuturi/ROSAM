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
      <!-- <h3><a href="http://www.univ-lyon2.fr">Universit� Lumi�re Lyon 2</a></h3> -->
	  <p>Utiliser les nouvelles technologies au service de :
		<ul>
        	<li>la capitalisation des connaissances sur les plantes en g�n�ral, les roses en sp�cial (� travers des informations textuelles mais aussi des images et des vid�os) <!--� r�unir les informations souvent partielles qu�on peut trouver dans des livres, encyclop�dies dans une base de donn�es en-ligne, accessible de partout (sans ou avec identification ?)--></li>
			<li>la valorisation des collections des roses anciennes et nouvelles</li>
			<li>la diffusion d�informations sur les anciennes/nouvelles vari�t�s</li>
			<li>l�aide � l�identification des vari�t�s de roses</li>
			<!--<li>l�aide � la localisation des vari�t�s (<a href="http://www.worldrose.org/conservation/wfrssrch.asp"> The World Federation of Rose Societies </a>) a cr�� une base de donn�es qui permet de localiser l�endroit de conservation d�une vari�t� � au niveau d�un (grand) jardin public, mais pas la localisation dans le jardin, on n�a pas des informations sur les mises � jour de la bdd).</li>
			<li>l�aide � la recherche/localisation des p�pini�res (jardineries) qui proposent certaines vari�t�s dans une r�gion sp�cifi�e</li>
			<li>am�nagement du territoire > vari�t�s les plus appropri�es en fonction de zone climatique, etc., mise en situation > google maps/street view + simulations am�nagements paysagers ?</li>
			<li>base de donn�es avec images g�olocalis�es (ou au moins une information sur la r�gion) pour pouvoir voir le comportement/l�apparence (growth habit � la croissance) des plantes dans les m�mes conditions climatiques (ou dans des conditions diff�rentes)</li>-->
            </ul>
	</p>
	<h2><a href="https://docs.google.com/forms/d/1vZlhgLyM8c_lp6xWIB-AEPXFGK4pPUiKQf1GOv0W1Hc/viewform"> Enqu�te T�te d'Or - 12.06.2015</a></h2>
	//<iframe src="https://docs.google.com/forms/d/1vZlhgLyM8c_lp6xWIB-AEPXFGK4pPUiKQf1GOv0W1Hc/viewform?embedded=true" width="760" height="1000" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>

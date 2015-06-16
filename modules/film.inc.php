<?php # films.inc.php

/* 
 *	This is the film module.
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

// Check for a general product ID in the URL.
$titre = NULL;
if (isset($_GET['film_id'])) {

	// Typecast it to an integer:
	$film_id = (int) $_GET['film_id'];
	
	// $film_id must have a valid value.+
	if ($film_id > 0) {
	
		// Get the information from the database
		// for this film:
		/*
		SELECT film_catalogue.Titre, film_catalogue.Numero, film_catalogue.NumeroComplementaire, film_catalogue.Catalogue_idCatalogue, film_catalogue.Resume, film_catalogue.Version, film_catalogue.Prix, film_catalogue.Longueur, film_catalogue.Code, film_catalogue.Genre_idGenre, personne.nom, personne.prenom, personne.DateNaissance, personne.DateDeces, personne.biographie FROM (film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm) left join personne on film_has_personne.Personne_idPersonne=personne.idPersonne WHERE film_catalogue.Film_idFilm=3584
		
		*/
		
		/*$q = "SELECT film_catalogue.Catalogue_idCatalogue, film_catalogue.Titre, film_catalogue.Numero, film_catalogue.NumeroComplementaire, film_catalogue.Catalogue_idCatalogue, film_catalogue.Resume, film_catalogue.Version, film_catalogue.Prix, film_catalogue.Longueur, film_catalogue.Code, film_catalogue.Genre_idGenre, personne.nom, personne.prenom, personne.DateNaissance, personne.DateDeces, personne.biographie, personne.idPersonne FROM (film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm) left join personne on film_has_personne.Personne_idPersonne=personne.idPersonne WHERE film_catalogue.Film_idFilm=$film_id";
		*/
		
		$q = "SELECT film_catalogue.Catalogue_idCatalogue, film_catalogue.Titre, film_catalogue.Numero, film_catalogue.NumeroComplementaire, film_catalogue.Catalogue_idCatalogue, film_catalogue.Resume, film_catalogue.Version, film_catalogue.Prix, film_catalogue.Longueur, film_catalogue.Code, film_catalogue.Genre_idGenre, film_catalogue.Serie_idSerie FROM film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm WHERE film_catalogue.Film_idFilm=$film_id";
		
		$r = mysqli_query($dbc, $q);
		
		if (mysqli_num_rows($r) == 1) {
			list ($idCatalogue, $titre, $numero, $numeroComplementaire, $idCatalogue, $resume, $version, $prix, $longueur, $code, $idGenre, $idSerie) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.
	
	} // End of ($film_id > 0) IF.
	
} // End of isset($_GET['film_id']) IF.

// Use the name as the page title:
if ($titre) {
	$page_title = $titre;
}
//// !!!!!il peut y avoir plusieurs personnes pour un film, ici il affiche 1 seule !!!!

if ($titre) { // Show the specific products.
	
	echo "<br /><h1>$titre</h1>\n<br />";
	//////if (($nom)&&($prenom)) {echo "<p><em>Cinéaste</em> : <a href=\"index.php?p=personne&personne_id=$idPersonne\">$nom $prenom </a></p>";}
/*	
	//a mettre dans la fiche Personne
	if ($datenaissance) {echo "<p><em>Date de naissance</em> : $datenaissance </p>";}
	if ($datedeces) {echo "<p><em>Date de décés</em> : $datedeces </p>";}
	if ($biographie) {echo "<p><em>Biographie</em> : $biographie </p>";}
*/

	//get the catalog name+version
	
		$q = "SELECT NomCatalogue, Fichier, Version, Annee, Mois FROM catalogue WHERE idCatalogue=$idCatalogue";
		$r = mysqli_query($dbc, $q);
		
		// Fetch the information:
		if (mysqli_num_rows($r) == 1) {
			list ($catalogue, $fichier, $versionCatalogue, $annee, $mois) = mysqli_fetch_array($r, MYSQLI_NUM);
		} // End of mysqli_num_rows() IF.

	if ($catalogue) {
		if($annee && $mois){
			echo "<p><em>Catalogue</em> : ".$catalogue." ".$annee."-".$mois." ".$versionCatalogue."</p>\n";
		}
		else {
			echo "<p><em>Catalogue</em> : ".$catalogue." ".$annee." ".$versionCatalogue."</p>\n";
		}
	}
		// Get the persons associated to this film.
		//données personne + idMetier + role dans le film + société de production
/*	$q = "SELECT film_has_personne.role, personne.idPersonne, personne.nom, personne.prenom, personne.DateNaissance, personne.DateDeces, personne.biographie, personne.MetierPersonne_idMetierPersonne, personne.SocieteProduction_idSocieteProduction FROM (film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm) left join personne on film_has_personne.Personne_idPersonne=personne.idPersonne WHERE film_catalogue.Film_idFilm=$film_id";
*/

	if ($numero) {echo "<p><em>Numéro</em> : $numero </p>";}
	if ($numeroComplementaire) {echo "<p><em>Numéro complémentaire</em> : $numeroComplementaire </p>";}
	if ($resume) {echo "<p><em>Résumé</em> : $resume </p>";}
	//serie
	//récuperer la série dans la table serie
	if ($idSerie){
		$qSerie = "SELECT nomSerie FROM serie WHERE idSerie=$idSerie;";					
		$rSerie = mysqli_query($dbc, $qSerie);

		if (mysqli_num_rows($rSerie) == 1) {
			list ($strSerie) = mysqli_fetch_array($rSerie, MYSQLI_NUM);
		}
		
		if($strSerie){echo "<p><em>Série</em> :  $strSerie </em></p>";}									
	}

	//genre
	if ($idGenre){
		$qGenre = "SELECT nomGenre FROM genre WHERE idGenre=$idGenre;";					
		$rGenre = mysqli_query($dbc, $qGenre);

		if (mysqli_num_rows($rGenre) == 1) {
			list ($strGenre) = mysqli_fetch_array($rGenre, MYSQLI_NUM);
		}
		
		if($strGenre){echo "<p><em>Genre</em> :  $strGenre </em></p>";}									
	}
	
	
	
	if ($version) {echo "<p><em>Version</em> : $version </p>";}
	if ($prix) {echo "<p><em>Prix</em> : $prix </p>";}
	if ($longueur) {echo "<p><em>Longueur</em> : $longueur </p>";}
	if ($code) {echo "<p><em>Code</em> : $code </p>";}

	$q = "SELECT film_has_personne.role, personne.idPersonne, personne.nom, personne.prenom, personne.MetierPersonne_idMetierPersonne, personne.SocieteProduction_idSocieteProduction FROM (film_catalogue left join film_has_personne on film_catalogue.Film_idFilm=film_has_personne.Film_idFilm) inner join personne on film_has_personne.Personne_idPersonne=personne.idPersonne WHERE film_catalogue.Film_idFilm=$film_id";
	$r = mysqli_query($dbc, $q);
	
	if (mysqli_num_rows($r) >= 1) {
	
		// Print each person :
		echo '<h3>Personne(s) en lien avec le film :</h3>';
	
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			////echo " while : ".$row['idPersonne'].$row['nom'].$row['prenom'];
			if (($row['nom'])&&($row['prenom'])) {
				$idPersonne=$row['idPersonne'];
				$nom=$row['nom'];
				$prenom=$row['prenom'];
				//echo "$idPersonne $nom $prenom";
				echo "<p><a href=\"index.php?p=personne&personne_id=$idPersonne\">".$nom." ".$prenom." </a>";
				// Determine if the role is the same with the "metier" pour afficher le role different, sinon on affiche une seule fois :
				if (!empty($row['role'])&&(!empty($row['MetierPersonne_idMetierPersonne'])) && ($row['role'] == $row['MetierPersonne_idMetierPersonne'])) {				$role=$row['role'];
					echo " <br /> <em>Métier : </em>";
					//select le nom du role à partir de la table metierpersonne
					$qRole = "SELECT nommetier FROM metierpersonne WHERE idMetierPersonne=$role;";					
					$rRole = mysqli_query($dbc, $qRole);
	
					if (mysqli_num_rows($rRole) == 1) {
						list ($strRole) = mysqli_fetch_array($rRole, MYSQLI_NUM);
					}
					
					if($strRole){echo "$strRole";}
				
				} else //if role!=metier
				{
					if (!empty($row['role']))	{
					//afficher le role dans le film				
						$role=$row['role'];
						echo " <br /> <em> Rôle dans le film : </em>";
						//select le nom du role à partir de la table metierpersonne
						$qRole = "SELECT nommetier FROM metierpersonne WHERE idMetierPersonne=$role;";					
						$rRole = mysqli_query($dbc, $qRole);
		
						if (mysqli_num_rows($rRole) == 1) {
							list ($strRole) = mysqli_fetch_array($rRole, MYSQLI_NUM);
						}
						
						if($strRole){echo "$strRole";}									
					}
					if (!empty($row['MetierPersonne_idMetierPersonne']))	{
					//afficher le metier				
						$metier=$row['MetierPersonne_idMetierPersonne'];
						echo " <br /> <em>Métier : </em>";
						//select le nom du métier à partir de la table metierpersonne
						$qMetier = "SELECT nommetier FROM metierpersonne WHERE idMetierPersonne=$metier;";					
						$rMetier = mysqli_query($dbc, $qMetier);
		
						if (mysqli_num_rows($rMetier) == 1) {
							list ($strMetier) = mysqli_fetch_array($rMetier, MYSQLI_NUM);
						}
						
						if($strMetier){echo "$strMetier";}									
					}

				}
				echo "</p>";
			}
				
			/*
			$price = (empty($row['price'])) ? $price : $row['price'];
		
			// Print most of the information:
			echo "<p>Size: {$row['size']}<br />Color: {$row['color']}<br /> Price: \$$price<br />In Stock?: {$row['in_stock']}";
			
			// Print cart link:
			if ($row['in_stock'] == 'Y') {
				echo "<br /> <a href=\"cart.php?sw_id={$row['sw_id']}&do=add\">Add to Cart</a>";
			}
			
			echo '</p>';
			*/
			
		} // End of WHILE loop.
		
	} else { // No person here!
		////echo '<p class="error">Aucune personne en lien avec le film.</p>';
	}/////////
	
	
	//echo "<p>filmId : $film_id</p>";
	
	
	
	/*
	// Print the product description, if it's not empty.
	if (!empty($description)) {
		echo "<p>$description</p>\n";
	}
	

*/
} else { // Invalid $_GET['film_id']!
	echo '<p class="error">Erreur d\'access.</p>';
}

?>

<?php 
function setFlashMessage($message, $type = 'success') {
	$_SESSION['flashMessage'] = [
		'message' => $message,
		'type' => $type
	];
}

function displayFlashMessage() {
	if (isset($_SESSION['flashMessage'])) {
		$message = $_SESSION['flashMessage']['message'];
		$type = ($_SESSION['flashMessage']['type'] == 'error')
			? 'danger' // pour la classe alert-danger du bootstrap.
			: $_SESSION['flashMessage']['type']
		;
		echo '<div class="alert alert-' . $type . '">' . '<h5 class="alert-heading">' . $message . '</h5>' . '</div>';
		// suppression du msg de la session pour affichage "one shot":
		unset($_SESSION['flashMessage']);
	}
}

function sanitizeValue(&$value) 
{
	// trim() supprime les espaces en début et fin de chaîne
	// strip_tags() supprime les balises HTML
	$value = trim(strip_tags($value));
	$striped = strip_tags($value);
	$value = trim($striped);
}


function sanitizeArray(array &$array) { // on à 'typé' la valeur, forçant a n'entré qu'un type bien défini de valeur (ici une valeur de type array)
	//applique la fonction sanitizeValue() sur tous les éléments du tableau.
	array_walk($array, 'sanitizeValue');
}

function sanitizePost() {
	sanitizeArray($_POST);
}

function isUserConnected() {
	return isset($_SESSION['utilisateur']);
}

function getUserFullName()
{
	if (isUserConnected()){
		return $_SESSION['utilisateur']['Prenom'] . ' ' . $_SESSION['utilisateur']['Nom'];
	}
}

function isUserAdmin() {
	return isUserConnected() && $_SESSION['utilisateur']['role'] == 'admin';
}

function adminSecurity()
{
	if (!isUserAdmin()) {
		if (!isUserConnected()) {
			header('Location:' . RACINE_WEB . 'connexion.php');
		} else {
			header('HTTP/1.1 403 Forbidden');
			echo "Vous n'avez pas le droit d'accéder à cette page!!";
		}
		die;
	}
}

function prixFr($prix) {
	return number_format($prix, 2, ',', ' ') . ' €';
}

function dateFr($dateSql) {
	return date('d/m/Y à H:i:s', strtotime($dateSql));
}

function ajoutPanier(array $produit, $quantite) {
	if (!isset($_SESSION['panier'])){ // initialisation du panier.
		$_SESSION['panier'] = [];
	}

	if (!isset($_SESSION['panier'][$produit['id']])) { // le produit n'est pas encore dans le panier, on l'y ajoute.
		$_SESSION['panier'][$produit['id']] = [
			'nom' => $produit['nom'],
			'prix' => $produit['prix'],
			'quantite' => $quantite
		];
	} else { // si le produit est déjà dans le panier, on met à jour la quantité
		$_SESSION['panier'][$produit['id']]['quantite'] += $quantite;
	}
}

function getTotalPanier() {
	$total = 0;
	if (isset($_SESSION['panier'])) {
		foreach ($_SESSION['panier'] as $produit) {
			$total += $produit['prix'] * $produit['quantite'];
		}
	}
	return $total;
}

function modifierQuantitiePanier($produitId, $quantite) {
	if(isset($_SESSION['panier'][$produitId])) {
		if($quantite != 0) {
			$_SESSION['panier'][$produitId]['quantite'] = $quantite;
		} else {
			unset($_SESSION['panier'][$produitId]);
		}
	}
}
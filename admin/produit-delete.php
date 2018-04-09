<?php 
require_once __DIR__ . '/../include/init.php';
adminSecurity();

$query = 'SELECT photo FROM produit WHERE id = ' . $_GET['id'];
$stmt = $pdo->query($query);
$photo = $stmt->fetchColumn();

if(!empty($photoActuell)) { // on supprime l'image du produit dans le répertoire photo s'il en a une
	unlink(PHOTO_DIR . $photoActuell);
}

$query = 'DELETE FROM produit WHERE id = ' . $_GET['id'];
$pdo->exec($query);

setFlashMessage('Le produit est supprimée !!');

header('Location: produit.php');
die;
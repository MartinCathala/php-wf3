<?php
require_once __DIR__ . '/include/init.php';

if (isset($_POST['commander'])){
	/* Enregistrer la commande et son détail en bdd, afficher une message de confirmation et vider le panier pour finir! */
	$query = <<<EOS
INSERT INTO commande (
	utilisateur_id,
	montant_total
) VALUES (
	:utilisateur_id,
	:montant_total
	)
EOS;
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':utilisateur_id', $_SESSION['utilisateur']['idUser']);
	$stmt->bindValue(':montant_total', getTotalPanier());
	$stmt->execute();
	$commandId = $pdo->lastInsertId(); // récupération de l'Id d ela commande qu'on vient d'insérer.
	// 1 Insertion de la table detail_commande
	$query = <<<EOS
INSERT INTO detail_commande (
	command_id,
	produit_id,
	prix,
	quantite
) VALUE (
	:command_id,
	:produit_id,
	:prix,
	:quantite
)	
EOS;
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':command_id', $commandId);

	foreach ($_SESSION['panier'] as $produitId => $produit) {
		$stmt->bindValue(':produit_id', $produitId);
		$stmt->bindValue(':prix', $produit['prix']);
		$stmt->bindValue(':quantite', $produit['quantite']);
		$stmt->execute();
	}
	setFlashMessage('La commande est enregistrée !');
	$_SESSION['panier'] = []; // ici on vide le panier.
}

if (isset($_POST['modifier-quantite'])) {
	modifierQuantitiePanier($_POST['produit-id'], $_POST['quantite']);
}

include __DIR__ . '/layout/top.php';
?>
<h1>Votre panier</h1>
<?php 
if (empty($_SESSION['panier'])):
?> 
<div class="alert alert-info">
	Le panier est vide!
</div>

<?php 
else :
?>
	<table class="table">
		<tr>
			<th>Nom produit</th>
			<th>Prix unitaire</th>
			<th>Quantité</th>
			<th>Total</th>
		</tr>
	<?php 
	foreach ($_SESSION['panier'] as $produitId => $produit) :
	?>
	<tr>
		<td><?= $produit['nom']; ?></td>
		<td><?= prixFr($produit['prix']); ?></td>
		<td>
			<form method="post" class="form-inline">
				<input type="number" name="quantite" value="<?= $produit['quantite']; ?>" class="form-control col-sm-2" min="0">
				<input type="hidden" value="<?= $produitId; ?>" name="produit-id">
				<button type="submit" class="btn btn-primary" name="modifier-quantite">Modifier</button>
			</form>
		</td>
		<td><?= prixFr($produit['prix'] * $produit['quantite']); ?></td>
	</tr>
	<?php 
	endforeach;
	?>
	<tr>
		<th colspan="3">Total</th>
		<td><?= prixFr(getTotalPanier()); ?></td>
	</tr>
<?php 
endif;
?>
	</table>

<?php 
if (isUserConnected()) :
?>
	<form method="post">
		<p class="text-right">
			<button type="submit" name="commander" class="btn btn-primary"> Valider la commande. </button>
		</p>
	</form>
<?php 
else :
?>
	<div class="alert alert-info">
		Vous devez vous connecter ou vous inscrire pour valider la commande!
	</div>
<?php 
endif;
?>

<?php 
include __DIR__ . '/layout/bottom.php';
?> 
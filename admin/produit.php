<?php 
require_once __DIR__ . '/../include/init.php';

adminSecurity();

// 'FROM produit p' definit un alias où 'p' est l'alias de 'produits'. Même chose pour 'JOIN categorie c' où 'c' est l'alias de 'categorie'.
// 'categorie_nom' est l'alias du champ nom de la table categorie.
// 'p.*' est l'alias de tous les champs de la table 'produit'.
$query = <<<EOS
SELECT p.*, c.nom AS categorie_nom 
FROM produit p 
JOIN categorie c ON p.categorie_id = c.id
EOS;
$stmt = $pdo->query($query);

$produits = $stmt->fetchAll();

include __DIR__ . '/../layout/top.php';
?>
<h1>Gestion Produits</h1>

<p><a href="produit-edit.php" class="btn btn-info">Ajouter un produit</a></p>

<table class="table">
	<tr>
		<th>Id</th>
		<th>Nom</th>
		<th>Réference</th>
		<th>Prix</th>
		<th>Catégoris</th>
		<th width="250px"></th>
	</tr>
	<?php 
foreach ($produits as $produit) :
	?>
	<tr>
		<td><?= $produit['id']; ?> </td>
		<td><?= $produit['nom']; ?> </td>
		<td><?= $produit['reference']; ?> </td>
		<td><?= prixFr($produit['prix']); ?> </td>
		<td><?= $produit['categorie_nom']; ?> </td>
		<td><a href="produit-edit.php?id=<?= $produit['id']; ?>" class="btn btn-info">
				Modifier
			</a>
			<a href="produit-delete.php?id=<?= $produit['id']; ?>" class="btn btn-danger">
				Supprimer
			</a>
			
		</td>
	</tr>
	<?php 
	endforeach; ?>
</table>
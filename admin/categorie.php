<?php 
require_once __DIR__ . '/../include/init.php';

// Lister les catégories dans un tableau HTML

// Le requêtage ici
$query = 'SELECT * FROM categorie';
$stmt = $pdo -> query($query);
$categories = $stmt->fetchAll();

include __DIR__ . '/../layout/top.php';
?>
<h1>Gestion catégories</h1>

<p><a class="btn btn-info" href="categorie-edit.php">Ajouter une catégorie</a></p>

<!-- Le tableau HTML ici -->
<table class="table">
	<tr>
		<th>Id</th>
		<th>Nom</th>
		<th width="250px"></th>
	</tr>
	<?php 
	//	une boucle pour avoir un tr avec 2 td pour chaque catégorie.
	foreach ($categories as $categorie) : // les {} sont remplacer par ':' a louverture et fermés par 'endforeach'. Ce seulement pour facilité la distinction entre le code php et l'html, tout simplement.?>
	<tr>
		<td><?= $categorie['id']; ?></td>
		<td><?= $categorie['nom']; ?></td>
		<td>
			<a href="categorie-edit.php?id=<?= $categorie['id']; ?>" class="btn btn-info">
				Modifier
			</a>
			<a href="categorie-delete.php?id=<?= $categorie['id']; ?>" class="btn btn-danger">
				Supprimer
			</a>
		</td>
	</tr>
	<?php endforeach;
	?>
</table>

<?php 
include __DIR__ . '/../layout/bottom.php';
?>
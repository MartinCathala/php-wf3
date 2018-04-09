<?php
/* 
lister les commandes dans un tableau HTML :
- id de la commande
- nom prénom de l'utilisateur qui a passé la comande
- montant formaté
- date de la commande formatée (functions date() et strtotime() de php)
- statuts
- date de la statut formatée (functions date() et strtotime() de php)
Passer le statut en liste déroulante (en cours, envoyé, livré) avec un bouton modifier pour changer le statut de la commande
	=> traiter le changement de statut en mettant à jour statut et date_statut dans la table commande.
*/
require_once __DIR__ . '/../include/init.php';
adminSecurity();

if (isset($_POST['modifier-statut'])) {
	$query = 'UPDATE commande SET'
		. ' statut = :statut,'
		. ' date_statut = now()'
		. ' WHERE id = :id';
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':statut', $_POST['statut']);
		$stmt->bindValue(':id', $_POST['command-id']);
		$stmt->execute();
var_dump($_POST);

		setFlashMessage('Le statut est modifé!');
}
// 'c = command' est établie aprés le select car PHP lis en priorité ce qui suit 'FROM' et ce qui suit derriére en priorité!
$query = "SELECT c.*, concat_ws('', u.nom, u.prenom) AS utilisateur" 
	. ' FROM commande c' 
	. ' JOIN utilisateur u ON c.utilisateur_id = u.idUser';

$stmt = $pdo->query($query);
$commandes = $stmt->fetchAll();

$statuts = [
	'En cours',
	'envoyé',
	'livré'
];

include __DIR__ . '/../layout/top.php';
?>
<h1>Commandes</h1>

<table class="table">
	<tr>
		<th>Id</th>
		<th>Utilisateur</th>
		<th>Montant total</th>
		<th>Date</th>
		<th>Statut</th>
		<th>Date MAJ statut</th>
	</tr>
	<?php 
	foreach ($commandes as $comande) :
	?>
	<tr>
		<td><?= $comande['id']; ?></td>
		<td><?= $comande['utilisateur']; ?></td>
		<td><?= prixFr($comande['montant_total']); ?></td>
		<td><?= dateFr($comande['date_commande']); ?></td>
		<td>
			<form method="post" class="form-inline">
				<select name="statut" class="form-control">
					<?php 
					foreach ($statuts as $statut) :
						$selected = ($statut == $comande['statut'])
							? 'selected'
							: ''
					?>
						<option value="<?= $statut; ?>"<?= $selected; ?>><?= ucfirst($statut); ?></option>
					<?php 
					endforeach;
					?>
				</select>
				<input type="hidden" name="command-id" value="<?= $comande['id'] ?>">
				<button type="submit" name="modifier-statut" class="btn btn-primary">
					Modifier
				</button>
			</form>
		</td>
		<td><?= dateFr($comande['date_statut']); ?></td>
	</tr>
	<?php 
	endforeach;
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
include __DIR__ . '/../layout/bottom.php';
?>
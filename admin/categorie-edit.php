<?php 
require_once __DIR__ . '/../include/init.php';

$errors = [];
$nom = '';

if (!empty($_POST)) { // si on a des données venant du formulaire :
	// "nettoyage" des données venues du formulaire
	sanitizePost();
	// crée des variables à partir d'un tableau (les variables ont les noms des clés dans le tableau)

	extract($_POST); // crée des variables à partir d'un tableau (les variables ont les noms des clés dans le tableau).

	if (empty($_POST['nom'])) { // test la saisie du champ nom :
		$errors[] = 'Le nom est obligatoire';
	} elseif (strlen($_POST['nom']) > 50) {
		$errors [] = 'Le nom ne doit pas faire plus de 50 caractères';
	}
	// si le formulaire est correctement rempli :
	if (empty($errors)) {
		if (isset($_GET['id'])) {
			$query = 'UPDATE categorie SET nom = :nom WHERE id = :id';
			$stmt = $pdo->prepare($query);
			$stmt->bindvalue(':nom', $_POST['nom']);
			$stmt->bindvalue(':id', $_POST['id']);
			$stmt->execute();
		} 
		else {
			// insertion en bdd:
			$query = 'INSERT INTO categorie(nom) VALUES(:nom)';
			$stmt = $pdo->prepare($query);
			$stmt->bindvalue(':nom', $_POST['nom']);
			$stmt->execute();
		}
		// enregistrement d'un message de session :
		setFlashMessage('La catégorie est enregistrée!');

		// redirection vers la page de la liste :
		header('Location: categorie.php');
		die; // ou 'exit;' une fonction qui arrêtera la lecture du rest du script derrières.
	}
} 
elseif (isset($_GET['id'])) { // en modification, si on n'a pas de retour de formulaire on va chercher la catégorie en bdd pour affichage.
	$query = 'SELECT * FROM categorie WHERE id = ' . $_GET['id'];
	$stmt = $pdo->query($query);
	$categorie = $stmt->fetch();
	$nom = $categorie['nom'];
}

include __DIR__ . '/../layout/top.php';


if (!empty($errors)) :
?>
	<div class="alert alert-danger">
		<h4 class="alert-heading">Le formulaire contient des erreurs</h4>
		<?= implode('<br>', $errors); // implode transforme un tableau en chaîne de cractères ?>
	</div>
<?php endif; ?>

<h1>Edition catégories</h1>
<?php 
$tab = ['a', 'b', 'c'];
echo implode(', ', $tab); // a, b, c.
?>
<form method="post">
	<div class="form-group">
		<label>Nom</label>
		<input type="text" name="nom" class="form-control" value="<?= $nom; ?>">
	</div>
	<div class="form-btn-group text-right">
		<button class="btn btn-primary" type="submit">Enregistrer</button>
		<a href="categorie.php" class="btn btn-secondary">Retour</a>
	</div>
</form>

<?php 
include __DIR__ . '/../layout/bottom.php';
?>
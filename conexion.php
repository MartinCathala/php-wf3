<?php 
require_once __DIR__ . '/include/init.php';

$email = '';

if (!empty($_POST)){
	sanitizePost();
	extract($_POST);

	if(empty($_POST['email'])) {
		$errors[] = "L'email est obligatoire.";
	}
	if(empty($_POST['mdp'])){
		$errors[] = 'Le mot de passe est obligatoire!!';
	}
	if (empty($errors)) {
		$query = 'SELECT * FROM utilisateur WHERE email = :email';
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':email', $_POST['email']);
		$stmt->execute();

		$utilisateur = $stmt->fetch();
		// si on a un utilisateur en bdd avec l'email saisi :
		if (!empty($utilisateur)) {
			// si le mdp saisi correspond au mdp en crypté en bdd :
			if (password_verify($_POST['mdp'], $utilisateur['mdp'])) {
				// connecter un utilisateur, c'est l'enregistrer en session.
				$_SESSION['utilisateur'] = $utilisateur;

				header('Location: index.php');
				die;
			}
		}

		$errors[] = 'Identifiant ou le mot de passe incorrect!';
	}
}

include __DIR__ . '/layout/top.php';

if (!empty($errors)) : 
?>
	<div class="alert alert-danger">
		<h5 class="alert-heading">Le formulaire contient des erreurs!</h5>
		<?= implode('<br>', $errors); // implode transforme un tableau en chaîne de cractères ?>
	</div>
<?php endif; ?>

<h1>Inscription</h1>

<form method="post">
	<div class="form-group">
		<label>Email</label>
		<input type="text" name="email" value="<?php $email ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Mot de Passe</label>
		<input type="password" name="mdp" class="form-control">
	</div>
	<div class="form-btn-group text-right">
		<button type="submit" class="btn btn-primary">Se connecter</button>
	</div>
</form>

<?php
include __DIR__ . '/layout/bottom.php';
?>
<?php 
require_once __DIR__ . '/include/init.php';

$errors = [];
$civilite = $nom = $prenom = $email = $ville = $cp = $adresse = '';
if (!empty($_POST)){
	sanitizePost();
	extract($_POST);

	if (empty($_POST['nom'])) {
		$errors[] = 'Le nom est obligatoire.';
	}

	if (empty($_POST['prenom'])) {
		$errors[] = 'Le prenom est obligatoire.';
	}

	if (empty($_POST['email'])) {
		$errors[] = 'Email obligatoire.';
	} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errors[] = "L'Email est invalide!";
	} else {
		$query = 'SELECT count(*) FROM utilisateur WHERE email = :email';
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':email', $_POST['email']);
		$stmt->execute();
		$nb = $stmt->fetchColumn();

		if ($nb != 0) {
			$errors[] = "Cet email est déja pris par un autre utilisateur!!";
		}
	}
	if (empty($_POST['ville'])) {
		$errors[] = 'La ville est obligatoire.';
	}
	if (empty($_POST['cp'])) {
		$errors[] = 'Le code postal est obligatoire.';
		// ctype_digit() renvoit true si la chaîne ne contient que des chiffres, false sinon.
	} elseif (strlen($_POST['cp']) != 5 || !ctype_digit($_POST['cp'])) {
		$errors[] = 'Le code postal est invalide!!';
	}
	if (empty($_POST['adresse'])) {
		$errors[] = "l'Adresse obligatoire.";
	}
	if (empty($_POST['mdp'])) {
		$errors[] = 'Le mot de passe est obligatoire.';
	} elseif (!preg_match('/^[a-zA-Z0-9_-]{6,20}$/', $_POST['mdp'])) {
		$errors[] = 'Le mot de passe doit faire entre 6 et 20 caractères et ne  contenire que des chiffres, des lettres et les caractères _ et - .';
	}
	if ($_POST['mdp'] != $_POST['mdp_confirm']) {
		$errors[] = 'Le mot de passe est invalide!!';
	}
	if (empty($errors)) {
		$query = <<<EOS
	INSERT INTO utilisateur(
		nom,
		prenom,
		email,
		mdp,
		civilite,
		ville,
		cp,
		adresse
	) VALUES (
		:nom,
		:prenom,
		:email,
		:mdp,
		:civilite,
		:ville,
		:cp,
		:adresse
	) 
EOS;
	$stmt = $pdo->prepare($query);
	$stmt->bindvalue(':nom', $_POST['nom']);
	$stmt->bindvalue(':prenom', $_POST['prenom']);
	$stmt->bindvalue(':email', $_POST['email']);
	// encodage du mdp à l'enregistrement.
	$stmt->bindvalue(':mdp', password_hash($_POST['mdp'], PASSWORD_BCRYPT));
	$stmt->bindvalue(':civilite', $_POST['civilite']);
	$stmt->bindvalue(':ville', $_POST['ville']);
	$stmt->bindvalue(':cp', $_POST['cp']);
	$stmt->bindvalue(':adresse', $_POST['adresse']);
	$stmt->execute();

	setFlashMessage('Votre compte est créé!');
	header('Location: index.php');
	die;
	}
}

include __DIR__ . '/layout/top.php';

if (!empty($errors)) :
?>
	<div class="alert alert-danger">
		<h4 class="alert-heading">Le formulaire contient des erreurs</h4>
		<?= implode('<br>', $errors); // implode transforme un tableau en chaîne de cractères ?>
	</div>
<?php endif; ?>

<h1>Inscription</h1>

<form method="post">
	<div class="form-group">
		<label>Civilité</label>
		<select name="civilite" class="form-control">
			<option value=""></option>
			<option value="Mme"<?php if ($civilite == 'Mme') {echo 'selected';} ?>>Mme</option>
			<option value="M."<?php if ($civilite == 'M.') {echo 'selected';} ?>>M.</option>
		</select>
	</div>
	<div class="form-group">
		<label>Nom</label>
		<input type="text" name="nom" value="<?php $nom ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Prénom</label>
		<input type="text" name="prenom" value="<?php $prenom ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Email</label>
		<input type="text" name="email" value="<?php $email ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Ville</label>
		<input type="text" name="ville" value="<?php $ville ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Code Postal</label>
		<input type="text" name="cp" value="<?php $cp ?>" class="form-control">
	</div>
	<div class="form-group">
		<label>Adresse</label>
		<textarea type="text" name="adresse" value="" class="form-control"><?php $adresse ?></textarea>
	</div>
	<div class="form-group">
		<label>Mot de Passe</label>
		<input type="password" name="mdp" class="form-control">
	</div>
	<div class="form-group">
		<label>Confirmation de Mot de Passe</label>
		<input type="password" name="mdp_confirm" class="form-control">
	</div>
	<div class="form-btn-group text-right">
		<button type="submit" class="btn btn-primary">Valider</button>
	</div>
</form>

<?php
include __DIR__ . '/layout/bottom.php';
?>
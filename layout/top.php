<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Boutique</title>
  </head>
  <body>

    <?php  
    if (isUserAdmin()) :
    ?>

  	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
  		<div class="container navbar-nav">
  			<a class="navbar-brand" href="#">Admin</a>
        <div class="navbar-collapse">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="<?= RACINE_WEB; ?>admin/categorie.php">Gestion catégories</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= RACINE_WEB; ?>admin/produit.php">Gestion de Produits</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= RACINE_WEB; ?>admin/commandes.php">Gestion de Commandes</a>
            </li>
          </ul>
        </div>
  		</div>
  	</nav>

    <?php  
    endif;
    ?>

  	<nav class="navbar navbar-expand-md navbar-dark bg-secondary">
  		<div class="container navbar-nav">
  			<a class="navbar-brand" href="<?= RACINE_WEB; ?>index.php">Boutique</a>
        <?php 
        include __DIR__ . '/menu-categorie.php';
        ?>
        <ul class="navbar-nav">

           <?php  
          if (isUserConnected()) :
          ?>
          <li class="nav-item"><a class="nav-link"><?= getUserFullName(); ?></a></li>
          <li class="nav-item"><a href="<?= RACINE_WEB; ?>deconnexion.php" class="nav-link">Deconnexion</a></li>
          <?php  
          else :
          ?>

          <li class="nav-item"><a href="<?= RACINE_WEB; ?>inscription.php" class="nav-link">Inscription</a></li>
          <li class="nav-item"><a href="<?= RACINE_WEB; ?>conexion.php" class="nav-link">Connexion</a></li>

          <?php  
          endif;
          ?>

          <li class="nav-item"><a href="<?= RACINE_WEB; ?>panier.php" class="nav-link">Panier</a></li>

        </ul>
  		</div>
  	</nav>
  	<div class="container">
      <?php  
      displayFlashMessage();
      ?>
  		
<?php
session_start(); // initialise la session

define('RACINE_WEB', '/php/site/');
define('PHOTO_WEB', RACINE_WEB . 'photo/');
// sous xampp, $_SERVER['DOCUMENT_ROOT'] vaut C:\xampp\htdocs
define('PHOTO_DIR', $_SERVER['DOCUMENT_ROOT'] . '/php/site/photo/');
define('PHOTO_DEFAULT', 'https://dummyimage.com/600x400/ccc/ffffff&text=Pas+d\'image');

require_once __DIR__ . '/cnx.php';
require_once __DIR__ . '/fonction.php';
?>
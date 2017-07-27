<?php

// Autoload PSR-4
spl_autoload_register();

// Imports
use \Classes\Webforce3\Config\Config;

// Get the config object
$conf = Config::getInstance();

$cityObject = new City();

// Formulaire soumis
if(!empty($_POST)) {

}

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'city.php';
require $conf->getViewsDir().'footer.php';

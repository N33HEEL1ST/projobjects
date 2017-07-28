<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Location;
use \Classes\Webforce3\DB\Country;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$locationId = isset($_GET['loc_id']) ? intval($_GET['loc_id']) : 0;
$locationObject = new Location();

// Récupère la liste complète des pays et locations en DB
$locationsList = Location::getAllForSelect();
$countriesList = Country::getAllForSelect();

if ($locationId > 0) {
    $locationObject = Location::get($locationId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Location::deleteById(intval($_GET['delete']))) {
		header('Location: location.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Formulaire soumis
if (!empty($_POST)) {
    $locationId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $countryId = isset($_POST['cou_id']) ? intval($_POST['cou_id']) : 0;
    $locationName = isset($_POST['loc_name']) ? trim($_POST['loc_name']) : '';

    
    if (!array_key_exists($countryId, $countriesList)) {
        $conf->addError('Pays non valide');
    }
    if (empty($locationName)) {
        $conf->addError('Veuillez renseigner le nom de la location');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $locationObject = new Location(
            $locationId, $locationName, new Country($countryId)
    );

    // Si tout est ok
    if (!$conf->haveError()) {
        if ($locationObject->saveDB()) {
            header('Location: location.php?success=' . urlencode('Ajout/Modification effectuée') . '&cit_id=' . $locationObject->getId());
            exit;
        } else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}

// Instancie le générateur de menu déroulant pour les cities
$selectLocations = new SelectHelper($locationsList, 0, array(
    'name' => 'loc_id',
    'id' => 'loc_id',
    'class' => 'form-control',
        ));

$selectCountries = new SelectHelper($countriesList, $locationObject->getCountry()->getId(), array(
    'name' => 'cou_id',
    'id' => 'cou_id',
    'class' => 'form-control',
        ));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir() . 'header.php';
require $conf->getViewsDir() . 'location.php';
require $conf->getViewsDir() . 'footer.php';

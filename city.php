<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use Classes\Webforce3\DB\City;
use Classes\Webforce3\DB\Country;
use Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$cityId = isset($_GET['cit_id']) ? intval($_GET['cit_id']) : 0;
$cityObject = new City();

// Récupère la liste complète des cities en DB
$citiesList = City::getAllForSelect();

// Récupère la liste complète des countries en DB
$countriesList = Country::getAllForSelect();

// Si modification d'une ville, on charge les données pour le formulaire
if ($cityId > 0) {
	$cityObject = City::get($cityId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (City::deleteById(intval($_GET['delete']))) {
		header('Location: city.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Formulaire soumis
if(!empty($_POST)) {
    $cityId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $countryId = isset($_POST['cou_id']) ? intval($_POST['cou_id']) : 0;
    $cityName = isset($_POST['cit_name']) ? strip_tags($_POST['cit_name']) : '';
    
    // Validation
    if (empty($cityName)) {
		$conf->addError('Veuillez renseigner le nom');
	}
    else if (strlen($cityName) < 4) {
		$conf->addError('Veuillez renseigner un nom correct');
	}
    if ($countryId == 0) {
        $conf->addError('Veuillez renseigner le pays');
    }
    else if (!array_key_exists($countryId, $countriesList)) {
		$conf->addError('Pays non valide');
	}
    
    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
	$cityObject = new City(
		$cityId,
        new Country($countryId),
		$cityName
	);
    
    // Si tout est ok
	if ($conf->haveError() === false) {
		if ($cityObject->saveDB()) {
			header('Location: city.php?success='.urlencode('Ajout/Modification effectuée').'&cit_id='.$cityObject->getId());
			exit;
		}
		else {
			$conf->addError('Erreur dans l\'ajout ou la modification');
		}
	}
}

// Instancie le générateur de menu déroulant pour la liste des villes
$selectCities = new SelectHelper($citiesList, $cityId, array(
	'name' => 'cit_id',
	'id' => 'cit_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour la liste des pays
$selectCountries = new SelectHelper($countriesList, $cityObject->getCountry()->getId(), array(
	'name' => 'cou_id',
	'id' => 'cou_id',
	'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'city.php';
require $conf->getViewsDir().'footer.php';
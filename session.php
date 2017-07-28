<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Session;
use \Classes\Webforce3\DB\Location;
use \Classes\Webforce3\DB\Training;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$sessionId = isset($_GET['ses_id']) ? intval($_GET['ses_id']) : 0;
$sessionObject = new Session();

// Récupère la liste complète des sessions en DB
$sessionsList = Session::getAllForSelect();
$locationsList = Location::getAllForSelect();
$trainingsList = Training::getAllForSelect();

if ($sessionId > 0) {
	$sessionObject = Session::get($sessionId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Session::deleteById(intval($_GET['delete']))) {
		header('Location: session.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Formulaire soumis
if (!empty($_POST)) {
    $sessionId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $locationId = isset($_POST['loc_id']) ? intval($_POST['loc_id']) : 0;
    $trainingId = isset($_POST['tra_id']) ? intval($_POST['tra_id']) : 0;
    $sessionStartDate = isset($_POST['ses_start_date']) ? date('Y-m-d', strtotime($_POST['ses_start_date'])) : 0;
    $sessionEndDate = isset($_POST['ses_end_date']) ? date('Y-m-d', strtotime($_POST['ses_end_date'])) : 0;
    $sessionNumber = isset($_POST['ses_number']) ? intval($_POST['ses_number']) : 0;

    if (strlen($sessionStartDate) < 10) {
        $conf->addError('Date de debut non correcte');
    }
    if (strlen($sessionEndDate) < 10) {
        $conf->addError('Date de fin non correcte');
    }
    if (!array_key_exists($locationId, $locationsList)) {
        $conf->addError('Location non valide');
    }
    if (!array_key_exists($trainingId, $trainingsList)) {
        $conf->addError('Training non valide');
    }
    if (empty($sessionNumber)) {
        $conf->addError('Veuillez renseigner le numero de session');
    }
    if (!is_int($sessionNumber)) {
        $conf->addError('Le numero de session doit etre un integer');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $sessionObject = new Session(
            $sessionId, new Location($locationId), new Training($trainingId), $sessionStartDate, $sessionEndDate, $sessionNumber
    );

    // Si tout est ok
    if (!$conf->haveError()) {
        if ($sessionObject->saveDB()) {
            header('Location: session.php?success=' . urlencode('Ajout/Modification effectuée') . '&ses_id=' . $sessionObject->getId());
            exit;
        } else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}

// Instancie le générateur de menu déroulant pour les trainings
$selectSessions = new SelectHelper($sessionsList, 0, array(
	'name' => 'ses_id',
	'id' => 'ses_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les cities
$selectLocations = new SelectHelper($locationsList, $sessionObject->getLocation()->getId(), array(
    'name' => 'loc_id',
    'id' => 'loc_id',
    'class' => 'form-control',
        ));

// Instancie le générateur de menu déroulant pour les cities
$selectTrainings = new SelectHelper($trainingsList, $sessionObject->getTraining()->getId(), array(
    'name' => 'tra_id',
    'id' => 'tra_id',
    'class' => 'form-control',
        ));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir() . 'header.php';
require $conf->getViewsDir() . 'session.php';
require $conf->getViewsDir() . 'footer.php';

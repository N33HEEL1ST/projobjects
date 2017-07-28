<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Trainer;
use \Classes\Webforce3\DB\City;
use \Classes\Webforce3\DB\Speciality;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$trainerId = isset($_GET['trn_id']) ? intval($_GET['trn_id']) : 0;
$trainerObject = new Trainer();

// Récupère la liste complète des students en DB
$trainersList = Trainer::getAllForSelect();
// Récupère la liste complète des cities en DB
$citiesList = City::getAllForSelect();
// Récupère la liste complète des sessions en DB
$specialitiesList = Speciality::getAllForSelect();

// Si modification d'un student, on charge les données pour le formulaire
if ($trainerId > 0) {
    $trainerObject = Trainer::get($trainerId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
    if (Trainer::deleteById(intval($_GET['delete']))) {
        header('Location: trainer.php?success=' . urlencode('Suppression effectuée'));
        exit;
    }
}

// Si formulaire soumis
if (!empty($_POST)) {
    $trainerId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $specialityId = isset($_POST['spe_id']) ? intval($_POST['spe_id']) : 0;
    $cityId = isset($_POST['cit_id']) ? intval($_POST['cit_id']) : 0;
    $trainerLastName = isset($_POST['trn_lname']) ? trim($_POST['trn_lname']) : '';
    $trainerFirstName = isset($_POST['trn_fname']) ? trim($_POST['trn_fname']) : '';

    if (!array_key_exists($specialityId, $specialitiesList)) {
        $conf->addError('Specialite non valide');
    }
    if (!array_key_exists($cityId, $citiesList)) {
        $conf->addError('Ville non valide');
    }
    if (empty($trainerLastName)) {
        $conf->addError('Veuillez renseigner le nom');
    }
    if (empty($trainerFirstName)) {
        $conf->addError('Veuillez renseigner le prénom');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $trainerObject = new Trainer(
            $trainerId, new Speciality($specialityId), new City($cityId), $trainerLastName, $trainerFirstName
    );

    // Si tout est ok
    if (!$conf->haveError()) {
        if ($trainerObject->saveDB()) {
            header('Location: trainer.php?success=' . urlencode('Ajout/Modification effectuée') . '&trn_id=' . $trainerObject->getId());
            exit;
        } else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}
// Instancie le générateur de menu déroulant pour la liste des étudiants
$selectTrainers = new SelectHelper($trainersList, $trainerId, array(
    'name' => 'trn_id',
    'id' => 'trn_id',
    'class' => 'form-control',
        ));

// Instancie le générateur de menu déroulant pour les trainings
$selectSpecialities = new SelectHelper($specialitiesList, $trainerObject->getSpeciality()->getId(), array(
    'name' => 'spe_id',
    'id' => 'spe_id',
    'class' => 'form-control',
        ));

// Instancie le générateur de menu déroulant pour les cities
$selectCities = new SelectHelper($citiesList, $trainerObject->getCity()->getId(), array(
    'name' => 'cit_id',
    'id' => 'cit_id',
    'class' => 'form-control',
        ));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir() . 'header.php';
require $conf->getViewsDir() . 'trainer.php';
require $conf->getViewsDir() . 'footer.php';

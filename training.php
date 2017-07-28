<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\DB\Training;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$trainingId = isset($_GET['tra_id']) ? intval($_GET['tra_id']) : 0;
$trainingObject = new Training();

// Récupère la liste complète des pays en DB
$trainingsList = Training::getAllForSelect();

if ($trainingId > 0) {
    $trainingObject = Training::get($trainingId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Training::deleteById(intval($_GET['delete']))) {
		header('Location: training.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Formulaire soumis
if (!empty($_POST)) {
    $trainingId = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $trainingName = isset($_POST['tra_name']) ? trim($_POST['tra_name']) : '';
    
    if (empty($trainingName)) {
        $conf->addError('Veuillez renseigner le nom du pays');
    }

    // je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
    $trainingObject = new Training(
            $trainingId,
            $trainingName
    );

    // Si tout est ok
    if (!$conf->haveError()) {
        if ($trainingObject->saveDB()) {
            header('Location: training.php?success=' . urlencode('Ajout/Modification effectuée') . '&tra_id=' . $trainingObject->getId());
            exit;
        } else {
            $conf->addError('Erreur dans l\'ajout ou la modification');
        }
    }
}

$selectTrainings = new SelectHelper($trainingsList, $trainingObject->getId(), array(
    'name' => 'tra_id',
    'id' => 'tra_id',
    'class' => 'form-control',
        ));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir() . 'header.php';
require $conf->getViewsDir() . 'training.php';
require $conf->getViewsDir() . 'footer.php';


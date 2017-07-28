<?php

// Autoload PSR-4
spl_autoload_register();

// Imports 
use \Classes\Webforce3\Config\Config;
use \Classes\Webforce3\Friendliness;
use \Classes\Webforce3\DB\Student;
use \Classes\Webforce3\DB\City;
use \Classes\Webforce3\DB\Session;
use \Classes\Webforce3\Helpers\SelectHelper;

// Get the config object
$conf = Config::getInstance();

$studentId = isset($_GET['stu_id']) ? intval($_GET['stu_id']) : 0;
$studentObject = new Student();

// Récupère la liste de sympathie pour la vue
$friendlinessList = Friendliness::getListForSelect();

// Récupère la liste complète des students en DB
$studentList = Student::getAllForSelect();
// Récupère la liste complète des cities en DB
$citiesList = City::getAllForSelect();
// Récupère la liste complète des sessions en DB
$sessionsList = Session::getAllForSelect();

// Si modification d'un student, on charge les données pour le formulaire
if ($studentId > 0) {
	$studentObject = Student::get($studentId);
}

// Si lien suppression
if (isset($_GET['delete']) && intval($_GET['delete']) > 0) {
	if (Student::deleteById(intval($_GET['delete']))) {
		header('Location: student.php?success='.urlencode('Suppression effectuée'));
		exit;
	}
}

// Si formulaire soumis
if (!empty($_POST)) {
	$studentId = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$sessionId = isset($_POST['ses_id']) ? intval($_POST['ses_id']) : 0;
	$cityId = isset($_POST['cit_id']) ? intval($_POST['cit_id']) : 0;
	$studentBirthdate = isset($_POST['stu_birthdate']) ? date('Y-m-d', strtotime($_POST['stu_birthdate'])) : 0;
	$studentFriendliness = isset($_POST['stu_friendliness']) ? intval($_POST['stu_friendliness']) : 0;
	$studentLastName = isset($_POST['stu_lname']) ? trim($_POST['stu_lname']) : '';
	$studentFirstName = isset($_POST['stu_fname']) ? trim($_POST['stu_fname']) : '';
	$studentEmail = isset($_POST['stu_email']) ? trim($_POST['stu_email']) : '';

	if (strlen($studentBirthdate) < 10) {
		$conf->addError('Birthdate non correcte');
	}
	if (!array_key_exists($studentFriendliness, $friendlinessList)) {
		$conf->addError('Sympathie non valide');
	}
	if (!array_key_exists($cityId, $citiesList)) {
		$conf->addError('Ville non valide');
	}
	if (!array_key_exists($sessionId, $sessionsList)) {
		$conf->addError('Session de formation non valide');
	}
	if (empty($studentEmail) || filter_var($studentEmail, FILTER_VALIDATE_EMAIL) === false) {
		$conf->addError('Email non valide');
	}
	if (empty($studentLastName)) {
		$conf->addError('Veuillez renseigner le nom');
	}
	if (empty($studentFirstName)) {
		$conf->addError('Veuillez renseigner le prénom');
	}

	// je remplis l'objet qui est lu pour les inputs du formulaire, ou pour l'ajout en DB
	$studentObject = new Student(
		$studentId,
		new Session($sessionId),
		new City($cityId),
		$studentLastName,
		$studentFirstName,
		$studentEmail,
		$studentBirthdate,
		$studentFriendliness
	);

	// Si tout est ok
	if ($conf->haveError() === false) {
		if ($studentObject->saveDB()) {
			header('Location: student.php?success='.urlencode('Ajout/Modification effectuée').'&stu_id='.$studentObject->getId());
			exit;
		}
		else {
			$conf->addError('Erreur dans l\'ajout ou la modification');
		}
	}
}

// Instancie le générateur de menu déroulant pour la sympathie
$selectFriendliness = new SelectHelper($friendlinessList, $studentObject->getFriendliness(), array(
	'name' => 'stu_friendliness',
	'id' => 'stu_friendliness',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour la liste des étudiants
$selectStudents = new SelectHelper($studentList, $studentId, array(
	'name' => 'stu_id',
	'id' => 'stu_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les trainings
$selectSessions = new SelectHelper($sessionsList, $studentObject->getSession()->getId(), array(
	'name' => 'ses_id',
	'id' => 'ses_id',
	'class' => 'form-control',
));

// Instancie le générateur de menu déroulant pour les cities
$selectCities = new SelectHelper($citiesList, $studentObject->getCity()->getId(), array(
	'name' => 'cit_id',
	'id' => 'cit_id',
	'class' => 'form-control',
));

// Views - toutes les variables seront automatiquement disponibles dans les vues
require $conf->getViewsDir().'header.php';
require $conf->getViewsDir().'student.php';
require $conf->getViewsDir().'footer.php';
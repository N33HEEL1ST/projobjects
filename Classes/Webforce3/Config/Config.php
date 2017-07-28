<?php

namespace Classes\Webforce3\Config;

class Config {
	/** @var \PDO */
	private $db;
	/** @var string */
	private $baseDir;
	/** @var string */
	private $viewsDir;
	/** @var string[] */
	private $successList;
	/** @var string[] */
	private $infoList;
	/** @var string[] */
	private $warningList;
	/** @var string[] */
	private $errorList;
	/** @var Config */
	private static $_instance; // Design Pattern "Singleton"

	public function __construct() {
		$this->baseDir = dirname(dirname(dirname(dirname(__FILE__))));
		$this->viewsDir = $this->baseDir.DIRECTORY_SEPARATOR.'views';
		require $this->baseDir.DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR.'config.php';

		$dsn = 'mysql:dbname='.$dbDatabase.';host='.$dbHost.';charset=UTF8';
		try {
			$this->db = new \PDO($dsn, $dbUser, $dbPassword);
		}
		catch(Exception $e) {
			echo 'Config :: Can\'t connect ['.$e->getMessage().']';
			exit;
		}

		$this->errorList = array();
		$this->warningList = array();
		$this->infoList = array();
		$this->successList = array();
		if (isset($_GET['success'])) {
			$this->addSuccess(trim($_GET['success']));
		}
	}
 
 	// Design Pattern "Singleton"
	public static function getInstance() {
		if(is_null(self::$_instance)) {
			self::$_instance = new Config();  
		}
		return self::$_instance;
	}

	/**
	 * @return \PDO
	 */
	public function getDB() {
		return $this->db;
	}

	/**
	 * @return \PDO
	 */
	public function getPDO() {
		return $this->getDB();
	}

	/**
	 * @return string
	 */
	public function getViewsDir() {
		return $this->viewsDir.DIRECTORY_SEPARATOR;
	}

	/**
	 * @return string
	 */
	public function getBaseDir() {
		return $this->baseDir.DIRECTORY_SEPARATOR;
	}

	/**
	 * @return \string[]
	 */
	public function getSuccessList() {
		return $this->successList;
	}

	/**
	 * @return \string[]
	 */
	public function getInfoList() {
		return $this->infoList;
	}

	/**
	 * @return \string[]
	 */
	public function getWarningList() {
		return $this->warningList;
	}

	/**
	 * @return \string[]
	 */
	public function getErrorList() {
		return $this->errorList;
	}

	/**
	 * @param string $str
	 */
	public function addError($str) {
		$this->errorList[] = $str;
	}

	/**
	 * @param string $str
	 */
	public function addWarning($str) {
		$this->warningList[] = $str;
	}

	/**
	 * @param string $str
	 */
	public function addInfo($str) {
		$this->infoList[] = $str;
	}

	/**
	 * @param string $str
	 */
	public function addSuccess($str) {
		$this->successList[] = $str;
	}

	/**
	 * @return bool
	 */
	public function haveError() {
		return sizeof($this->errorList) > 0;
	}

	/**
	 * @return bool
	 */
	public function doesNotHaveError() {
		return !$this->haveError();
	}
}
<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

abstract class DbObject {
	/** @var int */
	protected $id;
	/** @var int */
	protected $inserted;

	public function __construct($id=0, $inserted='') {
		$this->id = $id;
		if (is_numeric($inserted)) {
			$this->inserted = $inserted;
		}
		else {
			$this->inserted = strtotime($inserted);
		}
	}

	/**
     * Retourne un objet correspondant à l'ID en DB
     * 
	 * @param int $id
	 * @return DbObject
	 * @throws InvalidSqlQueryException
	 */
	abstract public static function get($id);

	/**
     * Renvoie un tableau correspondant à toutes les lignes de tables
     * sous forme d'objet (1 objet par ligne)
     * 
	 * @return DbObject[]
	 * @throws InvalidSqlQueryException
	 */
	abstract public static function getAll();

	/**
     * Renvoie un tableau associatif à une dimension
     * En index l'ID dans la table
     * En value, une string représentative
     * 
	 * @return array
	 * @throws InvalidSqlQueryException
	 */
	abstract public static function getAllForSelect();

	/**
     * Effectue la sauvegarde dans la DB de l'instance courante
     * 
	 * @return bool
	 * @throws InvalidSqlQueryException
	 */
	abstract public function saveDB();

	/**
     * Permet de supprimer la ligne correspondant à l'id, dans la table
     * 
	 * @param int $id
	 * @return bool
	 * @throws InvalidSqlQueryException
	 */
	abstract public static function deleteById($id);

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getInserted() {
		return $this->inserted;
	}
    
    public static function deleteFromId($id, $tableName, $idFieldName) {
        $sql = '
			DELETE FROM '.$tableName.' WHERE '.$idFieldName.' = :id
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			return true;
		}
		return false;
    }
}
<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

/**
 * Description of Country
 *
 * @author Etudiant
 */
class Country extends DbObject {
    /** @var string */
    protected $name;
    
    public function __construct($id=0, $name='', $inserted='') {
        parent::__construct($id, $inserted);
        $this->name = $name;
    }
    
    public static function deleteById($id) {
        $sql = '
			DELETE FROM country WHERE cou_id = :id
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

    public static function get($id) {
        $sql = '
			SELECT cou_id, cou_name, cou_inserted
			FROM country
			WHERE cou_id = :id
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Country(
					$row['cou_id'],
					$row['cou_name'],
					$row['cou_inserted']
				);
				return $currentObject;
			}
		}

		return false;
    }

    public static function getAll() {
        
    }

    public static function getAllForSelect() {
        $returnList = array();

		$sql = '
			SELECT cou_id, cou_name
			FROM country
			WHERE cou_id > 0
			ORDER BY cou_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['cou_id']] = $row['cou_name'];
			}
		}

		return $returnList;
    }

    public function saveDB() {
        if ($this->id > 0) {
			$sql = '
				UPDATE country
				SET cou_name = :name
				WHERE cou_id = :id
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->name);

			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				return true;
			}
		}
		else {
			$sql = '
				INSERT INTO country (cou_name)
				VALUES (:name)
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':name', $this->name);

			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				$this->id = Config::getInstance()->getPDO()->lastInsertId();
				return true;
			}
		}

		return false;
    }
    
    public function getName() {
        return $this->name;
    }
}

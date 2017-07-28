<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Speciality extends DbObject {
    
    /** @var string */
    protected $name;
    
    public function __construct($id = 0, $name = '', $inserted = '') {
        $this->name = $name;

        parent::__construct($id, $inserted);
    }
    
    public function saveDB() {
        if ($this->id > 0) {
            $sql = '
				UPDATE speciality
				SET spe_name = :name
				WHERE spe_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
				INSERT INTO speciality (spe_name)
				VALUES (:name)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':name', $this->name);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                $this->id = Config::getInstance()->getPDO()->lastInsertId();
                return true;
            }
        }

        return false;
    }

    public static function deleteById($id) {
        $sql = '
			DELETE FROM speciality WHERE spe_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            return true;
        }
        return false;
    }

    public static function get($id) {
        $sql = '
			SELECT spe_id, spe_name
			FROM speciality
			WHERE spe_id = :id
			ORDER BY spe_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Speciality(
                        $row['spe_id'], $row['spe_name']
                );
                return $currentObject;
            }
        }

        return false;
    }

    public static function getAll() {
        //vide
    }

    public static function getAllForSelect() {
        $returnList = array();

        $sql = '
			SELECT spe_id, spe_name
			FROM speciality
			WHERE spe_id > 0
			ORDER BY spe_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['spe_id']] = $row['spe_name'];
            }
        }

        return $returnList;
    }
    
    function getName() {
        return $this->name;
    }

}

<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class Trainer extends DbObject {
    
    /** @var string */
    protected $lname;

    /** @var string */
    protected $fname;
    
    /** @var City */
    protected $city;

    /** @var Speciality */
    protected $speciality;
    
    public function __construct($id = 0, $speciality = null, $city = null, $lname = '', $fname = '', $inserted = '') {
        if (empty($speciality)) {
            $this->speciality = new Speciality();
        } else {
            $this->speciality = $speciality;
        }
        if (empty($city)) {
            $this->city = new City();
        } else {
            $this->city = $city;
        }
        $this->lname = $lname;
        $this->fname = $fname;

        parent::__construct($id, $inserted);
    }
    
    public function saveDB() {
        if ($this->id > 0) {
            $sql = '
				UPDATE trainer
				SET trn_lastname = :lname,
				trn_firstname = :fname,
				city_cit_id = :citId,
				speciality_spe_id = :speId
				WHERE trn_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':citId', $this->city->id, \PDO::PARAM_INT);
            $stmt->bindValue(':speId', $this->speciality->id, \PDO::PARAM_INT);
            $stmt->bindValue(':lname', $this->lname);
            $stmt->bindValue(':fname', $this->fname);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
				INSERT INTO trainer (trn_lastname, trn_firstname, city_cit_id, speciality_spe_id)
				VALUES (:lname, :fname, :citId, :speId)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':citId', $this->city->id, \PDO::PARAM_INT);
            $stmt->bindValue(':speId', $this->speciality->id, \PDO::PARAM_INT);
            $stmt->bindValue(':lname', $this->lname);
            $stmt->bindValue(':fname', $this->fname);

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
			DELETE FROM trainer WHERE trn_id = :id
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
			SELECT trn_id, city_cit_id, trn_lastname, trn_firstname, speciality_spe_id
			FROM trainer
			WHERE trn_id = :id
			ORDER BY trn_lastname ASC, trn_firstname ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new Trainer(
                        $row['trn_id'], new Speciality($row['speciality_spe_id']), new City($row['city_cit_id']), $row['trn_lastname'], $row['trn_firstname']
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
			SELECT trn_id, trn_lastname, trn_firstname
			FROM trainer
			WHERE trn_id > 0
			ORDER BY trn_lastname ASC, trn_firstname ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['trn_id']] = $row['trn_lastname'] . ' ' . $row['trn_firstname'];
            }
        }

        return $returnList;
    }
    
    function getLname() {
        return $this->lname;
    }

    function getFname() {
        return $this->fname;
    }

    function getCity() {
        return $this->city;
    }

    function getSpeciality() {
        return $this->speciality;
    }

}

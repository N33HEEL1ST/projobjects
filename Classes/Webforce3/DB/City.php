<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class City extends DbObject {

    /** @var Country */
    protected $country;

    /** @var string */
    protected $name;

    public function __construct($id = 0, $country = null, $name = '', $inserted = '') {
        parent::__construct($id, $inserted);

        if (empty($country)) {
			$this->country = new Country();
		}
		else {
			$this->country = $country;
		}
        $this->name = $name;
    }

    /**
     * 
     * @return Country
     */
    public function getCountry() {
        return $this->country;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @param Country $country
     */
    public function setCountry($country) {
        // Je vérifie le type de la donnée fournie
        if (is_a($country, 'Country')) {
            $this->country = $country;
        }
    }

    /**
     * @param int $id
     * @return DbObject
     */
    public static function get($id) {
        $sql = '
			SELECT cit_id, cit_name, cit_inserted, country_cou_id
			FROM city
			WHERE cit_id = :id
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new City(
                        $row['cit_id'],
                        //new Country($row['country_cou_id']),
                        Country::get($row['country_cou_id']), $row['cit_name'], $row['cit_inserted']
                );
                return $currentObject;
            }
        }

        return false;
    }

    /**
     * @return DbObject[]
     */
    public static function getAll() {
        // TODO
    }

    /**
     * @return array
     */
    public static function getAllForSelect() {
        $returnList = array();

        $sql = '
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            print_r($stmt->errorInfo());
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $returnList[$row['cit_id']] = $row['cit_name'];
            }
        }

        return $returnList;
    }

    /**
     * @return bool
     */
    public function saveDB() {
        if ($this->id > 0) {
            $sql = '
				UPDATE city
				SET cit_name = :name,
                    country_cou_id = :countryID
				WHERE cit_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':countryID', $this->getCountry()->getId(), \PDO::PARAM_INT);
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
				INSERT INTO city (cit_name, country_cou_id)
				VALUES (:name, :countryID)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':countryID', $this->getCountry()->getId(), \PDO::PARAM_INT);
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

    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById($id) {
        return self::deleteFromId($id, 'city', 'cit_id');
    }

}

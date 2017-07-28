<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;
use Classes\Webforce3\Exceptions\InvalidSqlQueryException;

class City extends DbObject {

    /** @var string */
    protected $name;

    /** @var Country */
    protected $country;

    public function __construct($id = 0, $name = '', $country = null, $inserted = '') {
        if (empty($country)) {
            $this->country = new Country();
        } else {
            $this->country = $country;
        }
        $this->name = $name;

        parent::__construct($id, $inserted);
    }

    /**
     * @param int $id
     * @return bool|City
     * @throws InvalidSqlQueryException
     */
    public static function get($id) {
        $sql = '
			SELECT cit_id, cit_name, country_cou_id
			FROM city
			WHERE cit_id = :id
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);

        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if (!empty($row)) {
                $currentObject = new City(
                        $row['cit_id'], $row['cit_name'], new Country($row['country_cou_id'])
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
        $returnList = array();

        $sql = '
			SELECT cit_id, cit_name
			FROM city
			WHERE cit_id > 0
			ORDER BY cit_name ASC
		';
        $stmt = Config::getInstance()->getPDO()->prepare($sql);
        if ($stmt->execute() === false) {
            throw new InvalidSqlQueryException($sql, $stmt);
        } else {
            $allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($allDatas as $row) {
                $currentObject = new City(
                        $row['cit_id'], $row['cit_name']
                );
                $returnList[] = $currentObject;
            }
        }

        return $returnList;
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
				country_cou_id = :couId
				WHERE cit_id = :id
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
            $stmt->bindValue(':couId', $this->country->id, \PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name);

            if ($stmt->execute() === false) {
                throw new InvalidSqlQueryException($sql, $stmt);
            } else {
                return true;
            }
        } else {
            $sql = '
				INSERT INTO city (cit_name, country_cou_id)
				VALUES (:name, :couId)
			';
            $stmt = Config::getInstance()->getPDO()->prepare($sql);
            $stmt->bindValue(':couId', $this->country->id, \PDO::PARAM_INT);
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

    /**
     * @param int $id
     * @return bool
     */
    public static function deleteById($id) {
        $sql = '
			DELETE FROM city WHERE cit_id = :id
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

    function getName() {
        return $this->name;
    }

    function getCountry() {
        return $this->country;
    }

}

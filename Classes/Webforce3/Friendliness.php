<?php

namespace Classes\Webforce3;

class Friendliness {

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    public static function getListForSelect() {
        return array(
            1 => 'Pas sympa',
            2 => 'Assez sympa',
            3 => 'Sympa',
            4 => 'Très sympa',
            5 => 'Génial'
        );
    }

}

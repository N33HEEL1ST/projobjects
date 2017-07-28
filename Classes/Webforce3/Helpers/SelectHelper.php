<?php

namespace Classes\Webforce3\Helpers;

use \Classes\Webforce3\Config\Config;

class SelectHelper {

    private $valuesList;
    private $selectedValue;
    private $attributesList;

    public function __construct($valuesList, $selectedValue = 0, $attributesList = array()) {
        $this->valuesList = $valuesList;
        $this->selectedValue = $selectedValue;
        $this->attributesList = $attributesList;
    }

    /**
     * @return int
     */
    public function getSelectedValue() {
        return $this->selectedValue;
    }

    /**
     * @param int $selectedValue
     */
    public function setSelectedValue($selectedValue) {
        $this->selectedValue = $selectedValue;
    }

    public function getHTML() {
        // Get Config singleton
        $config = Config::getInstance();

        // Generate varaibles for view
        $selectValues = $this->valuesList;
        $selectedValue = $this->selectedValue;
        $attributesList = $this->attributesList;

        ob_start();
        include $config->getViewsDir() . 'select.php';
        return ob_get_clean();
    }

    public function displayHTML() {
        echo $this->getHTML();
    }

}

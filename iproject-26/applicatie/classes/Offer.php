<?php

require_once(__DIR__ ."/Database.php");

class Offer extends Database {
    protected const TABLE_NAME = "Bod";

    public $voorwerpnummer;
    public $bodbedrag;
    public $gebruikersnaam;
    public $boddag;
    public $bodtijdstip;

    public function all() {
        return Database::query("SELECT * FROM Bod");
    }

    public function getByProductIds(array $productIds) {
        if(count($productIds) <= 0) {
            return;
        }

        $in  = str_repeat('?,', count($productIds) - 1) . '?';
        $sql = "SELECT * FROM Bod WHERE voorwerpnummer IN ($in)";
        $offers = $this->simpleSelect($sql, $productIds);

        return $offers;
    }
}
<?php

require_once(__DIR__ ."/Database.php");

class Product extends Database {
    protected const TABLE_NAME = "Voorwerp";
    protected const PRIMARY_KEY = "voorwerpnummer";

    public $voorwerpnummer;
    public $titel;
    public $beschrijving;
    public $startprijs;
    public $betalingswijze;
    public $betalingsinstructie;
    public $plaats;
    public $land;
    public $tijdstip;
    public $verzendkosten;
    public $verzendinstructies;
    public $verkoper;
    public $koper;
    public $looptijd;
    public $looptijdbegindag;
    public $looptijdeinde;
    public $eindetijdstip;
    public $veilinggesloten;
    public $verkoopprijs;

    public function all() {
        return Database::query("SELECT * FROM Voorwerp");
    }

    public function allWithLimit() {
        return Database::query("SELECT TOP 9 * FROM Voorwerp");
    }

    public function getByKeyword(string $keyword) {
        $keyword = "%" . $keyword . "%";
        $sql = "SELECT * FROM Voorwerp WHERE titel LIKE :keyword";
        $voorwerpen = $this->select($sql, ["keyword" => $keyword]);
        $voorwerpIds = [];
        foreach($voorwerpen as $voorwerp) {
            array_push($voorwerpIds, $voorwerp->voorwerpnummer);
        }

        return [$voorwerpen, $voorwerpIds];
    }

    public function getMinPrice() {
        $product = Database::query("SELECT MIN(startprijs) as startprijs FROM Voorwerp");
        $productPrice = count($product) == 1 ? $product[0]->startprijs : null;
        return $productPrice;
    }

    public function getMaxPrice() {
        $product = Database::query("SELECT MAX(startprijs) as startprijs FROM Voorwerp");
        $productPrice = count($product) == 1 ? $product[0]->startprijs : null;
        return $productPrice;
    }

    public function saveBid(float $newBidValue) {

    }

    public function getCategories() {
        $stmt = Database::innerJoin(
            "SELECT * FROM Voorwerp_in_Rubriek vr
            INNER JOIN Rubriek r ON vr.rubrieknummer = r.rubrieknummer
            WHERE vr.voorwerpnummer = :vwid",
            ["vwid" => $this->voorwerpnummer]
        );

        return $stmt;
    }
}
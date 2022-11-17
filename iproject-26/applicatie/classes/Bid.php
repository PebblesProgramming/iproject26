<?php

require_once(__DIR__ ."/Database.php");

class Bid extends Database {
    protected const TABLE_NAME = "Bod";
    protected const PRIMARY_KEY = "voorwerpnummer";

    public $voorwerpnummer;
    public $bodbedrag;
    public $gebruikersnaam;
    public $boddag;
    public $bodtijdstip;

    public function insertBid(Bid $bid) {
        $tablename = self::TABLE_NAME;

        $bid->save();
    }

    public function all() {
        return Database::query("SELECT * FROM Bod");
    }

    public function getHighest() {
        $highestBid = Database::select("SELECT MAX(bodbedrag) as bodbedrag FROM Bod 
                                        WHERE voorwerpnummer = :voorwerpnummer", ["voorwerpnummer" => $_GET["id"]]);
        $highestBid = count($highestBid) === 1 ? $highestBid[0] : null;
        return $highestBid;
    }
}
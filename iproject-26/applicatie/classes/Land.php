<?php

require_once(__DIR__ ."/Database.php");

class Land extends Database {
    public $landnaam;

    public function all() {
        return Database::query("SELECT * FROM Land");
    }
}
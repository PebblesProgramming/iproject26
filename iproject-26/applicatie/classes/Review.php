<?php

require_once __DIR__ ."/Database.php";

class Review extends Database {
    protected const TABLE_NAME = "Beoordelingen";
    protected const PRIMARY_KEY = "verkoper";

    public $verkoper;
    public $cijfer;

    public function getAverageFromUser(string $verkoper) {
        $result = Database::select("SELECT AVG(cijfer) as cijfer FROM Beoordelingen WHERE verkoper = :verkoper", ["verkoper" => $verkoper]);
        
        return $result;
    }

    
}
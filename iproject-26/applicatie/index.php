<?php
require_once("classes/Database.php");

//Database::init();
$results = Database::select("select * from Test", array());

foreach($results as $result)
{
    echo "Naam verkoper: " . $result["tst_Column2"];
}

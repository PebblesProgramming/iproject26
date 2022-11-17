<?php

require_once __DIR__ ."/Database.php";

class Category extends Database {
    protected const TABE_NAME = "Rubriek";
    protected const PRIMARY_KEY = "rubrieknummer";

    public $rubrieknummer;
    public $rubrieknaam;
    public $rubriek;
    public $volgnummer;

    private $rubrieken = [];
    private $rubriekenCounter = 0;
    public $children = [];

    public function getRubrieken($parentId = -1) {
        $sql = "WITH rubriekenBoom (rubrieknummer, rubrieknaam, rubriek, level, crumble) as
            (
            select rubrieknummer, rubrieknaam, rubriek, 0 as rubriekLevel, cast(rubrieknaam as varchar(max)) as crumble
            from Rubriek
            where rubriek = -1
            union all
            select Rubriek.rubrieknummer, Rubriek.rubrieknaam, Rubriek.rubriek, rb.level+1, cast(rb.crumble + '|||' + Rubriek.rubrieknaam as varchar(max))
            from rubriekenBoom as rb inner join Rubriek
            on Rubriek.rubriek = rb.rubrieknummer
            )
            select *
            from rubriekenBoom rb
            order by rubriek;";
        
        $result = Database::query($sql);

        return $result;

    }

    function buildTree($result, $parentId = -1) {
        $branch = array();
    
        foreach ($result as $element) {
            if ($element->rubriek == $parentId) {
                $children = $this->buildTree($result, $element->rubrieknummer);
                if ($children) {
                    $element->children = $children;
                }
                $branch[] = $element;
            }
        }
    
        return $branch;
    }
}
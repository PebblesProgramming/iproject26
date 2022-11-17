<?php

require_once(__DIR__ . "/Database.php");

class User extends Database implements IUser
{
    protected const TABLE_NAME = "Gebruiker";
    protected const PRIMARY_KEY = "gebruikersnaam";

    public $gebruikersnaam;
    public $voornaam;
    public $achternaam;
    public $adres1;
    public $adres2;
    public $postcode;
    public $plaats;
    public $land;
    public $geboortedag;
    public $emailadres;
    public $wachtwoord;
    public $vraagnummer;
    public $antwoordtekst;
    public $verkoperstatus;

    public function all()
    {
        return Database::query("SELECT * FROM Gebruiker");
    }

    // public function findOne(string $pkVal)
    // {
    //     return Database::select("SELECT * FROM Gebruiker WHERE ? = ?", [self::PRIMARY_KEY, $pkVal]);
    // }


    public function validate(): bool
    {
        if (!preg_match('/([a-b][0-9]){0,}@(gmail|outlook).com/', $_POST["emailadres"], $matches)) {
            return false;
        }
        if (!preg_match('/[0-9]{4}[A-Z]{2}/', $_POST["postcode"], $matches)) {
            return false;
        }
        if (!preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $_POST["geboortedag"], $matches)) {
            return false;
        }

        return true;
    }

    public function convertPassword(): string
    {
        return password_hash($_POST["wachtwoord"], PASSWORD_DEFAULT);
    }


    // Deze functie checkt of de opgegeven email al bestaat
    function bestaatEmail($email)
    {
        $emailCheck = 'SELECT COUNT(emailadres) as email FROM Gebruiker WHERE emailadres = ?';
        $gecheckteQuery = Database::getVerbinding()->prepare($emailCheck);
        $gecheckteQuery->execute([htmlspecialchars($email)]);
        $resultaat = $gecheckteQuery->fetch();
        $mailRecords = intval($resultaat['email']);
        return $mailRecords === 0;
    }

    // Deze functie voegt een nieuwe gebruiker toe aan de database
    public function registreerGebruiker(array $userAccount)
    {
        $sql = "INSERT INTO Gebruiker (gebruikersnaam, voornaam, achternaam, adres1, adres2, postcode, plaats, land, geboortedag, emailadres, wachtwoord, vraagnummer, antwoordtekst, verkoperstatus)
        OUTPUT INSERTED.gebruikersnaam, INSERTED.voornaam, INSERTED.achternaam, INSERTED.adres1, INSERTED.adres2, INSERTED.postcode, INSERTED.plaats, INSERTED.land, INSERTED.geboortedag, INSERTED.emailadres, INSERTED.wachtwoord, INSERTED.vraagnummer, INSERTED.antwoordtekst, INSERTED.verkoperstatus
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $inputQuery = Database::getVerbinding()->prepare($sql);
        $paramsArray = [
                trim(htmlspecialchars($userAccount['gebruikersnaam'])),
                trim(htmlspecialchars($userAccount['voornaam'])),
                trim(htmlspecialchars($userAccount['achternaam'])),
                trim(htmlspecialchars($userAccount['adres1'])),
                trim(htmlspecialchars($userAccount['adres2'])),
                trim(htmlspecialchars($userAccount['postcode'])),
                trim(htmlspecialchars($userAccount['plaats'])),
                trim(htmlspecialchars($userAccount['land'])),
                trim(htmlspecialchars($userAccount['geboortedag'])),
                trim(htmlspecialchars($userAccount['emailadres'])),
                password_hash(trim(htmlspecialchars($userAccount['wachtwoord'])), PASSWORD_DEFAULT),
                null,
                null,
                isset($userAccount['verkoperstatus']) ? true : false,
        ];
        $inputQuery->execute(
            $paramsArray
        );
        $inputQuery->setFetchMode(PDO::FETCH_CLASS, "User");

        return $inputQuery->fetch();
    }

    public function login() {
        $_SESSION["user"] = $this;
        unset($_POST);
    }

    // Deze functie zorgt ervoor dat een gebruiker kan inloggen
    public function versturenAanmelden()
    {
        $verbinding = Database::getVerbinding();
        $gebruikersnaam = htmlspecialchars($_POST['gebruikersnaam']);
        $voornaam = htmlspecialchars($_POST['voornaam']);
        $achternaam = htmlspecialchars($_POST['achternaam']);
        $adres1 = htmlspecialchars($_POST['adres1']);
        $adres2 = htmlspecialchars($_POST['adres2']);
        $postcode = htmlspecialchars($_POST['postcode']);
        $plaats = htmlspecialchars($_POST['plaats']);
        $land = htmlspecialchars($_POST['land']);
        $geboortedag = htmlspecialchars($_POST['geboortedag']);
        $emailadres = htmlspecialchars($_POST['emailadres']);
        $wachtwoord = password_hash(htmlspecialchars($_POST['wachtwoord']), PASSWORD_DEFAULT);
        $vraagnummer = null;
        $antwoordtekst = null;
        $verkoperstatus = isset($userAccount['verkoperstatus']) ? true : false;

        $sql = 'INSERT INTO Gebruiker (gebruikersnaam, voornaam, achternaam, adres1, adres2, postcode, plaats, land, geboortedag, emailadres, wachtwoord, vraagnummer, antwoordtekst, verkoperstatus)
        values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $query = $verbinding->prepare($sql);
        $query->execute([
            $gebruikersnaam, $voornaam, $achternaam, $adres1, $adres2, $postcode, $plaats, $land, $geboortedag, $emailadres, $wachtwoord, $vraagnummer, $antwoordtekst, $verkoperstatus
        ]);
        // $_SESSION['user'] = $username;
        unset($_POST);
    }

    // Deze functie checkt of de post is geset
    public static function registreerCheck()
    {
        if (isset($_POST["gebruikersnaam"])) {
            $this->versturenAanmelden();
        }
    }



    // Deze functie controleert of de gebruiker de juiste gegevens heeft ingevuld bij het inloggen
    public function inloggenControleren()
    {
        $email = $_POST["emailadres"];
        $password = $_POST["wachtwoord"];

        $sql = 'SELECT *
            from Gebruiker
            where  emailadres = :email';
        $resultaat = Database::getVerbinding()->prepare($sql);
        $resultaat->execute(["email" => $email]);
        $resultaat = $resultaat->fetchAll(PDO::FETCH_CLASS, get_class($this));
        if($resultaat == false) {
            return false;
        } else if (count($resultaat) == 1) {
            $resultaat = $resultaat[0];

            if (isset($resultaat->emailadres) && password_verify($password, $resultaat->wachtwoord)) {
                return $resultaat;
            } elseif (!isset($resultaat->emailadres)) {
                return false;
            } elseif (isset($resultaat->emailadres) && password_verify($password, $resultaat->wachtwoord) == false) {
                return false;
            }
        } else {
            return false;
        }
    }

    // Deze functie haalt gegevens van de gebruiker op uit de database
    function haalGegevensOp($email, $gegevens)
    {
    }

    // Deze functie zorgt ervoor dat een gebruiker kan uitloggen
    function uitloggen()
    {
        if (isset($_GET['ingelogd']) && $_GET['ingelogd'] === 'false' && isset($_SESSION)) {
            unset($_POST);
            unset($_SESSION);
            session_unset();
            session_destroy();
            header('location: ../presentatielaag/fletnix.php');
        }
    }

    // Deze functie laat een bericht weergeven op het scherm bij een succesvolle login
    function loginBericht()
    {
        if (isset($_SESSION["user"])) {
            echo 'Je bent ingelogd';
        }
    }

    public static function getSessionData() {
        return unserialize(serialize($_SESSION["user"]??null));
    }
}

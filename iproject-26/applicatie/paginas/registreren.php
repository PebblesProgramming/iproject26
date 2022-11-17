<?php
session_start();

$message = [
    "enabled" => false,
    "status" => null,
    "title" => null,
    "message" => null
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once(__DIR__ . "/../classes/User.php");

    $user = new User;

    try {
        if (!$user->validate()) {
            throw new \PDOException("Sommige velden kloppen niet");
        }
        $user = $user->registreerGebruiker($_POST);
        $user->login();

        $message["enabled"] = true;
        $message["status"] = "success";
        $message["title"] = "U bent nu ingelogd en geregistreerd";
        $message["message"] = "Registratie voltooid, u bent nu ingelogd";
    } catch (\PDOException $e) {
        $message["enabled"] = true;
        $message["status"] = "error";
        $message["title"] = "Er ging wat fout bij het registreren";
        $message["message"] = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/forms.css">
    <link rel="stylesheet" href="../css/nav.css">
</head>

<body>
    <?php require_once(__DIR__ . "/../componenten/navigationbar.php"); ?>

    <div>
        <div class="d-flex justify-content-center align-items-center inputForm my-5">
            <div class="col-12 col-md-5">
                <h2 class="px-3">Registreren</h2>
                <form class="px-3" action="registreren.php" method="post">
                    <div class="form-group">
                        <label for="gebruikersnaam">Gebruikersnaam</label>
                        <input id="gebruikersnaam" class="form-control" type="text" name="gebruikersnaam" placeholder="Gebruikersnaam" required>
                    </div>

                    <div class="form-group">
                        <label for="voornaam">Voornaam</label>
                        <input id="voornaam" class="form-control" type="text" name="voornaam" placeholder="Voornaam" required>
                    </div>

                    <div class="form-group">
                        <label for="achternaam">Achternaam</label>
                        <input id="achternaam" class="form-control" type="text" name="achternaam" placeholder="Achternaam" required>
                    </div>

                    <div class="form-group">
                        <label for="adres1">Adres</label>
                        <input id="adres1" class="form-control" type="text" name="adres1" placeholder="Adres" required>
                    </div>

                    <div class="form-group">
                        <label for="adres2">2e adres</label>
                        <input id="adres2" class="form-control" type="text" name="adres2" placeholder="2e adres">
                    </div>

                    <div class="form-group">
                        <label for="postcode">Postcode</label>
                        <input id="postcode" class="form-control" type="text" name="postcode" placeholder="Postcode" required>
                    </div>

                    <div class="form-group">
                        <label for="plaats">Plaats</label>
                        <input id="plaats" class="form-control" type="text" name="plaats" placeholder="Plaats" required>
                    </div>


                    <?php
                    function genereerLandenDropDown()
                    {
                        require_once(__DIR__ ."/../classes/Land.php");
                        $landen = new Land();

                        $landen = $landen->all();
                        foreach ($landen??[] as $land) {
                            echo ("<option value=\"$land->landnaam\">$land->landnaam</option>");
                        }
                    }
                    ?>

                    <div class="form-group">
                        <label for="land">Land</label>
                        <select class="form-control" id='land' name='land'>
                            <?php
                            echo genereerLandenDropDown() ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="geboortedag">Geboortedag</label>
                        <input id="geboortedag" class="form-control" type="date" name="geboortedag" required>
                    </div>

                    <div class="form-group">
                        <label for="emailadres">E-mail adres</label>
                        <input id="emailadres" class="form-control" type="text" name="emailadres" placeholder="E-mail" required>
                    </div>

                    <div class="form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input id="wachtwoord" class="form-control" type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                    </div>

                    <div class="d-inline-block w-100">
                        <label for="verkoperstatus">Registreren als verkoper?</label>
                        <input id="verkoperstatus" type="checkbox" name="verkoperstatus">
                    </div>

                    <button class="hanButton mt-3" type="submit">
                        Registreer
                    </button>

                </form>
            </div>
        </div>
    </div>


    <?php require_once(__DIR__ . "/../componenten/footer.php"); ?>

    <script type="text/javascript">
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 7000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        <?php
        if ($message["enabled"] == true) {
        ?>
            Toast.fire({
                icon: "<?php echo $message["status"] ?>",
                title: "<?php echo $message["title"] ?>"
            });
        <?php
        }
        ?>
    </script>
</body>

</html>
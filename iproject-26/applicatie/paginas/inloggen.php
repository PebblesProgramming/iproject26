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
        $user = $user->inloggenControleren();
        if($user) {
            $user->login();
            $message["enabled"] = true;
            $message["status"] = "success";
            $message["title"] = "U bent nu ingelogd";
            $message["message"] = "Inloggen gelukt";
        } else {
            throw new \PDOException("Kon niet inloggen");
        }
    } catch (\PDOException $e) {
        $message["enabled"] = true;
        $message["status"] = "error";
        $message["title"] = "Er ging wat fout bij het inloggen";
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

    <title>Inloggen</title>

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/forms.css">
</head>

<body>
    <?php require_once(__DIR__ . "/../componenten/navigationbar.php"); ?>

    <div>
        <div class="d-flex justify-content-center align-items-center inputForm my-5">
            <div class="col-12 col-md-5">
                <h2 class="px-3">Inloggen</h2>
                <form class="px-3" action="inloggen.php" method="post">
                    <div class="form-group">
                        <label for="emailadres">E-mail adres</label>
                        <input id="emailadres" class="form-control" type="text" name="emailadres" placeholder="E-mail" required>
                    </div>

                    <div class="form-group">
                        <label for="wachtwoord">Wachtwoord</label>
                        <input id="wachtwoord" class="form-control" type="password" name="wachtwoord" placeholder="Wachtwoord" required>
                    </div>

                    <button class="hanButton mt-3" type="submit">
                        Login
                    </button>

                </form>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . "/../componenten/footer.php"); ?>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        })

        <?php
        if($message["enabled"] == true) {
        ?>
            Toast.fire({
                icon: "<?php echo $message["status"] ?>",
                title: "<?php echo $message["title"] ?>"
            })
        <?php 
        } 
        ?>

    </script>
</body>

</html>
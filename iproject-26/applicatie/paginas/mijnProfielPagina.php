<?php

$message = [
  "enabled" => false,
  "status" => null,
  "title" => null,
  "message" => null
];

require_once __DIR__ . "/../classes/User.php";
session_start();

if (!isset($_SESSION["user"])) {
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  header("location: $link/paginas/index.php");
}

$edit = isset($_GET["edit"]);

if (isset($_POST["save"])) {
  try {
    $user = new User;
    if (!$user->validate()) {
      throw new \PDOException("Sommige velden kloppen niet");
    }
    foreach ($_POST as $key => $value) {
      if ($key === "save") {
        continue;
      }
      $user->$key = $value;
    }
    if (in_array("verkoperstatus", array_keys($_POST))) {
      $user->verkoperstatus = true;
    } else {
      $user->verkoperstatus = false;
    }

    $user->wachtwoord = $_SESSION["user"]->wachtwoord;
    $user->update();
    $user->login();

    $message["enabled"] = true;
    $message["status"] = "success";
    $message["title"] = "Je profiel is nu aangepast";
  } catch (\PDOException $e) {
    $message["enabled"] = true;
    $message["status"] = "error";
    $message["title"] = "Er ging wat fout bij het aanpassen van je profiel";
    $message["message"] = $e->getMessage();
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <title>Mijn Profiel</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/mijnProfielPagina.css">
</head>

<body>
  <?php require_once __DIR__ . "/../componenten/navigationbar.php" ?>
  <div class="container">

    <div class="container bootdey flex-grow-1 container-p-y">

      <div class="media align-items-center py-3 mb-3">
        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="d-block ui-w-100 rounded-circle">
        <div class="media-body ml-4">
          <h4 class="font-weight-bold"><?php echo sprintf("%s %s", $_SESSION["user"]->voornaam ?? "", $_SESSION["user"]->achternaam ?? "") ?> <span class="text-muted font-weight-normal">@<?php echo $_SESSION["user"]->gebruikersnaam ?></span></h4>
          <a href="mijnProfielPagina.php?edit=1" class="btn btn-primary btn-sm">Wijzigen</a>&nbsp;
          <a href="javascript:void(0)" class="btn btn-default btn-sm">Profile</a>&nbsp;
        </div>
      </div>

      <div class="card">
        <div class="card-body">

          <h6 class="mt-4 mb-3">Algemene informatie</h6>

          <form action="mijnProfielPagina.php" method="POST">

            <table class="table user-view-table m-0">
              <tbody>
                <tr>
                  <td>Geregistreerd:</td>
                  <td>01/23/2017</td>
                </tr>
                <tr>
                  <td>Laatst online:</td>
                  <td>01/23/2018 (14 days ago)</td>
                </tr>
                <tr>
                  <td>Verified:</td>
                  <td><span class="fa fa-check text-primary"></span>&nbsp; Yes</td>
                </tr>
                <tr>
                  <td>Rol:</td>
                  <td>Verkoper</td>
                </tr>
                <tr>
                  <td>Status:</td>
                  <td><span class="badge badge-outline-success">Actief</span></td>
                </tr>
                <tr>
                  <td>Rating:</td>
                  <td>7/10</td>
                </tr>
              </tbody>
            </table>
        </div>

      </div>

      <div class="card">
        <div class="card-body">

          <h6 class="mt-4 mb-3">Gebruiker</h6>

          <table class="table user-view-table m-0">
            <tbody>
              <tr>
                <td>Gebruikersnaam:</td>
                <td>
                  <input type="text" class="form-control" name="gebruikersnaam" placeholder="gebruikersnaam" value="<?php echo $_SESSION["user"]->gebruikersnaam ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required>
                </td>
              </tr>
              <tr>
                <td>Voornaam:</td>
                <td><input type="text" class="form-control" name="voornaam" placeholder="voornaam" value="<?php echo $_SESSION["user"]->voornaam ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
              <tr>
                <td>Achternaam:</td>
                <td><input type="text" class="form-control" name="achternaam" placeholder="achternaam" value="<?php echo $_SESSION["user"]->achternaam ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
              <tr>
                <td>Geboortedag:</td>
                <td><input type="date" class="form-control" name="geboortedag" placeholder="geboortedag" value="<?php echo $_SESSION["user"]->geboortedag ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>



      <div class="card">
        <div class="card-body">
          <h6 class="mt-4 mb-3">Aanvullende informatie</h6>
          <table class="table user-view-table m-0">
            <tbody>
              <tr>
                <td>Adres:</td>
                <td><input type="text" class="form-control" name="adres1" placeholder="adres" value="<?php echo $_SESSION["user"]->adres1 ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
              <tr>
                <td>2e Adres:</td>
                <td>
                  <input type="text" class="form-control" name="adres2" placeholder="2e adres" value="<?php echo $_SESSION["user"]->adres2 ?? null ?>" <?php echo !$edit ? "disabled" : null ?>>
                </td>
              </tr>
              <tr>
                <td>Postcode:</td>
                <td> <input type="text" class="form-control" name="postcode" placeholder="postcode" value="<?php echo $_SESSION["user"]->postcode ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required> </td>
              </tr>
              <tr>
                <td>Plaats:</td>
                <td><input type="text" class="form-control" name="plaats" placeholder="postcode" value="<?php echo $_SESSION["user"]->plaats ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
              <tr>
                <td>Land:</td>
                <td><input type="text" class="form-control" name="land" placeholder="land" value="<?php echo $_SESSION["user"]->land ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
              <tr>
                <td>
                  <div class="d-inline-block w-100">
                    <label for="verkoperstatus">Registreren als verkoper?</label>
                    <input id="verkoperstatus" type="checkbox" name="verkoperstatus" value="<?php echo $_SESSION["user"]->verkoperstatus == true ? "on" : "off" ?>" <?php echo !$edit ? "disabled" : null ?>>
                  </div>
                </td>


              </tr>
            </tbody>
          </table>
        </div>
      </div>


      <div class="card">
        <div class="card-body">
          <h6 class="mt-4 mb-3">Contactinformatie</h6>
          <table class="table user-view-table m-0">
            <tbody>
              <tr>
                <td>E-mail:</td>
                <td><input type="text" class="form-control" name="emailadres" placeholder="emailadres" value="<?php echo $_SESSION["user"]->emailadres ?? null ?>" <?php echo !$edit ? "disabled" : null ?> required></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="card">
        <div class="card-body">

          <h6 class="mt-4 mb-3">Interesse</h6>

          <table class="table user-view-table m-0">
            <tbody>
              <tr>
                <td>Categorie:</td>
                <td>
                  -
                </td>
            </tbody>
          </table>
        </div>
      </div>
      <button class="btn btn-success" type="submit" name="save" value="1">
        Opslaan
      </button>
      </form>
    </div>
  </div>

  <?php
  require '../componenten/footer.php';

  ?>
  <script type="text/javascript">
    $(document).ready(function() {
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
    });
  </script>
</body>

</html>
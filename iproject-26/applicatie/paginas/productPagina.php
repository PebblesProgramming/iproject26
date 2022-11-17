<?php

$message = [
  "enabled" => false,
  "status" => null,
  "title" => null,
  "message" => null
];

if (!isset($_GET["id"])) {
  $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
  header("location: $link/paginas/index.php");
}

require_once __DIR__ ."/../classes/User.php";

session_start();

require_once(__DIR__ . "/../classes/DatabaseError.php");
require_once(__DIR__ . "/../classes/Product.php");
require_once(__DIR__ . "/../classes/Review.php");
require_once(__DIR__ . "/../classes/User.php");
require_once(__DIR__ . "/../classes/Bid.php");

$product;
$relatedCategories;
$averageReview;

try {
  $product = (new Product)->findOne($_GET["id"]);
  $relatedCategories = $product->getCategories();
  $averageReview = (new Review)->getAverageFromUser($product->verkoper);
} catch (DatabaseError $e) {
  $message["enabled"] = true;
  $message["status"] = "error";

  switch ($e->getType()) {
    case DatabaseError::PRODUCT_ERR:
      $message["message"] = "Kon product data niet ophalen";
      break;
    case DatabaseError::REVIEW_ERR:
      $message["message"] = "Kon review data niet ophalen";
      break;
  }
}

// Set new highest bid if the submitted bid is higher than the previous highest bid
$bid = new Bid;
if (isset($_POST["nieuwBod"])) {
  $currentHighest = (float)$bid->getHighest()->bodbedrag;

  if ($currentHighest < $_POST["nieuwBod"]) {
    try {
      $bid->voorwerpnummer = $_GET["id"];
      $bid->bodbedrag = $_POST["nieuwBod"];
      $bid->gebruikersnaam = unserialize(serialize($_SESSION["user"]))->gebruikersnaam;
      
      $bid->boddag = date("Y-m-d");
      $bid->bodtijdstip = date("h:i:s");
      $bid->save();

      $message["enabled"] = true;
      $message["status"] = "success";
      $message["message"] = "Uw bod is opgeslagen, u heeft nu het hoogste bod";
    } catch (\PDOException $e) {
      $message["enabled"] = true;
      $message["status"] = "error";
      $message["message"] = "Er ging wat fout met het opslaan van uw bod";
    }
  } else {
    $message["enabled"] = true;
    $message["status"] = "error";
    $message["message"] = "Uw bod is kleiner dan het vorige bod op dit product";
    // $message["message"];
  }
}

$highestBid = $bid->getHighest();

if(isset($_POST["cijfer"]) && isset($_POST["verkoper"])) {
  try {
    $review = new Review;
    $review->cijfer = $_POST["cijfer"];
    $review->verkoper = $_POST["verkoper"];
    $review->save();

    $message["enabled"] = true;
    $message["status"] = "success";
    $message["message"] = "Uw review is opgeslagen";
  } catch (\PDOException $e) {
    $message["enabled"] = true;
    $message["status"] = "error";
    $message["message"] = "Kon de review niet opslaan";
  }
}

?>

<!doctype html>
<html lang="en">

<head>
  <title>Producten</title> <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../css/reset.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/productPagina.css">
</head>


<body>
  <?php require_once '../componenten/navigationbar.php' ?>

  <!--Product titel-->
  <div class="container mt-4">
    <div class="row">
      <div class="col-12">
        <h2>Titel</h2>
      </div>
      <div class="col-7 mr-2 p-3 mb-5 bg-white rounded">
        <div class="plaatje"><img src="../afbeeldingen/fiets.jpg" class="img-fluid" alt="Responsive image" width="700px" height="1000px"></div>
      </div>
      <div class="col mb-5">
        <div class=" shadow-lg bg-white rounded-lg h-100">
          <div id="time-remaining" class="rounded-top">
            <h4 class="py-3 text-center">Sluit in <span></span></h4>
          </div>
          <div class="p-3">

            <?php if (!isset($_SESSION["user"])) { ?>
              <div class="row mb-2">
                <div class="col">
                  <a href="registreren.php" class="btn btn-success">Meld je gratis aan</a>
                  <a href="inloggen.php" class="btn btn-danger">Log in om te bieden</a>
                </div>
              </div>
            <?php } else { ?>
              <!--Huidige bod blok-->
              <form name="form" action="" method="post">
                <div class="input-group input-group-lg mb-3 shadow-sm">
                  <div class="input-group-prepend">
                    <p class="input-group-text border-0">&euro;</p>
                  </div>
                  <input type="number" name="nieuwBod" id="nieuwBod" class="form-control border-0 bid-input" aria-label="Amount (to the nearest euro)" aria-describedby="button-addon" required>
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-success rounded-right bid-input" id="button-addon">Bied</button>
                  </div>
                </div>
              </form>
            <?php } ?>

            <?php if (isset($product)) { ?>
              <!--Product beschrijving tekst-->
              <div id="productInfo" class="row">
                <!--Tabel met informatie over de kavel-->
                <div class="col-12 col-sm-6">
                  <p>Huidig bod: </p>
                </div>
                <div class="col-12 col-sm-6">
                  <p>
                    &euro;<?php
                          echo $highestBid->bodbedrag??"0.00";
                          ?>
                  </p>
                </div>

                <p class="col-12 col-sm-6">Contact verkoper: </p>
                <p class="col-12 col-sm-6">
                  <?php $product->echo("verkoper") ?>
                  <small>
                    <?php  ?>
                  </small>
                </p>

                <p class="col-12 col-sm-6">Locatie: </p>
                <p class="col-12 col-sm-6"><?php $product->echo("plaats") ?></p>

                <p class="col-12 col-sm-6">Startbod: </p>
                <p class="col-12 col-sm-6"><?php $product->echo("startprijs") ?></p>
                <p class="col-12 col-sm-6">Voorwerpnummer: </p>
                <p class="col-12 col-sm-6"><?php $product->echo("voorwerpnummer") ?></p>
                <p class="col-12 col-sm-6">Categorieen: </p>
                <p class="col-12 col-sm-6">
                  <?php foreach ($relatedCategories ?? [] as $category) { ?>
                    <?php echo $category->rubrieknaam ?>
                    <br>
                  <?php } ?>
                </p>
                <p class="col-12 col-sm-6">Verkoper: </p>
                <div class="col-12 col-sm-6">
                  <p class="mb-0">
                    <?php $product->echo("verkoper") ?><br>
                  </p>
                  <?php if (isset($averageReview[0]->cijfer)) { ?>
                    <small>Gemiddelde review: <?php echo $averageReview[0]->cijfer ?>/10</small>
                  <?php } ?>
                  <?php if(isset($_SESSION["user"])) { ?>
                    <form action="productPagina.php?id=<?php echo $_GET["id"] ?>" class="pr-3 py-3" method="POST">
                      <input type="hidden" name="verkoper" value="<?php $product->echo("verkoper") ?>">
                      <select class="form-control mb-2 w-100" name="cijfer">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                      </select>
                      <button type="submit" class="btn hanButton w-100">beoordeel</button>
                    </form>
                  <?php } ?>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="kavelBeschrijving" class="mt-5">
    <div class="container">
      <!--Kavelbeschrijving-->
      <div class="row">
        <div class="col-12">
          <div class="mt-1 shadow p-3 my-5 bg-white rounded">
            <h3>Kavelbeschrijving</h3>
            <?php if (isset($product)) { ?>
              <br>
              <!--Tabel met informatie over het aangeboden product -->
              <p><?php $product->echo("beschrijving") ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php require_once __DIR__ . "/../componenten/footer.php" ?>

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      <?php if(isset($product)) { ?>
      const deadlineIn = () => {
        const currentDate = new Date();
        const dateString = <?php echo json_encode($product->looptijdeindedag . " " . $product->looptijdeindetijdstip) ?>;
        const endDate = new Date(dateString);

        console.log(endDate);

        let diff = endDate - currentDate;

        const ms = diff % 1000;
        diff = (diff - ms) / 1000
        const ss = diff % 60;
        diff = (diff - ss) / 60
        const mm = diff % 60;
        diff = (diff - mm) / 60
        const hh = diff % 24;
        days = (diff - hh) / 24

        if (diff < 0 || hh < 0 || mm < 0 || ss < 0) {
          $("#time-remaining > h4").text("Biedtijd verlopen");
          $(".bid-input").prop("disabled", true);
        } else if (days === 0) {
          $("#time-remaining > h4 > span").text(hh + ":" + mm + ":" + ss);
        } else {
          $("#time-remaining > h4 > span").text(days + " dagen");
        }
      };
      deadlineIn();
      setInterval(deadlineIn, 1000);
      <?php } ?>

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
      if ($message["enabled"] === true) {
      ?>
        Toast.fire({
          icon: "<?php echo $message["status"] ?>",
          title: "<?php echo $message["message"] ?>"
        })
      <?php
      }
      ?>

      var swal_alert = localStorage.getItem("alert");

      if (swal_alert != 1) {
        Swal.fire({
          title: "Stappen om te kunnen bieden",
          html: "<br /><strong>stap 1:</strong> Kies een product waarop je mogelijk wilt bieden.<br /><strong>Stap 2:</strong> Log in om te bieden of registreer een account.<br /><strong> Stap 3:</strong> Plaats een bod hoger dan het geplaatste bod.",
          timer: 15000,
          type: "warning",
          confirmButtonColor: '#008000',
          confirmButtonText: "Begrepen",
          showConfirmButton: true
        });
      }

      localStorage.setItem("alert", "1");
    });
  </script>

</body> <!-- Footer -->


</html>
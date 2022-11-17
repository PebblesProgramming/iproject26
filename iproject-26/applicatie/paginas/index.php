<?php
session_start();
require_once(__DIR__ . "/../classes/Product.php");
require_once(__DIR__ . "/../classes/User.php");

$products = (new Product)->allWithLimit();
?>

<!doctype html>
<html lang="en">

<head>
  <title>EenmaalAndermaal</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <div class="row"> -->
  </nav>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/forms.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/main.css">

</head>

<body>
  <?php require_once '../componenten/navigationbar.php' ?>
  <div id="intro" class="bg-image shadow-2-strong d-flex justify-content-center align-items-center">
    <div class="container">
      <div class="row">
        <div class="col-md-7">
          <h1 class="xlarge-font"><b>Welkom</b></h1>
          <h2 class="large-font" style="color:#E50056;"><b>Bij de beste veilingsite van de EU</b></h2>
          <p><span style="font-size:16px">Bekijk nu de beste en mooiste aanbiedingen</span> </p>
          <a href="../paginas/resultaatPagina.php" type="button" class="btn text-white btn-dark">Ontdek</a>
        </div>
        <div class="col-md-5">
          <img src="../afbeeldingen/kinderen.jpg" alt="foto" width="100%" height="100%">
        </div>
      </div>
    </div>
  </div>
  <!-- Background image -->
  <br>

  <!--Garantie bar-->

  <div id="guaranteesBar">

  <!--
    K: Er is goed gebruik gemaakt van Bootstrap en alles is overzichtelijk
    O: Ik zie deze implementatie van Bootstrap als goed
    E: Bootrsteap zorgt ervoor dat de opmaak van de site makkelijker
        En sneller gemaakt kan worden. 
    T: Kijk of je bepaalde code kan hergebruiken zodat je niet 3x 
       hetzelfde hebt staan
   -->
    <div class="container">
      <div class="row">
          <div class="col-md-5">
            <i class="bi bi-check2-all pr-6"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#Ecb044" class="bi bi-check2-all" viewBox="0 0 16 16">
              <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
              <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
            </svg>
            <p>100.000 gebruikers per dag</p>
          </div>
          <div class="col-md-4">
            <i class="bi bi-check2-all pr-6"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#Ecb044" class="bi bi-check2-all" viewBox="0 0 16 16">
              <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
              <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
            </svg>
            <p>Niet tevreden? Geld terug!</p>
          </div>
          <div class="col-md-3">
            <i class="bi bi-check2-all pr-6"></i><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#Ecb044" class="bi bi-check2-all" viewBox="0 0 16 16">
              <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
              <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
            </svg>
            <p>20.000 geauthenticeerde verkopers</p>
          </div>
      </div>
    </div>
  </div>

  <br>

  <div class="product-column1">
    <div class="container">
      <h1>Populaire Artikelen</h1>
      <br>
        <div class="row text-center">
          <?php foreach ($products ?? [] as $product) { ?>
            <div class="col-4">
              <div class="card">
                <img class="card-img-top" src="https://iproject26.ip.aimsites.nl/pics/dt_1_<?php $product->echo("voorwerpnummer") ?>.jpg" alt="Card image cap">
                <div class="card-body">
                  <p class="card-body"><?php $product->echo("titel") ?></p>
                  <p class="card-text mr-20">Eindigd <?php $product->echo("looptijdeinde") ?></p>
                  <p class="card-text">Prijs <?php $product->echo("verkoopprijs") ?></p>
                  <button type="button" name="" id="" class="btn btn-dark" style="color: #E50056;" btn-lg btn-block>Bekijk</button>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
    </div>

    <br>
    <div class="product-column1">
      <div class="container">
        <h1>Binnenkort Gesloten</h1>
        <br>
          <div class="row text-center">
            <?php foreach ($products ?? [] as $product) { ?>
              <div class="col-4">
                <div class="card">
                  <img class="card-img-top" src="https://iproject26.ip.aimsites.nl/pics/dt_1_<?php $product->echo("voorwerpnummer") ?>.jpg" alt="Card image cap">
                  <div class="card-body">
                    <p class="card-body"><?php $product->echo("titel") ?></p>
                    <p class="card-text mr-20">Eindigd <?php $product->echo("looptijdeinde") ?></p>
                    <p class="card-text">Prijs <?php $product->echo("verkoopprijs") ?></p>
                    <button type="button" name="" id="" class="btn btn-dark" style="color: #E50056;" btn-lg btn-block>Bekijk</button>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        <br>
        <br>
      </div>
      <br>
    </div>
  </div>


  <?php
  require '../componenten/footer.php';
  ?>

</body>

</html>
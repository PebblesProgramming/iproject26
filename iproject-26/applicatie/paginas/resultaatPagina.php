<?php
session_start();

require_once __DIR__ . "/../classes/Product.php";
require_once __DIR__ . "/../classes/Offer.php";

  $limit= 2;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  $authors = ("SELECT * FROM Voorwerp LIMIT $paginationStart, $limit");
  // Get total records
  $sql = 5;
  // $sql = ("SELECT count(*) AS id FROM Voorwerp");
  // $allRecrods = $sql[0]['count(*)'];
  // Calculate total pages
  $totoalPages = ceil($sql / $limit);
  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;

$offers;
$products;
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["search"])) {
    $products = (new Product)->getByKeyword($_POST["search"]);
    $offers = (new Offer)->getByProductIds($products[1]);
    $products = $products[0];
  }
} else {
  $products = (new Product)->all();
  $offers = (new Offer)->all();
}

$productLowestValued = (new Product)->getMinPrice() ?? 0.00;
$productHighestValued = (new Product)->getMaxPrice() ?? 0.00;


$jsProducts = [];
foreach ($offers ?? [] as $offer) {
  foreach ($products as $key => $product) {
    $products[$key]->biedingen = [];
    $products[$key]->hoogsteBieding = new Offer;

    $highest = 0.00;
    if ($offer->voorwerpnummer == $product->voorwerpnummer) {
      if ($offer->bodbedrag > $highest) {
        $highest = $offer->bodbedrag;
        $products[$key]->hoogsteBieding = $offer;
      }
      array_push($products[$key]->biedingen, $offer);
    }
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <title>Voorwerp</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="../css/reset.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/nav.css">
  <link rel="stylesheet" href="../css/main.css">
</head>

<body>
  <?php require_once '../componenten/navigationbar.php' ?>

  <div class="container">
    <div class="row">
      <div class="col col-lg-3 text-start">
        <h3>Filter</h3>
        <ul class="nav flex-column">
          <li>
            &euro;<input type="text" id="amount_min" class="w-25" style="border:0; color:#f6931f; font-weight:bold;">
            &euro;<input type="text" id="amount_max" class="w-25" style="border:0; color:#f6931f; font-weight:bold;">
            <div id="slider" class="my-3">
            </div>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-muted"> Filter
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-muted">
              Filter</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-muted">
              Filter</a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link text-muted">
              Filter</a>
          </li>
        </ul>
      </div>
      <div class="col col-lg-9 d-flex flex-wrap justify-content-between">
        <div class="container-fluid my-5">
          <div id="products" class="row">
            <?php foreach ($products ?? [] as $key => $product) { ?>
              <div id="product-<?php echo $key ?>" class="col-4 <?php echo $key > 2 ? "mt-5" : null ?>">
                <div class="card">
                  <a href="../paginas/productPagina.php?id=<?php $product->echo("voorwerpnummer") ?>">
                    <img src="https://iproject26.ip.aimsites.nl/pics/dt_1_<?php $product->echo("voorwerpnummer") ?>.jpg" class="card-img-top img-fluid" alt="Product" />
                  </a>
                  <div class="card-body">
                    <p class="card-text fw-bold">
                      <?php $product->echo("titel") ?>
                    </p>
                    <small class="text-secondary">
                      Huidig Bod: <?php echo $product->hoogsteBieding->bodbedrag ?? null ?>
                      <input id="startprijs-<?php echo $key ?>" type="hidden" value="<?php echo $product->startprijs ?? null ?>">
                    </small>
                  </div>
                </div>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
          <nav aria-label="Page navigation example mt-5">
          <ul class="pagination justify-content-center">
            <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
              <a class="page-link"
              href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>">Previous</a>
                </li>
                  <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                  <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                  <a class="page-link" href="resultaatPagina.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                  </li>
                  <?php endfor; ?>
                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
              <a class="page-link"
              href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?page=". $next; } ?>">Next</a>
            </li>
          </ul>
      </nav>
  </div>


  <?php require_once __DIR__ ."/../componenten/footer.php" ?>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>


  <script type="text/javascript">
    const minVal = <?php echo $productLowestValued ?>;
    const maxVal = <?php echo $productHighestValued ?>;

    const productCount = <?php echo count($products) ?>;

    let removed = [];
    let removedPrices = [];

    let lastSliderVal = 0;

    const restyleProducts = () => {
      const allProducts = $("#products").children();

      allProducts.each((index, elem) => {
        if (index > 2) {
          $(elem).addClass("mt-5");
        } else {
          $(elem).removeClass("mt-5");
        }
      });
    }

    $(document).ready(function() {
      $("#slider").slider({
        range: true,
        min: minVal,
        max: maxVal,
        values: [minVal, maxVal],
        slide: function(event, ui) {
          $("#amount_min").val(ui.values[0]);
          $("#amount_max").val(ui.values[1]);

          for (var i = 0; i < productCount; i++) {
            const priceToCheck = $("#startprijs-" + i).val();
            if (priceToCheck < ui.values[0] || priceToCheck > ui.values[1]) {
              removed[i] = $("#product-" + i).clone();
              removedPrices[i] = priceToCheck;
              $("#product-" + i).remove();
            } else if (removedPrices[i] >= ui.values[0] && removedPrices[i] <= ui.values[1]) {
              $("#products").append(removed[i]);

              removedPrices.splice(i, 1)
              removed.splice(i, 1);
            }
          }

          restyleProducts();

          lastSliderVal = ui.values[1];
        }
      });
    });

    $("#amount_min").val(minVal);
    $("#amount_max").val(maxVal);
    $("#amount_min").change(function() {
      $("#slider-range").slider("values", 0, $(this).val());
    });
    $("#amount_max").change(function() {
      $("#slider-range").slider("values", 1, $(this).val());
    });
  </script>
</body>





</body>

</html>
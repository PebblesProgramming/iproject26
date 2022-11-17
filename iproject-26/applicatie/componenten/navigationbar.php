<?php

require_once __DIR__ . "/../classes/Category.php";

$result = (new Category)->getRubrieken();

$result = (new Category)->buildTree($result);

function generateSub($rubriek, $subrubrieken = null)
{
  echo <<< EOT
  <li class="dropdown-submenu">
    <a href="../paginas/resultaatPagina.php?category=$rubriek->rubrieknaam" class="d-inline-block">$rubriek->rubrieknaam</a>
  EOT;
  if (count($subrubrieken ?? $rubriek->children) > 0) {
    if(count($rubriek->children) > 0) {
      echo "<ul class=\"dropdown-menu multi-level\">";
    }
    foreach ($subrubrieken ?? $rubriek->children as $subrubriek) {
      if (count($subrubriek->children) > 0) {
        generateSub($subrubriek, $subrubriek->children);
      }
    }
    if(count($rubriek->children) > 0) {
      echo "</ul>";
    }
  }
  echo <<< EOT
    </a>
  </li>
  EOT;
}

?>

<nav class="customNavbar navbar-center-1 navbar-dark bg-dark py-4">
  <div class="container">
    <div class="d-flex justify-content-between w-100">
      <div class="d-flex align-items-center">
        <a class="btn hanButton border" href="../paginas/index.php">Home</a>
        <div class="dropdown">
          <a class="btn hanButton dropdown-toggle ml-4" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Categorieën
          </a>

            <!-- <ul class="dropdown-menu multi-level" id="categories" aria-labelledby="dropdownMenuLink">
              <li class="dropdown-submenu">
                <a href="#">Auto's</a>
                <ul class="dropdown-menu">
                  <li><a href="#">Volkswagen</a></li>
                  <li><a href="#">Mazda</a></li>
                </ul>
              </li>
              <li class="dropdown-submenu">
                <a href="#">Gereedschap</a>
                <ul class="dropdown-menu">
                  <li><a href="#">Tangen</a></li>
                  <li><a href="#">Hamers</a></li>
                </ul>
              </li>
            </ul> -->

          <ul class="dropdown-menu multi-level" id="categories" aria-labelledby="dropdownMenuLink">
            <?php
            foreach ($result as $key => $rubriek) {
              generateSub($rubriek);
            }
            ?>

            <!-- <li class="dropdown-submenu">
              <a href="#">Gereedschap</a>
              <ul class="dropdown-menu">
                <li><a href="#">Tangen</a></li>
                <li><a href="#">Hamers</a></li>
              </ul>
            </li> -->
          </ul>
        </div>
      </div>

      <form action="../paginas/resultaatPagina.php" method="post">
        <div class="input-group">
          <input class="form-control " type="text" name="search" placeholder="Zoek naar producten...">
          <div class="input-group-append">
            <button type="submit" class="btn hanButton">Zoek</button>
          </div>
        </div>
      </form>

      <div>
        <div class="d-flex align-items-center">
          <div class="dropdown">
            <a class="btn hanButton dropdown-toggle ml-4" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Persoonlijk
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
              <?php if (!isset($_SESSION["user"])) { ?>
                <a class="dropdown-item" href="../paginas/inloggen.php">Inloggen</a>
                <a class="dropdown-item" href="../paginas/registreren.php">Registreren</a>
              <?php } else { ?>
                <a class="dropdown-item" href="../paginas/mijnProfielPagina.php">Mijn Profiel</a>
                <a class="dropdown-item" href="../paginas/uitloggen.php">Uitloggen</a>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</nav>
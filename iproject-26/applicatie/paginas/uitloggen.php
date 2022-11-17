<?php 
session_start();

unset($_SESSION["user"]);

$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
header("location: $link/paginas/index.php");
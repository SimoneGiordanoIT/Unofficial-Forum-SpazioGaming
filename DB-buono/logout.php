<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
;

session_start();
unset($_SESSION);
session_destroy();

header("Location: ../PAGINE SITO/LAHOME.php")

?>



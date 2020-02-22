<?php

$db_nome = "forumdb";
$db_tab_utente = "UtentiDB";


$mysqliConnection = new mysqli("localhost", "root", "", $db_nome);


if (mysqli_connect_errno($mysqliConnection)) {
    printf("Errore nella connessione al database: %s\n", mysqli_connect_error($mysqliConnection));
    exit();
}

?>
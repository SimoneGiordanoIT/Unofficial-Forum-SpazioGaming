<?php

/*Lo script si occupa di stampare in un menù cliccabile, l'elenco degli utenti bannati, a cui l'admin può rimuovere il ban. Inizialmente si effettua la connessione al DB, e si interroga il DB, prelevando tutti gli
utenti che sono stati bannati, ovvero gli utenti con campo ban pari a 1. Successivamente per ogni utente si preleva il suo nome e il suo ID, e si stampa il nome dell'utente bannato. Se l'admin dovesse cliccare su
uno degli elementi del menù cliccabile, allora verrà passato l'ID dell'utente che si vuole sbannare allo script adibito a tale funzione (sbannaUtenteDaADMIN.php). */

ini_set('display_errors', 1);
error_reporting(E_ALL);

include("../DB-buono/connection.php");

$sql = "SELECT * 
        FROM $db_tab_utente
        WHERE ban = \"1\" 
        ";

    if (!$result = mysqli_query($mysqliConnection, $sql)) {
        printf("Errore nella query di ricerca reputazioni\n");
    exit();
    }

    while($row = mysqli_fetch_assoc($result)){
        $nomeUtente = $row['nome'];
        $ID_Utente = $row['userID'];

        echo"
        <div class='vertical_menu'>
            <form method=\"post\" action=\"../sbannaUtenteDaADMIN.php\">
            <input type=\"hidden\" name=\"ID_Utente\" value=\"$ID_Utente\">
            <button type \"submit\" value=\"VAI\"> Nome utente: \"$nomeUtente\" </button>
            </form>
        </div>
        ";
    }


?>
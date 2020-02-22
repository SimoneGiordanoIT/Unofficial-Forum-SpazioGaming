<?php

/*Lo script si occupa di rimuovere il ban di un determinato utente, a opera dell'admin. A tal scopo, inizialmente si memorizza in una variabile apposita l'ID dell'utente bannato, e si effettua la connessione al DB.
Successivamente si esegue una query di update in cui si imposta a 0 il campo ban dell'utente che si intende sbannare. Infine si carica la pagina precedente a quella corrente. */


session_start();


$ID_Utente_bannato = $_POST['ID_Utente'];

include("./DB-buono/connection.php");

$query = "   UPDATE $db_tab_utente 
				SET ban=\"0\"
				WHERE userID=\"$ID_Utente_bannato\"
                ";
                
        if (!$result = mysqli_query($mysqliConnection, $query)) {
            printf("Errore nella query di salvataggio\n");
        exit();
        }

        echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script>
            
            window.history.back();
            
        </script>"; 

?>
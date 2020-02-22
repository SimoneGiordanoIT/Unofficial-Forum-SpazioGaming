<?php

/* Lo script gestisce il ban di un utente creatore di post. Per farlo, viene effettuato il collegamento al DB ed effettuata una query di update, in cui si aggiorna il campo ban a 1 dell'utente che ha ID pari a quello
passato mediante la variabile post; eseguita l'operazione, si verifica che la query sia stata eseguita correttamente sul DB, stampato un messaggio in caso di errore, e successivamente ricaricata la pagina precedente
alla seguente */

session_start();

    $ID_CREATORE_POST=$_POST['ID_CREATORE'];

    include("DB-buono/connection.php");

    $query ="   UPDATE $db_tab_utente 
				SET ban=\"1\"
				WHERE userID=\"$ID_CREATORE_POST\"
				";
	
	if (!$result = mysqli_query($mysqliConnection, $query)) {
			printf("Errore nella query di salvataggio reputazione finale\n");
		exit();
		}

echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script>
    
    window.history.back();
    
</script>";


?>
<?php

/*Lo script si occupa di rimuovere il ban a un utente creatore di post. Per far ciò inizialmente si memorizza in una apposita variabile l'ID del creatore del post che si intende sbannare. Successivamente si esegue 
una query di update in cui si imposta a zero il campo ban dell'utente di interesse. Infine viene caricata la pagina precedente a quella corrente. */

session_start();

    $ID_CREATORE_POST=$_POST['ID_CREATORE'];

    include("DB-buono/connection.php");

    $query ="   UPDATE $db_tab_utente 
				SET ban=\"0\"
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
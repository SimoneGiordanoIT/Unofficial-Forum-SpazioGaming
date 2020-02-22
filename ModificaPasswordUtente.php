<?php
/*Lo script gestisce la modifica della password dell'utente. A tal scopo, si effettua la connessione iniziale al DB; successivamente si verifica che si stato sottomesso il bottone di aggiorna e quindi sia settata
la variabile NUOVAP. Si verifica che la password nuova coincida con la password reinserita; se il controllo da esito positivo allora viene memorizzata la nuova password in una apposta variabile. La nuova password 
viene hashata mediante l'apposita funzione, e infine viene effettuata la query di update sul server per aggiornare la nuova password dell'utente. Infine viene caricata la pagina della home.
Nella parte superiore della pagina è presente la form attraverso la quale l'utente può riempire i campi appositi per la modifica della password. */
?>


<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="DB-buono/createUser1.css">
        
        <title>MODIFICA PASSWORD</title>
    </head>
    
    <body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">

                        <form class="form-horizontal login-form" method="post">
                                
                                <h1>MODIFICA PASSWORD</h1>
                                <p>Per favore riempire i campi</p>

                                <div class="form-group relative">
                                <label for="username"><b>Username</b></label>
                                <input class="form-control input-lg" type="password" placeholder="Inserire nuova Password" name="password" required>
                                </div>

                                <div class="form-group relative">
                                <label for="psw"><b>Password</b></label>
                                <input class="form-control input-lg" type="password" placeholder="Inserire DI NUOVO la nuova Password" name="verificaP" required>
                                </div>

                                <div class="form-group">
                                <button class="btn btn-success btn-lg btn-block" type="submit" class="login" name="NUOVAP">AGGIORNA</button>
                                <button class="btn btn-success btn-lg btn-block" type="reset" class="cancel">Cancel</button>
                                <button class="btn btn-success btn-lg btn-block" onclick="goBack()">Go back</button> 
                                </div>
                  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="gradient.js"></script>
        <script>
            function goBack() {
            window.history.back();
            }
        </script>
    </body>
    
</html>

<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

include("DB-buono/connection.php");

$ID_UT= $_SESSION['ID'];

if (isset($_POST['NUOVAP'])){        

    if($_POST['password']==$_POST['verificaP']){

        $NuovaPSW = $_POST['password'];

        $hashedPassword = password_hash($NuovaPSW, PASSWORD_DEFAULT);

        $query ="   UPDATE $db_tab_utente 
				SET password = ('$hashedPassword')
				WHERE userID=('$ID_UT')
				";
	
	        if (!$result = mysqli_query($mysqliConnection, $query)) {
			printf("Errore nella query di aggiornamento ultimo accesso\n");
		    exit();
		    }
            
            header('Location: ../PAGINE SITO/LAHOME.php');  

    }
    else{
        echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script>
            
        window.alert('VALORI INSERITI NON CORRISPONDONO!')
    
            
        </script>";

    }
 
            
}  


?>

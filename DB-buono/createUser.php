<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="createUser.css">
            <title>Creazione utente</title>
        </head>
        
    
<body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">
                        <form class="form-horizontal login-form" method="post">
                                    <h1>Sign Up</h1>
                                    <p>Per favore riempire i campi per la registrazione</p>
                                    
                                   
                                    <div class="form-group relative">
                                        <label for="nome"><b>Nome</b></label>
                                        <input class="form-control input-lg" type="text" placeholder="Inserire nome" name="nome" required><i class="fa fa-user"></i>
                                    </div>
                                    <div class="form-group relative">
                                        <label for="cognome"><b>Cognome </b></label>
                                        <input class="form-control input-lg" type="text" placeholder="Inserire cognome" name="cognome" required><i class="fa fa-user"></i>
                                    </div>
                                    <div class="form-group relative">
                                        <label for="email"><b>Email</b></label>
                                        <input class="form-control input-lg" type="text" placeholder="Inserire email" name="email" required><i class="fa fa-user"></i>
                                    </div>
                                    <div class="form-group relative">
                                        <label for="username"><b>Username</b></label>
                                        <input class="form-control input-lg" type="text" placeholder="Inserire username" name="username" required><i class="fa fa-user"></i>
                                    </div>
                                    <div class="form-group relative">
                                        <label for="psw"><b>Password</b></label>
                                        <input class="form-control input-lg" type="password" placeholder="Inserire password" name="psw" required><i class="fa fa-user"></i>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button class="btn btn-success btn-lg btn-block" type="submit" name="invio">Sign Up</button>
                                        <button class="btn btn-success btn-lg btn-block" type="reset" >Cancel</button>
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


ini_set('display_errors', 1);
error_reporting(E_ALL);

include("./connection.php");

if(isset($_POST['invio']) ){ 
    if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['psw'])){
                $nome=  $_POST['nome'];
                $cognome=  $_POST['cognome'];
                $email= $_POST['email'];
                $username=  $_POST['username'];
                $password=  $_POST['psw'];
                $data_Accesso=  date('Y-m-d H:i:s');

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


            
                $sql = "INSERT INTO $db_tab_utente 
                    (nome,cognome,reputazione,data_ultimo_acc,email,username,password,ruolo,ban)
                    VALUES
                    (\"$nome\", \"$cognome\", \"3\",\"$data_Accesso\" , \"$email\", \"$username\", \"$hashedPassword\", \"1\", \"0\")";
                
                if (!$result = mysqli_query($mysqliConnection, $sql)) {
                    printf("Errore nella query\n");
                exit();
                }

                $xmlString = "";
                /*.. selects the parent directory from the current.*/
                foreach ( file("../PAGINE SITO/SCHEMI\gestionePref.xml") as $node ) {
                $xmlString .= trim($node);
                }

                $doc = new DOMDocument();
                if (!$doc->loadXML($xmlString)) {
                die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
                }

                $root = $doc->documentElement;
                
                $newPref = $doc->createElement("preferiti");

                /*Adesso prendo l'id dell'utente appena creato che sarà quindi l'ultimo utente aggiunto al DB*/

                $sql2 = "SELECT userID 
                FROM $db_tab_utente
                ORDER BY userID DESC
                LIMIT 1";
                
                if (!$result = mysqli_query($mysqliConnection, $sql2)) {
                    printf("Errore nella query\n");
                exit();
                }

                $row = mysqli_fetch_array($result);
                
                $newID_UT=$doc->createElement("ID_UT", $row['userID']);

                $newPref->appendChild($newID_UT);

                $root->appendChild($newPref);

                $doc->save("../PAGINE SITO/SCHEMI\gestionePref.xml");
                
                header("Location: ../PAGINE SITO/LAHOME.php");


    }   
    else{
        print("Inserire tutti i valori!");
    }
        
}

?>
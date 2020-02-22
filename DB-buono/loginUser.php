<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="createUser.css">
        
        <title>Log-in utente</title>
    </head>
    
    <body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">

                        <form class="form-horizontal login-form" method="post">
                                
                                <h1>Log-in</h1>
                                <p>Per favore riempire i campi per il login</p>

                                <div class="form-group relative">
                                <label for="username"><b>Username</b></label>
                                <input class="form-control input-lg" type="text" placeholder="Inserire Username" name="username" required>
                                </div>

                                <div class="form-group relative">
                                <label for="psw"><b>Password</b></label>
                                <input class="form-control input-lg" type="password" placeholder="Inserire Password" name="psw" required>
                                </div>

                                <div class="form-group">
                                <button class="btn btn-success btn-lg btn-block" type="submit" class="login" name="login">Log-in</button>
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
ini_set('display_errors', 1);
error_reporting(E_ALL);

include("./connection.php");

if (isset($_POST['login'])){          
  
    $usernameUtente = $_POST['username'];

    $sql = "SELECT *
            FROM $db_tab_utente 
            WHERE username = ('$usernameUtente')
		";
 

    if (!$result = mysqli_query($mysqliConnection, $sql)) {
            printf("Errore nella query\n");
        exit();
        }

    $row = mysqli_fetch_array($result);


    if (password_verify($_POST['psw'], $row['password'])){
        if($row['ban']==0){
            session_start();
            $_SESSION['ID']=$row['userID'];
            $_SESSION['username']=$_POST['username'];
            $_SESSION['reputazione']=$row['reputazione'];
            $_SESSION['ruolo']=$row['ruolo'];

            $data_Accesso=  date('Y-m-d H:i:s');
            $ID_UT=$row['userID'];

            $query ="   UPDATE $db_tab_utente 
                SET data_ultimo_acc = ('$data_Accesso')
                WHERE userID=('$ID_UT')
                ";
    
            if (!$result = mysqli_query($mysqliConnection, $query)) {
            printf("Errore nella query di aggiornamento ultimo accesso\n");
            exit();
            }
            
            header('Location: ../PAGINE SITO/LAHOME.php');   

        }
        else{
            echo "<p>Ci dispiace. Ma sei stato Bannato.</p>";
        }

    }
        else{
            echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script>
            
        window.alert('LOGIN NON ESEGUITO!')
    
            
        </script>";
        }  
    }

?>

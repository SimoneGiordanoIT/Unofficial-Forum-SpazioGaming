<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
    <link rel="stylesheet" href="form_login.css" type="text/css" />
        <title>Creazione database e popolazione</title>
    </head>
    
    <body>
    
    
        
       <?php			
        error_reporting(E_ALL &~E_NOTICE);

        $db_nome = "forumdb";
        
        $db_tab_utente = "UtentiDB";

        // esecuzione del tentativo di connessione
        $mysqliConnection = new mysqli("localhost", "root", "");

      
        
        
            $queryCreazioneDatabase = "CREATE DATABASE $db_nome";
        
            if ($resultQ = mysqli_query($mysqliConnection, $queryCreazioneDatabase)) {
                printf("Database creato ...\n");
            }
            else {
                printf("NO DB Creation!\n");
                exit();
            }
        
            // ok, adesso chiudiamo la connessione
            $mysqliConnection->close();

 
        
        $mysqliConnection = new mysqli("localhost", "root", "", $db_nome);

        if (mysqli_errno($mysqliConnection)) {
            printf("Connessione fallita. Errore: %s\n", mysqli_error($mysqliConnection));
            exit();
                }
                
            $sqlQuery = "CREATE TABLE if not exists $db_tab_utente (
                        userID int NOT NULL auto_increment, primary key(UserId), 
                        nome varchar(120) NOT NULL,
                        cognome varchar(120) NOT NULL,
                        reputazione float NOT NULL,
                        data_ultimo_acc datetime,
                        email varchar(120),
                        username varchar(120) NOT NULL,
                        password varchar(120) NOT NULL,
                        ruolo int NOT NULL,
                        ban boolean NOT NULL
                        )";
                          
                
            if($result = mysqli_query($mysqliConnection, $sqlQuery))
                print("Tabella Utenti creata con successo\n");
            else{
                printf("Errore in creazione Utenti\n");
                exit();
            }

            $hashedPW1 = '$2y$10$lb6e/NDwpn1vtQM2WX0HJ.jDh2D/CElEv/z1z/IsedPJj.XQcXMXa';
            
            $sql1 = "INSERT INTO $db_tab_utente
                (nome,cognome,reputazione,email,username,password,ruolo,ban)
                VALUES
                ('ADMIN','ADMIN','3','admin@example.com','ADMIN', '$hashedPW1','100','0')";
       

        if ($result = mysqli_query($mysqliConnection, $sql1))
            printf("popolamento ADMIN con successo in tabella utenti\n");
        else {
            printf("Errore nell'inserimento in tabella utenti di ADMIN\n");
            exit();
            } 

            $hashedPW2 = '$2y$10$mtlF6VHUr.ztnz6/LenQbuEftMu.I21EPcRmvcMAom9aVOJXub4S6';

            $sql2 = "INSERT INTO $db_tab_utente
                (nome,cognome,reputazione,email,username,password,ruolo,ban)
                VALUES
                ('MOD LOL','MOD LOL','3','MODLOL@example.com','MOD LOL','$hashedPW2','10','0')";
       

        if ($result = mysqli_query($mysqliConnection, $sql2))
            printf("popolamento MOD con successo in tabella utenti\n");
        else {
            printf("Errore nell'inserimento in tabella utenti di MOD\n");
            exit();
            } 

            $hashedPW3 = '$2y$10$VqBKaLSH4CrXctJc9fTdJOBx/rb/vT61AtdkozIi51rDmtubyqIK.';

            $sql3 = "INSERT INTO $db_tab_utente
                (nome,cognome,reputazione,email,username,password,ruolo,ban)
                VALUES
                ('deputyLOL','deputyLOL','3','deputyLOL@example.com','deputyLOL','$hashedPW3','15','0')";
       

        if ($result = mysqli_query($mysqliConnection, $sql3))
            printf("popolamento ADMIN con successo in tabella utenti\n");
        else {
            printf("Errore nell'inserimento in tabella utenti di ADMIN\n");
            exit();
            } 

            $hashedPW4='$2y$10$21pA7TlM3ZPnYh3Uz4Bcj.vawA/JDGnrJlqKcR2KHCYRJ08ay4e9m';

            $sql4 = "INSERT INTO $db_tab_utente
                (nome,cognome,reputazione,email,username,password,ruolo,ban)
                VALUES
                ('prova1','prova1','3','prova1@example.com','prova1','$hashedPW4','1','0')";
       

        if ($result = mysqli_query($mysqliConnection, $sql4))
            printf("popolamento Utente con successo in tabella utenti\n");
        else {
            printf("Errore nell'inserimento in tabella utenti di Utente\n");
            exit();
            } 
        
        
        mysqli_close($mysqliConnection);
            
        
       header("Location: ../PAGINE SITO/LAHOME.php"); 
            exit;
        ?>
 </body>
</html>       


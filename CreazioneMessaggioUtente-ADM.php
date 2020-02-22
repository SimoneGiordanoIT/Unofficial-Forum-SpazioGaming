<?php
/*Lo script di creazione dei messaggi tra utente e admin, gestisce i messaggi che l'utente invia all'admin, in relazione a segnalazione di problemi o richieste di creazione di nuove aree. A tale scopo, si verifica
se è settata la variabile di invio, se si, allora si accede al contenuto del file gestioneMessaggiCreaArea.xml, si verifica che la sessione è quella di un utente registrato oppure se quella di un ADMIN: nel primo caso
vengono inizializzate le varibili di ID utente con la sessione corernte, flag sender a 0 poiché il sendere é l'utente, il testo del messaggio e il flag di lettura a 1, nel secondo,
vengono inizializzate le varibili di ID utente con la variabile che mi viene passata, flag sender a 1 poiché il sender é l'ADMIN, il testo del messaggio e il flag di lettura a 1 . 
Successivamente verrà creata una nuova struttura con i campi il cui valore sarà quello delle variabili
precedentemente inizializzate; i nuovi campi verranno appesi nella struttura, verrà appesa la struttura e infine salvato il documento.
Nella parte inferiore dello script è presente la form per la creazione del messaggio da inviare all'admin */


error_reporting(0);


if (isset($_POST['invio'])){

    session_start();
	
	$xmlString = "";
	foreach ( file("PAGINE SITO/SCHEMI/gestioneMessaggiCreaArea.xml") as $node ) {
	$xmlString .= trim($node);
	}

	$doc = new DOMDocument();
	if (!$doc->loadXML($xmlString)) {
  	die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
	}

    $root = $doc->documentElement;
    
    $elementi = $root->childNodes;

    if($_SESSION['ruolo'] == 1){

        $ID_Utente = $_SESSION['ID'] ;
        $flag_sender = 0;
        $Testo_Mex = $_POST['testo'];
        $flag_lett= 1;}

        else if($_SESSION['ruolo'] == 100){

            $ID_Utente = $_POST['ID_UTENTE'] ;
            $flag_sender = 1;
            $Testo_Mex = $_POST['testo'];
            $flag_lett= 1;
        }
        

        // creazione di un nuovo <record>
        
        $newMex = $doc->createElement("messaggio");  /* crea una struttura messaggio vuota e crea i campi con i valori ( Da riga 32 a 35 ) */ 

        $newID = $doc->createElement("ID_UT",$ID_Utente);
        $newFlagSend = $doc->createElement("flag_sender", $flag_sender);
        $newTestoMess = $doc->createElement("testoMess", $Testo_Mex);
        $newFlagLett = $doc->createElement("flag_lettura", $flag_lett);
        


        $newMex->appendChild($newID);      /* inserisci i dati nella struttura messaggio (da rig 39 a 42) */ 
        $newMex->appendChild($newFlagSend);
        $newMex->appendChild($newTestoMess);
        $newMex->appendChild($newFlagLett);
        
        $root->appendChild($newMex);  /* aggiunge la struttura messaggio in elenco messaggi ( vedi xsd ) */

        $doc->save("PAGINE SITO/SCHEMI/gestioneMessaggiCreaArea.xml");

        

    }

    

?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<PHP>	<link rel="stylesheet" type="text/css" href="./DB-buono/createUser.css"> <PHP>
	<title>Aggiunta Messaggio</title>
</head>

<body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">
                
                        <form class="form-horizontal login-form" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                        
                        <h1>Sezione messaggi</h1>
                        <p>Come possiamo aiutarti?</p>
	
	

						<div class="form-group relative">
							<label for="description"><b>Description</b></label>
							<br>
							<textarea class="form-control input-desc" type="text" rows="9" cols="70" class="form-control" name="testo" required></textarea>
						</div>

						<div class="form-group">

                                <?php 
                                session_start();

                                    /* DEVO FAR IN MODO CHE VENGA PASSATO ANCHE L'ID Di UTENTE DI PHP*/

                                    if($_SESSION['ruolo']==100){

                                    

                                    $ID_UTENTE = $_POST['ID_UTENTE'];
                                    echo"<input type=\"hidden\" name=\"ID_UTENTE\" value=\"$ID_UTENTE\">";
                                    }
                                ?>

                                <button class="btn btn-success btn-lg btn-block" type="submit" name="invio">INVIA</button>
								<button class="btn btn-success btn-lg btn-block" type="reset">Reset</button>
								<button class="btn btn-success btn-lg btn-block" onclick="goBack()">Go back</button>
						</div>

						</form>

					</div>
				</div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    <script src="./DB-buono/gradient.js"></script>
    <script>
        function goBack() {
        window.history.back();
        }
    </script>
</body>

</html>
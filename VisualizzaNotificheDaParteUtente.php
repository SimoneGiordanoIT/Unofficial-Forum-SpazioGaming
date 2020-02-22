<?php

/*Lo script si occupa di gestire la visualizzazione delle notifiche dell'utente, relativamente a messaggi inviati da moderatore e utente. Nella parte superiore dello script si visualizzano le notifiche dei messaggi
inviati dal moderatore; pertanto si accede al contenuto del file gestioneMessaggiModeraPost.xml, e si scorrono i suoi elementi controllando che l'ID dell'utente contenuto nel file xml coincida con l'ID dell'utente 
passato dalla variabile di session. Se il controllo da esito positivo, allora si accederà ai successivi campi che verranno memorizzati in apposite variabili. Infine viene controllato che il flag di lettura 
del messaggio sia pari a 1, ovvero il messaggio non è stato ancora letto; se il controllo da esito positivo allora verrà stampato il messaggio in grassetto, altrimenti se il controllo da esito negativo allora il
testo del messaggio verrà stampato non in grassetto.
Nella seconda parte dello script si visualizzano le notifiche dei messaggi inviati dall'admin; pertanto si accede al contenuto del file gestioneMessaggiCreaArea.xml, si scorrono i suoi elementi controllando che 
l'ID dell utente contenuto nel file coincida con l'ID dell'utente passato dalla variabile di sessione. Se il controllo da esito positivo, si verifica che il flag_sender sia pari a 1 ovvero il messaggio è stato inviato
dall'admin; se la condizione è soddisfatta allora viene stampato il testo del messaggio, e verrà impostato il valore del flag_lettura a 0, per indicare che il messaggio è stato letto. Contrariamente ai messaggi
inviati dal moderatore, quelli inviati dall'admin vengono stampati non in grassetto e l'aggiornamento del flag di lettura avviene non appena l'utente accede all'area di notifiche, mentre nel caso dei messaggi del
moderatore l'aggiornamento avviene cliccando sopra il messaggio. */


    $xmlString = "";
    foreach ( file("SCHEMI/gestioneMessaggiModeraPost.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);


    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);
        

        $ID_Utente = $elemento->firstChild;
        $ID_UT = $ID_Utente->textContent;

        if( $ID_UT == $_SESSION['ID'] ){

            $ID_MOD = $ID_Utente->nextSibling;

            $ID_P = $ID_MOD->nextSibling;
            $ID_POST = $ID_P->textContent;

            $Text_M = $ID_P->nextSibling;
            $Testo_Messaggio = $Text_M->textContent;

            $Flag_Lettura = $Text_M->nextSibling->textContent;

            echo " <div class='card'>";

                if($Flag_Lettura == 1){

                    /* se il flag di lettura è a 1 il testo viene messo in grassetto, premuto il bottone la form rimanda al nuovo script che modifica il flag, passandoci ID POST e la posizione i del messaggio dentro
                    il file xml */

                    echo"<p><b>$Testo_Messaggio</b></p>

                
                    <form method=\"post\" action=\"../PassaggioDaVisualNotificheEVisualPostConModificaFlagLettura.php\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                    <input type=\"hidden\" name=\"posizione\" value=\"$i\">
                    <button class='btn btn-success btn-lg btn-like' >Vai al Post di riferimento</button>
                    </form>";



                }
                else{
                    echo"<p>$Testo_Messaggio</p>

                    <form method=\"get\" action=\"VISUALIZZAZIONE SINGOLO POST CON COMMENTI.php\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                    <button class='btn btn-success btn-lg btn-like' >Vai al Post di riferimento</button>
                    </form>";
                    
                }
                echo"</div>
                    <br>";
                          

        }
    }


    $xmlString2 = "";
    foreach ( file("SCHEMI/gestioneMessaggiCreaArea.xml") as $node ) {
      $xmlString2 .= trim($node);
    }


    $docXML = new DOMDocument();
    $docXML->loadXML($xmlString2);


    $rootXML = $docXML->documentElement;
    $elementiXML = $rootXML->childNodes;
    
    for ($j=0; $j<$elementiXML->length; $j++) {
      
      $elementoXML = $elementiXML->item($j);
        

        $ID_Utente2 = $elementoXML->firstChild;
        $ID_UT2 = $ID_Utente2->textContent;

        if($ID_UT2 == $_SESSION['ID']  ){

            $flag_sen = $ID_Utente2->nextSibling;
            $flag_sender = $flag_sen->textContent;


            
            if($flag_sender == 1){
                echo " <div class='card'>";

                $text_Messaggio=$flag_sen->nextSibling->textContent;

                echo"<p>$text_Messaggio</p>";


                $flag_lett = $elemento->lastChild->textContent;

                if($flag_lett == 1){
                    $docXML->getElementsByTagName('flag_lettura')->item($j)->nodeValue = '0';
                    $docXML->save("SCHEMI\gestioneMessaggiCreaArea.xml");
                }
                
                echo"</div>";
            }
            

        }
    }
    
?>
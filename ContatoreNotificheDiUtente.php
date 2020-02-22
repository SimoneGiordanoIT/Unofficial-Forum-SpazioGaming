<?php

/*Lo script si occupa di determinare il numero di notifiche non lette di un determinato utente loggato, e stamparle in una apposita div. Per farlo vengono inizialmente contati i messaggi non letti inviati dall'admin
e successivamente contati i messaggi non letti inviati dal moderatore. Per i messaggi inviati dall'admin, si accede agli elementi dell file gestioneMessaggiCreaArea.xml, per ogni elemento si preleva il campo ID utente
e si controlla che coincida con l'ID dell'utente contenuto nella variabile di sessione. Se il controllo da esito positivo, si accede al contenuto del campo flag_sender che indica se il messaggio è stato inviato da un admin
o dall'utente. Viene effettuato un controllo sul flag sender, se quindi il controllo da esito positivo, ovvero il campo è uguale a 1 e quindi il messaggio è stato inviato da un admin, si accede al campo di flag_lett 
che specifica se il messaggio è stato letto o meno; se il controllo da esito positivo, ovvero il messaggio non è stato letto, allora viene incrementato il valore del contatore che memorizza il numero di messaggi ancora
non letti. Successivamente vengono contati i messaggi non letti inviati dai moderatori all'utente. Il procedimento è analogo, fatta eccezione per il fatto che si accede al file gestioneMessaggiModeraPost.xml e non 
bisogna fare il controllo sul campo flag_Sender poichè assente. Una volta contate tutte le notifiche, vengono stampate in una apposita div.
Tipologia analoga nel caso l'utente utilizzatore é un ADMIN, ma, in questo caso, il file XML da controllare é solamente "gestioneMessaggiCreaArea" e si controlla chi é stato il sender del messaggio e se é stato letto  */

if($_SESSION['ruolo'] == 1 ){


    $xmlString = "";
    foreach ( file("SCHEMI/gestioneMessaggiCreaArea.xml") as $node ) { /* conta sui messaggi inviati dall admin e successivamente quelli inviati dai moderatori */
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $contatore = 0;

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);
        

        $ID_Utente = $elemento->firstChild;
        $ID_UT = $ID_Utente->textContent;

        if($ID_UT == $_SESSION['ID']  ){

            $flag_sen = $ID_Utente->nextSibling;
            $flag_sender = $flag_sen->textContent;

            if($flag_sender == 1){

                $flag_lett = $elemento->lastChild->textContent;

                if($flag_lett == 1){
                    $contatore = $contatore + 1;
                }

            }

        }
    }


    $xmlString2 = "";
    foreach ( file("SCHEMI/gestioneMessaggiModeraPost.xml") as $node2 ) {  /* il flag sender non c è poichè gli utenti non possono rispondere ai moderatori  */
      $xmlString2 .= trim($node2);
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

            $flag_lettura = $elementoXML->lastChild->textContent;
            

            if($flag_lettura == 1){

                $contatore = $contatore + 1;
                

            }

        }
    }
}

else if($_SESSION['ruolo']==100){
  $xmlString = "";
    foreach ( file("SCHEMI/gestioneMessaggiCreaArea.xml") as $node ) { /* conta sui messaggi inviati dall admin e successivamente quelli inviati dai moderatori */
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $contatore = 0;

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {

      $elemento = $elementi->item($i);
        

        $ID_Utente = $elemento->firstChild;
        $ID_UT = $ID_Utente->textContent;

            $flag_sen = $ID_Utente->nextSibling;
            $flag_sender = $flag_sen->textContent;

            if($flag_sender == 0){

                $flag_lett = $elemento->lastChild->textContent;

                if($flag_lett == 1){
                    $contatore = $contatore + 1;
                }

            }

        }



  }




/* nella card alla fine viene stampato il valore del contatore */
    echo"<div class='card'>
            <img class='rulesIMG' src='./IMMAGINI/notifiche.png'>
            <p class='desc'>Numero Notifiche NON LETTE: $contatore</p>  
        </div>";
?>
      
        

<?php
/*Lo script si occupa di ricavare il voto di un commento, calcolato come media pesata in cui si tiene conto del voto espresso dall'utente e della reputazione dell'utente che ha sottomesso il voto. A tal scopo
si accede al contenuto del file commenti.xml, e si inizializzano le variabili di appoggio per il calcolo della media pesata del voto al commento. Successivamente si scorrono gli elementi del file con i loro campi, e
si verifica inizialmente che ci sia corrispondenza tra l'ID del post contenuto nel file xml e l'ID del post di interesse. Se l'esito della verifica è positivo, allora si accede al campo ID commento e si controlla che
l'ID del commento nel file coincide con l'ID del commento di interesse; se il controllo da esito positivo, allora si prendono tutte le valutazioni di quel determinato commento; se il numero di votazioni è diverso da
zero, si recupera il voto al commento e l'ID dell'utente votante, mediante il quale si interrogherà il DB per prelevare la sua reputazione. Ottenute le informazioni necessarie si calcolerà il voto pesato 
(voto*reputazione utente) e il denominatore e il numeratore necessari per il calcolo della media pesata. Infine se il numero di commenti è nullo, il voto al commento sarà zero. */

    $xmlString2 = "";
    foreach ( file("SCHEMI/commenti.xml") as $node ) {
      $xmlString2 .= trim($node);
    }


    $docXML = new DOMDocument();
    $docXML->loadXML($xmlString2);


    $rootCOMM = $docXML->documentElement;
    $elementiCOMM = $rootCOMM->childNodes;

    $VOTO_COMM=0;

    $APPOGGIO_VOTAZIONE = 0;
    $APPOGGIO_REPUTAZIONE = 0;
    $NUMERO_VOTI = 0;
    
    for ($b=0; $b<$elementiCOMM->length; $b++) {
      
      $elementoCOMM = $elementiCOMM->item($b);
        
      $ID_Creatore=$elementoCOMM->firstChild;

      $ID_P = $ID_Creatore->nextSibling;
      $ID_POST_RIF = $ID_P->textContent;

      if($ID_POST_RIF==$ID_POST_SPECIFICO){
          $ID_C = $ID_P->nextSibling;
          $ID_COMM_RIF = $ID_C->textContent;


          if($ID_COMM_RIF==$ID_comm){

            $listaValutazioniCommenti = $elementoCOMM->getElementsByTagName("voto"); /* prende tutte le valutazioni di quel determinato commento */
            $numValutazioniCommenti = $listaValutazioniCommenti->length;

            

            if($numValutazioniCommenti != 0){

 
              for($s = 0;$s <$numValutazioniCommenti ; $s++){

                $valutazioneComm = $listaValutazioniCommenti->item($s); /* prende la valutazione indicata da item()*/ 
    
                $VOTO_COMM_RIF = $valutazioneComm->firstChild->textContent; 

                
                
                $ID_Votante_Commento=$valutazioneComm->lastChild->textContent;

                    $sql = "SELECT *
                  FROM $db_tab_utente 
                  WHERE userID = \"$ID_Votante_Commento\"
          ";

                if (!$result = mysqli_query($mysqliConnection, $sql)) {
                  printf("Errore nella query di ricerca reputazioni\n");
                exit();
                }
                
                $row = mysqli_fetch_array($result);

                $VOTO_PESATO= $row['reputazione'] * $VOTO_COMM_RIF;


                        $APPOGGIO_REPUTAZIONE= $APPOGGIO_REPUTAZIONE + $row['reputazione'] ;


                        $APPOGGIO_VOTAZIONE = $APPOGGIO_VOTAZIONE + $VOTO_PESATO;

                        
                        
                    }

                    $VOTO_COMM=$APPOGGIO_VOTAZIONE / $APPOGGIO_REPUTAZIONE;

                }
            
              else{
                  $VOTO_COMM=0;
              }




              }

            }
    
}
                
?>

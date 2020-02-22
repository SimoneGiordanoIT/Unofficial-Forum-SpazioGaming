<?php

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


        $flag_sen = $ID_Utente2->nextSibling;
        $flag_sender = $flag_sen->textContent;


        
        if($flag_sender == 0){
          echo " <div class='card'>";

            $text_Messaggio=$flag_sen->nextSibling->textContent;
          /* ID_UT2 è quello che viene passato in creazioneAree per nominare l'utente che ha fatto richiesta, moderatore */
            echo"<p>$text_Messaggio</p>

                      <form method='post' action='../creazioneAree.php'>
                      <input type='hidden' name='ID_UTENTE' value='$ID_UT2'>
                      <button class='btn btn-success btn-lg btn-block' type='submit' value='CREA AREA'>Crea quest'AREA</button>
                      </form>
                      
                      <form method='post' action='../CreazioneMessaggioUtente-ADM.php'>
                      <input type='hidden' name='ID_UTENTE' value='$ID_UT2'>
                      <button class='btn btn-success btn-lg btn-block' type='submit' value='RISPONDI'>Rispondi</button>
                      </form>

                      ";
            
            
            


            $flag_lett = $elementoXML->lastChild->textContent;

            if($flag_lett == 1){
                $docXML->getElementsByTagName('flag_lettura')->item($j)->nodeValue = '0'; /* se l'admin clicca sulla sezione notifiche della home allora nella pagina di visualizzazione verranno contrassegnate come 
                                                                                            lette. Se l'admin esce dal sito al rientro visualizzerà un numero di notifiche pari a zero */
                $docXML->save("SCHEMI/gestioneMessaggiCreaArea.xml");
            }
            echo"</div>";
            

        }
        

    
}
    
    ?>

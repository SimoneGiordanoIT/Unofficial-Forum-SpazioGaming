<?php

/*Lo script gestisce il flag di lettura per la visualizzazione dei messaggi. Per far ciò si accede al file gestioneMessaggiModeraPost.xml, e imposta a 0 il flag di lettura del messaggio in posizione i nel file,
a questo punto apre il singolo post, grazie all'ID passato in precedenza */

    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/gestioneMessaggiModeraPost.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $i = $_POST['posizione'];

    $ID_POST = $_POST['ID_POST'];
        

    $doc->getElementsByTagName('flag_lett')->item($i)->nodeValue = '0';  /* viene impostato il valore del flag di lettura a 0 quindi il mex diventa letto */
    $doc->save("PAGINE SITO/SCHEMI/gestioneMessaggiModeraPost.xml");

    header("Location:PAGINE SITO/VISUALIZZAZIONE SINGOLO POST CON COMMENTI.php?ID_POST=".$ID_POST);

?>
    
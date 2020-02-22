<?php

/*Lo script permette all'admin di rendere un'area invisibile. Per far ciò, inizialmente si memorizza in una apposita variabile la posizione dell'area di interesse da rendere invisibile; successivamene si accede al 
contenuto del file AreeInteresse.xml, e si pone a zero il flag_attivo dell'area interessata, rendendola così invisibile. Infine si salva il documento xml e si carica la pagina precedente a quella corrente.  */

    $Area_Da_Rendere_Invisibile = $_POST['posizione_Area'];

    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/AreeInteresse.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);


        $doc->getElementsByTagName('flag_attivo')->item($Area_Da_Rendere_Invisibile)->nodeValue = '0';  /* viene impostato il valore del flag di area a 0 quindi area diventa invisibile */
        $doc->save("PAGINE SITO/SCHEMI/AreeInteresse.xml");

        echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
    ?>
<?php

/*Lo script si occupa di rimuovere un like a un post a cui l'utente ha messo il like. A tal scopo, si accede al contenuto del file post.xml, e si memorizza in una apposita variabile la posizione del post necessaria
a individuare nel file, l'elemento desiderato. Una volta individuato quest'ultimo, si preleva la lista di tutte le valutazioni al post; si scorrono gli elementi della lista verificando per ognuno di essi che ci sia
una corrispondenza tra l'ID dell'utente loggato e l'ID memorizzato nel file. Se il controllo da esito positivo allora si imposta il flag_voto di quell'elemento a 0. Infine si salva il documento e si carica la
pagina precedente a quella corrente. */

session_start();
    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $posizione=$_POST['posizione'];

    $elemento = $elementi->item($posizione);

    $listaValutazioni = $elemento->getElementsByTagName("valutazionePost");
    $numValutazioni = $listaValutazioni->length;

    for ($j=0; $j<$numValutazioni; $j++){

        $valutazione = $listaValutazioni -> item($j);

        $ID_Vot = $valutazione->firstChild;
        $ID_Votante = $ID_Vot->textContent;

        if( $ID_Votante == $_SESSION['ID']){
            
            $flag_voto = $valutazione->lastChild;

            $elemento->getElementsByTagName('flag_voto')->item($j)->nodeValue='0';

        }

    }


    $doc->save("PAGINE SITO/SCHEMI/post.xml");
    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
?>

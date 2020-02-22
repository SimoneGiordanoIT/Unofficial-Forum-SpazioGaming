<?php

/*Lo script si occupa di riaggiungere un like a un post a cui l'utente ha tolto il like. A tal scopo, si accede al contenuto del file post.xml, e si memorizza in una apposita variabile la posizione del post, necessaria
a individuare nel file, l'elemento desiderato. Una volta individuato quest'ultimo, si preleva la lista di tutte le valutazioni al post; si scorrono gli elementi della lista verificando per ognuno di essi che ci sia
una corrispondenza tra l'ID dell'utente loggato e l'ID memorizzato nel file. Se il controllo da esito positivo allora si imposta il flag_voto di quell'elemento a 1. Infine si salva il documento e si carica la
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

            $listaValutazioni = $elemento->getElementsByTagName('valutazionePost');
            $numValutazioni = $listaValutazioni->length;

            for($i=0; $i<$numValutazioni; $i++){

                $valutazione = $listaValutazioni->item($i);
                $ID_votante = $valutazione->firstChild->textContent;
                
                if($ID_votante == $_SESSION['ID']){

                    $elemento->getElementsByTagName('flag_voto')->item($i)->nodeValue='1';
                }

            }
            $doc->save("PAGINE SITO/SCHEMI/post.xml");
    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
?>

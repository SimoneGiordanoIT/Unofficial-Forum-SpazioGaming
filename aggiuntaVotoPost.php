<?php

/*Lo script si occupa di aggiungere un like al post, tenendo traccia dell'utente che ha sottomesso il like. A tale scopo, si accede al contenuto del file post.xml contenente i post con i rispettivi campi, si trova il
post di riferimento, effettuando un controllo tra il valore della posizione passata tramite la variabile Post e il valore dell'indice i del ciclo for. Determinato il post di interesse, si percorrono i suoi campi
fino al campo vis, superato quest'ultimo si crea l'elemento 'valutazionePost' con i suoi campi: ID_votante che tiene memoria dell'ID dell'utente che ha votato, flag_voto posto a 1 che indica che l'utente ha votato.
Infine una volta creata la struttura dati e appesi i campi al suo interno, viene inserita prima della struttura dati dedicata ai commenti, in particolare viene trovato il primo commento e inserita la struttura prima
di esso. */ 

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

    for ($i=0; $i<$elementi->length; $i++) {
        if($i==$posizione){

            $elemento = $elementi->item($i);

            $ID = $elemento->firstChild;
            $tag = $ID->nextSibling;
            $ID_Creat=$tag->nextSibling;
            $DATA=$ID_Creat->nextSibling;
            $tit=$DATA->nextSibling;
            $text=$tit->nextSibling;
                
            $attachment = $text->nextSibling;
    
            $vis = $attachment->nextSibling;

            $newValutazionePost = $doc->createElement('valutazionePost');

            $New_IDVotante = $doc->createElement('ID_Votante',$_SESSION['ID']);
            $Newflag_voto = $doc->createElement('flag_voto', 1);

            $newValutazionePost->appendChild($New_IDVotante);
            $newValutazionePost->appendChild($Newflag_voto);

      
            $Primo_Commento = $elemento->getElementsByTagName("commento")->item(0);
            $valutazione = $elemento->insertBefore($newValutazionePost,$Primo_Commento);
            
        }
    }
    $doc->save("PAGINE SITO/SCHEMI/post.xml");
    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
?>

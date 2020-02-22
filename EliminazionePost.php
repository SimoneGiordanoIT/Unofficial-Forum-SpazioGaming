<?php

/*Lo script gestisce l'eliminazione di un post, ovvero rende il post invisibile. A tal scopo, si accede al contenuto del file post.xml, e si memorizza l'ID del post da eliminare in una apposita variabile; quindi si
scandiscono gli elementi del file xml, ricercando il post il cui ID coincide con quello del post ricercato da eliminare. Una volta trovato il post, verrà impostato il campo visibile a zero, di modo da renderlo 
invisibile. Infine viene salvato il file e caricata la pagina precedente a quella corrente. */

$xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $ID_POST_DA_ELIMINARE = $_POST['ID_POST'];

    for ($i=0; $i<$elementi->length; $i++) {
      
        $elemento = $elementi->item($i);

        $ID = $elemento->firstChild;
        $ID_Check=$ID->textContent;

        if($ID_Check==$ID_POST_DA_ELIMINARE){

            $doc->getElementsByTagName("visibile")->item($i)->nodeValue = '0';


        }
    }
    $doc->save("PAGINE SITO/SCHEMI/post.xml");
    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
?>

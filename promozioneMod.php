<?php

/*Lo script gestisce la promozione di un deputy a moderatore. A tal scopo, inizialmente si effettua la connessione al DB, e si aggiorna il ruolo dell'utente deputy promosso a moderatore, al valore 10. Successivamente
si accede al contenuto del file moderatoreAreaDeputy.xml e si scorrono i suoi elementi, prelevando i campi: ID moderatore, tag dell'area, la lista dei deputy di quell'area. Si scorrono tutti i deputy e se c'è
corrispondenza tra l'ID del deputy promosso e l'ID del deputy nella lista, e tra il tag dell'area e il tag area passato mediante post; se il controllo da esito positivo si aggiorna l'ID del moderatore a quello del deputy
e si rimuove dal file l'ID del deputy divenuto moderatore; infine viene degradato il precedente moderatore a utente registrato, effettuando una query di update in cui si pone il valore della variabile ruolo a 1.
Infine si salva il file e si carica la pagina precedente a quella corrente. */

session_start();


$ID_deputy = $_POST['ID_Deputy'];

include("./DB-buono/connection.php");

$query = "   UPDATE $db_tab_utente 
				SET ruolo=\"10\"
				WHERE userID=\"$ID_deputy\"
                ";
                
        if (!$result = mysqli_query($mysqliConnection, $query)) {
            printf("Errore nella query di salvataggio\n");
        exit();
        }

        

        $xmlString = "";
        foreach ( file("PAGINE SITO/SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
        $xmlString .= trim($node);
        }

        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        $root = $doc->documentElement;
        $elementi = $root->childNodes;

        for ($i=0; $i<$elementi->length; $i++) {

            $elemento = $elementi->item($i);

            $ID_M = $elemento->firstChild;
            $ID_MOD = $ID_M->textContent;

            $tagArea = $ID_M->nextSibling;
            $tagName = $tagArea->textContent;

            $listaDeputy = $elemento->getElementsByTagName("ID_DEP");
            $numDeputy = $listaDeputy->length;

            for($j = 0;$j <$numDeputy ; $j++){

                $deputy = $listaDeputy->item($j);
                $ID_DEP = $deputy->firstChild->textContent;

                if($ID_DEP == $ID_deputy && $tagName == $_POST['tagName']){

                    $doc->getElementsByTagName('ID_MOD')->item($j)->nodeValue = $ID_DEP;

                    $deputy->parentNode->removeChild($deputy);

                    $query = "UPDATE $db_tab_utente 
                              SET ruolo=\"1\"
                              WHERE userID=\"$ID_MOD\"
                                ";

                
                    if (!$result = mysqli_query($mysqliConnection, $query)) {
                        printf("Errore nella query di salvataggio\n");
                    exit();
                    }

                }

            }

        }
        
        $doc->save("PAGINE SITO/SCHEMI/moderatoreAreaDeputy.xml");

        echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script>
            
            window.history.back();
            
        </script>";

?>
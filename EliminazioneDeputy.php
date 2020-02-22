<?php

/*Lo script gestisce l'eliminazione di un deputy da parte di un moderatore. A tal scopo, si accede inizialmente al contenuto del file moderatoreAreaDeputy.xml, si scandiscono i suoi elementi prelevando l'ID del
moderatore e confrontandolo con l'ID memorizzato nella variabile di sessione. Se il controllo da esito positivo allora viene prelevata la lista dei deputy di quel moderatore, all'interno della lista viene ricercato
il deputy il cui ID coincide con quello memorizzato nella variabile di post, e viene eliminato. Infine viene effettuata la connesione al DB, e viene aggiornato il ruolo del deputy che è stato degradato. Viene salvato
il file xml e caricata la pagina precedente a quella corrente. */


session_start();
    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $valori = array();

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    for ($i=0; $i<$elementi->length; $i++) {
        
        $elemento = $elementi->item($i);

            $ID_M = $elemento->firstChild;
            $ID_MOD = $ID_M->textContent;

            if($ID_MOD == $_SESSION['ID']){
                $listaDeputy = $elemento->getElementsByTagName("ID_DEP");
                    $numDeputy = $listaDeputy->length;


                    for($j = 0;$j <$numDeputy ; $j++){

                        $deputy = $listaDeputy->item($j);

                        $ID_DEP = $deputy->firstChild->textContent;

                        if($ID_DEP == $_POST['ID_DEPUTY']){
                            $deputy->parentNode->removeChild($deputy);


                            $ID_DEP=$_POST['ID_DEPUTY'];

                            include("DB-buono/connection.php");

                            $query ="   UPDATE $db_tab_utente 
                                        SET ruolo=\"1\"
                                        WHERE userID=\"$ID_DEP\"
                                        ";
                            
                            if (!$result = mysqli_query($mysqliConnection, $query)) {
                                    printf("Errore nella query di salvataggio reputazione finale\n");
                                exit();
                                }
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

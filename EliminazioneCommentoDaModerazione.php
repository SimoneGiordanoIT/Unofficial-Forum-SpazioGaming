<?php

/*Lo script si occupa di eliminare il commento a opera di un moderatore. A tale scopo, inizialmente si effettua il collegamento al DB per aggiornare più avanti la reputazione dell'utente il cui commento verrà 
eliminato; si accede, poi, al file post.xml. Si memorizzano in apposite variabili l'ID del commento da eliminare, l'ID del commentatore e l'ID del post passati tramite variabili post. Successivamente 
si scandiscono gli elementi del file xml, prelevando l'ID dei post in analisi e verificando che coincidano con l'ID del post passato nella variabile post. Se il confronto darà esito positivo allora si accederà
alla lista dei commenti di quel post e si ricercherà il commento il cui id coincide con quello memorizzato nella variabile post (quello da eliminare); una volta trovato il commento questo verrà rimosso. Successivamente verrà effettuata
un'interrogazione al DB per prelevare la reputazione dell'utente creatore del commento e diminuirla di uno; quindi eseguire una query di update per aggiornare la reputazione al suo nuovo valore. Infine si scandisce
la lista di commenti al post, al fine di verificare la presenza o meno di commenti in risposta al commento eliminato; se presenti vengono rimossi. Al termine dell'operazione si salva il file xml e viene caricata la
pagina precedente a quella corrente. */

    include("DB-buono\connection.php");

    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $ID_COMMENTO_DA_ELIMINARE=$_POST['ID_COMMENTO'];

    $ID_COMMENTATORE = $_POST['ID_COMMENTATORE'];

    $ID_POST = $_POST['ID_POST'];

    for ($i=0; $i<$elementi->length; $i++) {
      
        $elemento = $elementi->item($i);

        $ID = $elemento->firstChild;
        $ID_Check=$ID->textContent;

        /*QUI CI PASSO L'ID DEL POST DI RIFERIMENTO*/
        
        if($ID_Check==$ID_POST){

            $listaCommenti = $elemento->getElementsByTagName("commento");
            $numCommenti = $listaCommenti->length;

            for($j=0;$j<$numCommenti;$j++){

                $commento=$listaCommenti->item($j);

                $ID_commento = $commento->firstChild->textContent;


                    if($ID_commento==$ID_COMMENTO_DA_ELIMINARE){

                        $commento->parentNode->removeChild($commento);

                        $sql = "SELECT *
                        FROM $db_tab_utente 
                        WHERE userID = \"$ID_COMMENTATORE\"
                    ";

                        if (!$result = mysqli_query($mysqliConnection, $sql)) {
                            printf("Errore nella query di ricerca reputazioni\n");
                        exit();
                        }
                        
                        $row = mysqli_fetch_array($result);

                        $Nuova_Reputazione= $row['reputazione'] - 1;

                        $query ="   UPDATE $db_tab_utente 
                        SET reputazione=('$Nuova_Reputazione')
                        WHERE userID=('$ID_COMMENTATORE')
                        ";
            
            if (!$result = mysqli_query($mysqliConnection, $query)) {
                    printf("Errore nella query di salvataggio reputazione finale\n");
                exit();
                }



                        for($k=0;$k<$numCommenti;$k++){
                            $risposta = $listaCommenti->item($k);
                            if(!empty($risposta)){
                                if ($risposta->getElementsByTagName('ID_com_superiore')->length!=0){
                                    $ID_com_sup = $risposta->lastChild->textContent;
            
                                    if($ID_com_sup == $ID_commento){
                                        $risposta->parentNode->removeChild($risposta);
                                    }
                            }

                            }

                    }

                    $doc->save("PAGINE SITO/SCHEMI/post.xml");
                    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
                    }

                
            }

        }

    }

?>
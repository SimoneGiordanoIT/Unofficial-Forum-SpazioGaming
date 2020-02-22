<?php

/*Lo script gestisce l'eliminazione di un'area di interesse dai propri preferiti. Per far ciò, si accede al contenuto del file gestionePref.xml, si scandiscono i suoi elementi prelevando da questi il contenuto dell'
id dell'utente e controllando che tale ID corrisponda all'ID dell'utente memorizzato nella variabile di sessione; se il controllo da esito positivo allora si preleva la lista degli ID delle aree preferite, e si
scandiscono prelevando di volta in volta l'ID dell'area e verificando che coincida con l'ID dell'area passato tramite la variabile GET. Se il controllo da esito positivo allora viene rimosso l'ID dall'insieme dei
preferiti, viene salvato il file xml e si viene rimandati alla pagina personale aggiornata.*/

session_start();
    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/gestionePref.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);



    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    for ($i=0; $i<$elementi->length; $i++) {
      
        $elemento = $elementi->item($i);
  
        $ID = $elemento->firstChild;
        $ID_Check_Utente=$ID->textContent;
  
        if($ID_Check_Utente== $_SESSION['ID']){

            $listaPreferiti = $elemento->getElementsByTagName("ID_Area");
            $numPreferiti = $listaPreferiti->length;

            for($j=0; $j<$numPreferiti; $j++ ){

                $preferito= $listaPreferiti->item($j);
                $ID_prefCheck = $preferito->textContent;

                if($ID_prefCheck == $_GET['ID_AREA']){
                    $preferito->parentNode->removeChild($preferito);
                }
            }
        }
    }
    $doc->save("PAGINE SITO/SCHEMI/gestionePref.xml");
    header("Location: PAGINE SITO/PAGINA PERSONALE.php");
    

?>
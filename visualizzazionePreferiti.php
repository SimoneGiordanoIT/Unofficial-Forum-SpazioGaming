<?php

/*Lo script gestisce la visualizzazione delle aree preferite dell'utente loggato. A tal scopo, inizialmente si verifica che sia settata la variabile di sessione, se si, si accede al contenuto del file gestionePref.xml
Successivamente si scorrono i suoi elementi e si verifica la corrispondenza tra l'ID dell'utente memorizzato nel file xml e l'ID dell'utente memorizzato nella variabile sessione. Quindi una volta trovato l'utente 
di interesse si preleva la lista degli ID delle sue aree preferite, e si scorrono i suoi elementi; per visualizzare correttamente le aree preferite,
si accede al contenuto del file AreeInteresse.xml e si scorrono i suoi elementi, verificando che ci sia corrispondenza
tra l'area di interesse preferita e quella presente nel file AreeInteresse.xml. Se il confronto da esito positivo, allora si prelevano i campi di quell'area di interesse, quindi nome, tag e il flag attivo. Infine se
il flag attivo è pari a 1, quindi l'area è attiva, viene stampato il menù cliccabile contenente l'area di interesse preferita, é possibile rimuovere un area dai preferiti, cliccando sul pulsante apposito
che invierá allo script di eliminazione l'ID dell'area da eliminare. Se non è settata la variabile di sessione allora verrà stampato il bottone
per la registrazione al sito per vedere i propri preferiti. */

if(isset($_SESSION['ruolo'])){
    $xmlString = "";
    foreach ( file("SCHEMI/gestionePref.xml") as $node ) {
      $xmlString .= trim($node);
    }

    /*QUI CI SARA SEMPRE LA SESSION CHE SERVIRà A CAPIRE DI CHI DOBBIAMO VEDERE I PREFERITI*/

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($p=0; $p<$elementi->length; $p++) {
      
      $elemento = $elementi->item($p);

        $ID_UTENTE = $elemento->firstChild;
        $ID_UT=$ID_UTENTE->textContent;

        if($ID_UT==$_SESSION['ID']){

            $listaAree = $elemento->getElementsByTagName("ID_Area");
            $numAree = $listaAree->length;

            for($j=0;$j<$numAree;$j++){

                    $area=$listaAree->item($j);
                    $ID_AREA_Pref=$area->firstChild->textContent;

                    $xmlStringAREA = "";
                    foreach ( file("SCHEMI/AreeInteresse.xml") as $node2 ) {
                    $xmlStringAREA .= trim($node2);
                    }


                    $docXML = new DOMDocument();
                    $docXML->loadXML($xmlStringAREA);

                    $rootAREA = $docXML->documentElement;
                    $elementiAREA = $rootAREA->childNodes;
                    
                    for ($t=0; $t<$elementiAREA->length; $t++) {
                    
                    $elementoAREA = $elementiAREA->item($t);

                        $ID = $elementoAREA->firstChild;
                        $ID_Area=$ID->textContent;

                        if($ID_Area==$ID_AREA_Pref){

                            $nome = $ID->nextSibling;
                            $nome_Area = $nome->textContent;
                    
                            $tag = $nome->nextSibling;
                            $tag_Area = $tag->textContent;
                    
                            $flag = $tag->nextSibling;
                            $flag_Attivo = $flag->textContent;
                            

                            if($flag_Attivo == 1){

                                echo "
                                        
                                        
                                        <div class='vertical_menu'>
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tag_Area\">
                                            <button type=\"submit\" value=\"VAI\"> NOME GIOCO: \"$nome_Area\" con TAG: \"$tag_Area\"</button> 
                                            </form>
                                            <form method=\"get\" action=\"../EliminazioneAreaPreferiti.php\">
                                            <input type=\"hidden\" name=\"ID_AREA\" value=\"$ID_Area\">
                                            <button type=\"submit\" value=\"rimuovi\">Rimuovi area con tag: $tag_Area</button>  
                                            </form>
                                        </div>
                                 
                                    ";


                            	}

                        }

                    
                        
                    }

        }
      
    }
    }

}
else{
    echo "    
                <div class='vertical_menu'>
                    <form method=\"get\" action=\"../DB-buono/createUser.php\">
                    <button type \"submit\" >REGISTRATI PER VISUALIZZARE I PREFERITI</button>
                </div>
            
            ";

}
    
    
?>
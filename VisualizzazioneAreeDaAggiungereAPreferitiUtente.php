<?php


/*Lo script si occupa di stampare un menù cliccabile i cui elementi sono le aree di interesse che l'utente può aggiungere ai suoi preferiti. A tal scopo, si accede al contenuto del file AreeInteresse.xml e si 
scorrono di volta in volta i suoi elementi analizzando i rispettivi campi e memorizzandone il contenuto in apposite variabili. Successivamente si verifica che l'area è attiva; se il controllo da esito positivo
allora si stampa l'elemento del menù contenente il nome dell'area di interesse che si può aggiungere ai preferiti. */

    $xmlString = "";
    foreach ( file("SCHEMI/AreeInteresse.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);

        $ID = $elemento->firstChild;
        $ID_Area=$ID->textContent;
      
        $nome = $ID->nextSibling;
        $nome_Area = $nome->textContent;
  
        $tag = $nome->nextSibling;
        $tag_Area = $tag->textContent;
  
        $flag = $tag->nextSibling;
        $flag_Attivo = $flag->textContent;
        

        if($flag_Attivo == 1){

            echo "
                                        
                                        
            <div class='vertical_menu'>
                <form method=\"post\" action=\"../AggiuntaAreaNeiPreferitiUtente.php\">
                <input type=\"hidden\" name=\"ID_Area\" value=\"$ID_Area\">
                <button type \"submit\" value=\"VAI\"> AGGIUNGI AI TUOI PREFERITI: \"$nome_Area\" </button>
                </form>
            </div>
     
        ";

                


        }
    }
    
    ?>
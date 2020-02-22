<?php

    $xmlString = "";
    foreach ( file("SCHEMI/gestioneMessaggiModeraPost.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);

        $ID_U = $elemento->firstChild;
        $ID_UT=$ID_U->textContent;
      
        $ID_M = $ID_U->nextSibling;
        $ID_MOD = $ID_M->textContent;
  
        $ID_P = $ID_M->nextSibling;
        $ID_POST = $ID_P->textContent;
  
        $testo = $ID_P->nextSibling;
        $testo_mess = $testo->textContent;

        $flag_l = $testo->nextSibling;
        $flag_lettura = $flag_l->textContent;
        

        if($flag_lettura == 1 ){
          /* Se il messaggio non è stato ancora letto attraverso questo controllo if($flag_lettura == 1)  allora il font del messaggio
          sarà scritto in grassetto
          Servirà anche una connessione al db per trovare il nome utente associato a quel ID utente */


            print " <tr>
                    <th>NOME GIOCO: </th>
                        <td>$nome_Area</td>
                </tr>

                <tr>
                    <th>TAG: </th>
                        <td>$tag_Area</td>
                </tr>";

                echo"<form method=\"get\" action=\"VisualizzazionePostDiAreaDeterminata.php\">
                <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tag_Area\">
                <input type=\"submit\" value=\"VAI\">
                </form>";


        }
    }
    
    ?>

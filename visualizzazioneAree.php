<?php

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

            print " <tr>
                    <th>NOME GIOCO: </th>
                        <td>$nome_Area</td>
                </tr>

                <tr>
                    <th>TAG: </th>
                        <td>$tag_Area</td>
                </tr>";

                echo"
                <form method=\"get\" action=\"VisualizzazionePostDiAreaDeterminata.php\">
                <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tag_Area\">
                <input type=\"submit\" value=\"VAI\">
                </form>";


        }
    }
    
    ?>

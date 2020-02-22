<?php

    $xmlString = "";
    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
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

                echo"<hr>";

                for($j = 0;$j <$numDeputy ; $j++){

                    $deputy = $listaDeputy->item($j);
                    $ID_deputy = $deputy->textContent;

                    include("../DB-buono/connection.php");
        
                    $sql = "SELECT *
                    FROM $db_tab_utente 
                    WHERE userID = \"$ID_deputy\"
                    ";
        
                    if (!$result = mysqli_query($mysqliConnection, $sql)) {
                        printf("Errore nella query di ricerca reputazioni\n");
                    exit();
                    }
                    
                $row = mysqli_fetch_array($result);

                $usernameUtente= $row['username'];



                    echo"<br><div class='vertical_menu'>
                                            <form method=\"post\" action=\"../EliminazioneDeputy.php\">
                                            <input type=\"hidden\" name=\"ID_DEPUTY\" value=\"$ID_deputy\">
                                            <button type \"submit\" value=\"VAI\"> ELIMINA DAI DEPUTY $usernameUtente</button>
                                            </form>
                                        </div>
                                 
                                    ";

                    
                    
                }
        }
    }
?>
      
       
<?php

/*Lo script gestisce la stampa di un menù cliccabile i cui elementi sono aree di interesse il cui moderatore è inattivo da 14 giorni, o aree di interesse qualsiasi che l'admin può decidere di rendere invisibili.
A tal scopo si effettua inizialmente la connessione al DB e si accede al contenuto del file AreeInteresse.xml; si scorrono di volta in volta gli elementi con i loro campi che verranno memorizzati in apposite variabili.
Si effettua il controllo che l'area sia attiva, verificando che il flag attivo sia pari a 1; se il controllo da esito positivo si accede al contenuto del file moderatoreAreaDeputy.xml. Successivamente si scorrono
gli elementi di tale file e si prelevano i campi necessari per essere memorizzati in apposite variabile; si verifica che il tag dell'area di interesse precedentemente salvato coincida con il tag dell'area memorizzato
nel file xml. Se la condizione è soddisfatta allora si interroga al DB prelevando la data di ultimo accesso del moderatore assegnato a quell'area; si calcola la data odierna, e la differenza in giorni tra la data di
ultimo accesso e la data corrente. Se il numero di giorni supera 14 allora verrà stampato nel menù l'area senza moderatore attivo; altrimenti verrà stampato il messaggio per rendere semplicemente invisibile l'area
pur avendo moderatore attivo. */


include("../DB-buono/connection.php");

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

            $xmlString2 = "";
            foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
            $xmlString2 .= trim($node);
            }


            $docXML = new DOMDocument();
            $docXML->loadXML($xmlString2);

            $rootXML = $docXML->documentElement;
            $elementiXML = $rootXML->childNodes;

            for($j=0; $j<$elementiXML->length; $j++){

                $elementoXML = $elementiXML->item($j);

                $ID_MODERATORE = $elementoXML->firstChild;
                $ID_MOD= $ID_MODERATORE->textContent;

                $TAG_AREA_DI_MODERATORE = $ID_MODERATORE->nextSibling;
                $TAG_AREA_DI_MOD = $TAG_AREA_DI_MODERATORE->textContent;


                if( $TAG_AREA_DI_MOD == $tag_Area ){

                    $sql = "SELECT *
                        FROM $db_tab_utente 
                        WHERE userID = \"$ID_MOD\"
                    ";

                        if (!$result = mysqli_query($mysqliConnection, $sql)) {
                            printf("Errore nella query di ricerca accesso moderatore\n");
                        exit();
                        }
                        
                    $row = mysqli_fetch_array($result);

                    $UltimoAccessoMOD= $row['data_ultimo_acc'];

                    $dataOdierna = date("Y-m-d");

                    $diff = strtotime($dataOdierna) - strtotime($UltimoAccessoMOD);

                    $differenzaInGiorni = $diff /3600/24;

                    
                    if($differenzaInGiorni > 14){

                        echo"

                        <div class='menu_da_togliere'>
                                            <form method=\"post\" action=\"../rendeInvisibileAreaDaAdmin.php\">
                                            <input type=\"hidden\" name=\"posizione_Area\" value=\"$i\">
                                            <button type \"submit\" value=\"VAI\"> Area di \"$nome_Area\" NON &egrave coperta da MODERATORE</button>
                                            </form>
                                        </div>
                                    ";


                    }
                    else{
                        echo"

                        <div class='vertical_menu'>
                                            <form method=\"post\" action=\"../rendeInvisibileAreaDaAdmin.php\">
                                            <input type=\"hidden\" name=\"posizione_Area\" value=\"$i\">
                                            <button type \"submit\" value=\"VAI\"> Rendi \"$nome_Area\" semplicemente INVISIBILE</button>
                                            </form>
                                        </div>
                                    ";

                    }


                }
                else{
                    echo"

                    <div class='menu_da_togliere'>
                    <form method=\"get\" action=\"LAHOME.php\">
                    <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tag_Area\">
                    <button type \"submit\" value=\"VAI\"> Per area di \"$nome_Area\" NON &egrave ancora stato scelto il MODERATORE</button>
                    </form>
                </div>
            ";


                   

                    

                }
                

                    



            }

            


        }
        else{
            echo"

                        <div class='vertical_menu'>
                                            <form method=\"post\" action=\"../rendeVisibileAreaDaAdmin.php\">
                                            <input type=\"hidden\" name=\"posizione_Area\" value=\"$i\">
                                            <button type \"submit\" value=\"VAI\"> Rendi \"$nome_Area\" di nuovo VISIBILE</button>
                                            </form>
                                        </div>
                                    ";


        }
    }
    
    ?>

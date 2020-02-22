<?php

/*Lo script si occupa di stampare l'elenco dei deputy presenti sul sito, candidati a essere promossi a moderatori dell'area di cui sono deputy. Per far ciò si effettua la connessione al DB, e si accede al contenuto 
del file moderatoreAreaDeputy.xml. Successivamente si scandiscono i suoi elementi, memorizzando il tag dell'area di interesse e la lista dei suoi deputy; per ogni deputy si interrogherà il DB per prelevare il nome
dei deputy. Infine verranno stampati in un menù cliccabile tutti i nomi dei deputy con le loro rispettive aree, disponibili a essere promossi a deputy*/

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    include("../DB-buono/connection.php");

    $xmlString = "";
    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    for ($i=0; $i<$elementi->length; $i++) {
        
    $elemento = $elementi->item($i);

    $ID_M = $elemento->firstChild;

    $tagArea = $ID_M->nextSibling;
    $tagName = $tagArea->textContent;

    $listaDeputy = $elemento->getElementsByTagName("ID_DEP");
    $numDeputy = $listaDeputy->length;

    for($j = 0;$j <$numDeputy ; $j++){
        
        $deputy = $listaDeputy->item($j);

        $ID_Deputy = $deputy->firstChild->textContent;

        $sql = "SELECT * 
        FROM $db_tab_utente
        WHERE userID = \"$ID_Deputy\";
        ";

        if (!$result = mysqli_query($mysqliConnection, $sql)) {
            printf("Errore nella query di ricerca reputazioni\n");
        exit();
        }
        $row = mysqli_fetch_array($result);
        $nomeDeputy = $row['nome'];

        echo"
        <div class='vertical_menu'>
            <form method=\"post\" action=\"../promozioneMod.php\">
            <input type=\"hidden\" name=\"ID_Deputy\" value=\"$ID_Deputy\">
            <input type=\"hidden\" name=\"tagName\" value=\"$tagName\">
            <button type \"submit\" value=\"VAI\"> Promuovi a moderatore il deputy: $nomeDeputy  in area: $tagName </button>
            </form>
        </div>
        ";
    }
}



?>
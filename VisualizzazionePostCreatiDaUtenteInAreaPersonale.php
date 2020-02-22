<?php


/*Lo script si occupa di stampare post creati dall'utente nella sua area personale, presentando i post in ordine decrescente per like. A tal scopo,si accede al contenuto del file post.xml, e si scorrono tutti i suoi elementi; in particolare
per ogni elemento, post, si scorreranno i suoi campi fino ad arrivare al campo di valutazione (like). Giunti al campo valutazione, si preleva la lista di tutte le valutazioni al post, e si calcola il numero di 
valutazioni/like che il post ha ricevuto; calcolati il numero di like si memorizza il valore dei like in un array associativo in cui in ogni posizione dell'array vi è il numero di like del post di riferimento. Si 
applica l'ordinamento di arsort sull'array, in modo da ordinare gli elementi dell'array in ordine decrescente per numero di like. Il passo successivo è prelevare tutti i campi di tutti i post, e memorizzare i valori
internamente alle apposite variabili. Poichè nell'array associativo abbiamo che la posizione dell'elemento (post) nel file xml coincide con l'ID del post, e viceversa, il passo successivo sarà scorrere gli elementi
dell'array associativo; mediante il campo posizione accederemo di volta in volta alla posizione del post internamente al file xml, e preleveremo i suoi campi. Una volta verificato che il campo visibile del post è 
pari a 1 (ovvero il post è visibile) allora verrà stampato il post con i suoi campi. Infine vengono stampati i bottoni per la modifica e l'eliminazione del post. */


    if(isset($_SESSION['ID'])){
        $xmlString = "";
    foreach ( file("SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $valori = array();

    $root = $doc->documentElement;
    $elementi = $root->childNodes;
    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);
        

        $ID = $elemento->firstChild;
      
        $tag = $ID->nextSibling;
  
        $ID_C = $tag->nextSibling;
  
        $dataCre = $ID_C->nextSibling;
  
        $titolo = $dataCre->nextSibling;
  
        $testo = $titolo->nextSibling;

            
        $attachment = $testo->nextSibling;

        $vis = $attachment->nextSibling;
        
        $valutazioneP = 0;
  
        $listaValutazioni = $elemento->getElementsByTagName("valutazionePost"); /* prende tutte le valutazioni di quel determinato post */
        $numValutazioni = $listaValutazioni->length;
 
        for($j = 0;$j <$numValutazioni ; $j++){  /* calcolo della votazione del post dato dalla somma tra valutazioni passate ( salvate in valutazioneP) e la valutazione successiva (flag_voto) */

            $valutazione = $listaValutazioni->item($j);

            $flag_voto = $valutazione->lastChild->textContent;

            $valutazioneP = $valutazioneP + $flag_voto;
        }

        $valori[$i]=$valutazioneP;

        

        arsort($valori);}

        $P_ID=$doc->getElementsByTagName( "ID" );  
        $P_ID_Creat=$doc->getElementsByTagName( "ID_Creat" );  
        $P_Title=  $doc->getElementsByTagName( "Titolo" );        
        $P_Text= $doc->getElementsByTagName( "Testo" );
        $P_Tag= $doc->getElementsByTagName( "tag" );
        $P_Data=$doc->getElementsByTagName( "Data_creaz" );
        $P_val=$doc->getElementsByTagName( "valutazionePost" );
        $P_visibile=$doc->getElementsByTagName( "visibile" );
        $P_atta=$doc->getElementsByTagName( "Attachment" );

        foreach($valori as $posizione => $valutazione) {

            $ID_POST=$P_ID->item($posizione)->nodeValue;   /* preleva l'ID dall'elemento indicato da posizione ( stesso per i successivi campi fino a riga 83) */
            $ID_CREATORE=$P_ID_Creat->item($posizione)->nodeValue;
            $title=$P_Title->item($posizione)->nodeValue;

            $titleHTML = htmlentities($title);

            $text=$P_Text->item($posizione)->nodeValue;

            $textHTML= htmlentities($text);

            $att=$P_atta->item($posizione)->nodeValue;
            $tagName=$P_Tag->item($posizione)->nodeValue;
            $data=$P_Data->item($posizione)->nodeValue;
            $visibile=$P_visibile->item($posizione)->nodeValue;

            if($_SESSION['ID']==$ID_CREATORE){


            if($visibile==1){
                
                echo " <div class='card'>
                          
                    <a class='link_post' title='Link al post' href='VISUALIZZAZIONE SINGOLO POST CON COMMENTI.php?ID_POST=".$ID_POST."'><h1 class='titolo'>$titleHTML</h1></a> 

                        <h2 class='title_descrip'>TAG: $tagName </h2>
                        <h3>Sottomesso in data: $data </h3>
                        <p class='text'>$textHTML</p>
                        <h3>Like: $valutazione</h3>
                    ";

            if ($att != '0') {

                $nomeImmagine = $tagName . $ID_POST;

                echo "<img src='ATTACHMENT/$nomeImmagine.png' />";
            
                    }
    
                        echo"<form method=\"post\" action=\"../ModificaPost.php\">
                        <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                        <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"MODIFICA\">MODIFICA TESTO POST</button>
                        </form>";

                        echo"<form method=\"post\" action=\"../EliminazionePost.php\">
                        <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                        <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"ELIMINA\">ELIMINA POST</button>
                        </form>
                        </div>";
    
    
                }

            }
    }        
}
    

     
    
    
?>
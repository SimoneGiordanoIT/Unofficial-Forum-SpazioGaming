<?php

/*Lo script si occupa di ricercare e stampare post e commenti, in base al tag inserito dall'utente nella barra di ricerca. A tal scopo, inizialmente si verifica se è settata la variabile add, se si, allora si 
memorizza il tag ricercato passato mediante variabile post, e si converte il tag cercato in minuscolo. Successivamente si accede al contenuto del file post.xml, e si scorrono tutti i suoi elementi; in particolare
per ogni elemento, post, si scorreranno i suoi campi fino ad arrivare al campo di valutazione (like). Giunti al campo valutazione, si preleva la lista di tutte le valutazioni al post, e si calcola il numero di 
valutazioni/like che il post ha ricevuto; calcolati il numero di like si memorizza il valore dei like in un array associativo in cui in ogni posizione dell'array vi è il numero di like del post di riferimento. Si 
applica l'ordinamento di arsort sull'array, in modo da ordinare gli elementi dell'array in ordine decrescente per numero di like. Il passo successivo è prelevare tutti i campi di tutti i post, e memorizzare i valori
internamente alle apposite variabili. Poichè nell'array associativo abbiamo che la posizione dell'elemento (post) nel file xml coincide con l'ID del post, e viceversa, il passo successivo sarà scorrere gli elementi
dell'array associativo; mediante il campo posizione accederemo di volta in volta alla posizione del post internamente al file xml, e preleveremo i suoi campi. Una volta verificato che il campo visibile del post è 
pari a 1 (ovvero il post è visibile) allora verrà stampato il post con i suoi campi. Successivamente per quel post verrà prelevata la lista di valutazioni e, verificato che l'utente è loggato e che il numero di 
valutazioni è diverso da zero, si controlla per ogni valutazione che ci sia corrispondenza tra l'ID dell'utente votante, e l'ID dell'utente passato dalla session. Se il controllo da esito positivo allora significa
che l'utente aveva già votato e quindi verrà stampato il bottone di non mi piace; se il controllo da esito negativo allora significa che l'utente ancora deve votare e viene stampato il bottone di mi piace. Infine
se il numero di valutazioni è nullo, allora viene stampato ugualmente il bottone di mi piace; se inoltre l'utente non è loggato allora viene stampato il bottone di login per votare.
Per la stampa dei commenti, si accede al file post.xml e si scorrono i suoi elementi; per ogni post preso in analisi prelevo la lista dei suoi commenti. Successivamente si scorre la lista dei commenti e si prelevano
i campi di interesse per la stampa del commento ricercato, e la lista di tutti i suoi tag; ottenuti tutti i tag del commento, si verifica per ognuno che ci sia corrispondenza tra il tag del commento e il tag ricercato.
Se il confronto da esito positivo allora stampo il commento con un un pulsante che reindirizza al post di riferimento del commento. */


?>



<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<title>RICERCA</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>

    <section id="contact">
        
        <div class="container">

            <form action="<?php $_SERVER['PHP_SELF']?>" method="post">

                <input type="text" placeholder="TAG" name="tag"><br>

                <button class='btn btn-success btn-lg btn-block' type="submit" value="aggiungi" name="add" >Cerca</button>

            </form>
		</div>
</body>

</html>

<?php

if (isset($_POST['add'])){

    $tagDaRicercare = $_POST['tag'];

    $tag_Ricercato=strtolower($tagDaRicercare);

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

            $ID_POST=$P_ID->item($posizione)->nodeValue;
            $ID_CREATORE=$P_ID_Creat->item($posizione)->nodeValue;
            $title=$P_Title->item($posizione)->nodeValue;
            $text=$P_Text->item($posizione)->nodeValue;
            $att=$P_atta->item($posizione)->nodeValue;
            $tagName=$P_Tag->item($posizione)->nodeValue;
            $data=$P_Data->item($posizione)->nodeValue;
            $visibile=$P_visibile->item($posizione)->nodeValue;


            if($visibile==1){
                $nomeAreaDaCercare = strtolower($tagName);
                if($tag_Ricercato==$nomeAreaDaCercare){
                    echo " <div class='card'>
                          
                <a class='link_post' title='Link al post' href='VISUALIZZAZIONE SINGOLO POST CON COMMENTI.php?ID_POST=".$ID_POST."'><h1 class='titolo'>$title</h1></a> 

                    <h2 class='title_descrip'>TAG: $tagName </h2>
                    <h3>Sottomesso in data: $data </h3>
                    <p class='text'>$text</p>
                    <h3>Like: $valutazione</h3>
                ";

        if ($att != '0') {
           

            $nomeImmagine = $tagName . $ID_POST;

            echo "<img src='ATTACHMENT/$nomeImmagine.png' />";
        
                }

                $elemento = $elementi->item($posizione); /* prende il post indicato da item($posizione) */
                    
                    $listaValutazioni = $elemento->getElementsByTagName("valutazionePost"); /* prende tutte le valutazioni di quel post e ne calcola la lunghezza   */
                    $numValutazioni = $listaValutazioni->length;

                    if(isset($_SESSION['ruolo'])){ /* verifica se l'utente è loggato */


                    if($numValutazioni != 0){ /* verifica se ci sono valutazioni al post */

                        for($j = 0;$j <$numValutazioni ; $j++){
                            
    
                            $valutazione = $listaValutazioni->item($j); /* prende la valutazione indicata da item()*/ 
    
                            $ID_Votante = $valutazione->firstChild->textContent; /* preleva l'id del votante su cui fare il controllo dell'if(riga 129) */
                            
                            
                            if($ID_Votante == $_SESSION['ID']){
                                
    
                                $flag_voto = $valutazione->lastChild->textContent; /* preleva il flag voto, se è 1 allora l'utente non potrà rivotare ma solo togliere il like al commento (viceversa) */
    
                                if($flag_voto == 1 ){
                                    echo"<form method=\"post\" action=\"../rimozioneVotoPost.php\">
                                    <input type=\"hidden\" name=\"posizione\" value=\"$posizione\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"NON MI PIACE\">Non mi piace</button>
                                    </form>";
    
                                }

                                else{

                                    echo"<form method=\"post\" action=\"../aggiuntaVotoPost.php\">
                                    <input type=\"hidden\" name=\"posizione\" value=\"$posizione\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Mi piace</button>
                                    </form>";
    
                                }
    
                            }
                            
    
                            
                        }

                    }

                /* se non ci sono valutazioni allora mette il tasto mi piace */

                    else{
                        echo"<form method=\"post\" action=\"../aggiuntaVotoPost.php\">
                        <input type=\"hidden\" name=\"posizione\" value=\"$posizione\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Mi piace</button>
                        </form>";

                    }
                    }    
                    /* se non è loggato l'utente allora appare il tasto per fare il login */
                    else{

                        echo"<form method=\"post\" action=\"../DB-buono/loginUser.php\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Log-in per votare</button>
                        </form>";
                        
    
                    }

                    
                echo"</div>";

                }
            }
    }


    

    $xmlString2 = "";
    foreach ( file("SCHEMI/post.xml") as $node ) {
      $xmlString2 .= trim($node);
    }


    $docXML = new DOMDocument();
    $docXML->loadXML($xmlString2);

    $rootXML = $docXML->documentElement;
    $elementiXML = $rootXML->childNodes;
    
    for ($k=0; $k<$elementiXML->length; $k++) {
      
      $elementoXML = $elementiXML->item($k);

      $ID_POST=$elementoXML->firstChild->textContent;

        $listaCommenti = $elementoXML->getElementsByTagName("commento");
        $numCommenti = $listaCommenti->length;

        if($numCommenti != 0){


            for($j=0;$j<$numCommenti;$j++){

                $commento=$listaCommenti->item($j);

                $ID_commento = $commento->firstChild;
                $ID_comm = $ID_commento->textContent;

                $textComm=$ID_commento->nextSibling;
                $textCommento=$textComm->textContent;

                $listaTag=$commento->getElementsByTagName('tagC');
                $numTag = $listaTag->length;

                for($x=0;$x<$numTag;$x++){
                    $tag_commento=$listaTag->item($x);
                    $tagCommento=$tag_commento->textContent;

                    $tagDaConfrontare= strtolower($tagCommento);

                    if($tagDaConfrontare==$tag_Ricercato){
                        echo " <div class='card'>

                        <p>$textCommento<br><p>

                        <form method=\"get\" action=\"/PAGINE SITO/VISUALIZZAZIONE SINGOLO POST CON COMMENTI.php\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"MI PIACE\">VAI AL POST</button>
                    </form>

                    </div>";
                    }
                }     
            }
            
        }


    }
}

?>



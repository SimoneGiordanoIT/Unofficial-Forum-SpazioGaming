<?php

/*Lo script si occupa di stampare post di un area, presentando i post in ordine decrescente per like. A tal scopo,si accede al contenuto del file post.xml, e si scorrono tutti i suoi elementi; in particolare
per ogni elemento, post, si scorreranno i suoi campi fino ad arrivare al campo di valutazione (like). Giunti al campo valutazione, si preleva la lista di tutte le valutazioni al post, e si calcola il numero di 
valutazioni/like che il post ha ricevuto; calcolati il numero di like si memorizza il valore dei like in un array associativo in cui in ogni posizione dell'array vi è il numero di like del post di riferimento. Si 
applica l'ordinamento di arsort sull'array, in modo da ordinare gli elementi dell'array in ordine decrescente per numero di like. Il passo successivo è prelevare tutti i campi di tutti i post, e memorizzare i valori
internamente alle apposite variabili. Poichè nell'array associativo abbiamo che la posizione dell'elemento (post) nel file xml coincide con l'ID del post, e viceversa, il passo successivo sarà scorrere gli elementi
dell'array associativo; mediante il campo posizione accederemo di volta in volta alla posizione del post internamente al file xml, e preleveremo i suoi campi. Una volta verificato che il campo visibile del post è 
pari a 1 (ovvero il post è visibile) allora verrà stampato il post con i suoi campi. Successivamente per quel post verrà prelevata la lista di valutazioni e, verificato che l'utente è loggato e che il numero di 
valutazioni è diverso da zero, si controlla per ogni valutazione che ci sia corrispondenza tra l'ID dell'utente votante, e l'ID dell'utente passato dalla session. Se il controllo da esito positivo allora significa
che l'utente aveva già votato e quindi verrà stampato il bottone di non mi piace; se il controllo da esito negativo allora significa che l'utente ancora deve votare e viene stampato il bottone di mi piace
in entrambi i casi, viene incrememntato di uno il valore del contatore. Se il valore del contatore coincide con il numero di votazioni allora viene stampato il tasto di mi piace. Invece
se il numero di valutazioni è nullo, allora viene stampato ugualmente il bottone di mi piace; se inoltre l'utente non è loggato allora viene stampato il bottone di login per votare.
Successivamente si verifica se la sessione è quella di un admin, se si, allora si accede al contenuto del file moderatoreAreaDeputy.xml e si preleva la lista dei Tag dell'area; si scorrono le aree e si aggiungono
internamente a un'array di appoggio. Si controlla che il tag del post preso in analisi non è presente internamente all'array; se il controllo da esito positivo allora significa che l'area con quel tag non ha
moderatore e viene stampato il bottone per promuovere l'utente creatore del post a moderatore dell'area; se il controllo da esito negativo allora significherà che il moderatore è già presente e quindi verrà stampato
l'apposito messaggio. 
Se la sessione è quella di un moderatore oppure di un deputy, si accede al contenuto del file moderatoreAreaDeputy.xml e si scorrono i suoi elementi memorizzando in apposite variabili i campi di interesse: ID_MOD,
area di cui è moderatore, e la lista dei deputy. Si controlla che l'ID dell'utente loggato è un moderatore, se quel moderatore è un moderatore dell' area a cui fa riferimento il post e se quel post non é stato creato 
dall'utente che sta utilizzando il sito (ovvero dall'utente loggato, il tutto per evitare che il moderatore si promuova a deputy). Se il controllo da esito positivo, allora si preleva il ruolo dell'utente creatore 
del post tramite una interrogazione al DB, e si stampano i tasti di promuovi a moderatore, riporta un problema ed elimina post.
Se la sessione è quella di un deputy, si preleva la lista dei deputy, e si verifica che l'ID del deputy in analisi coincide con quello passato dalla sessione; se la condizione è soddisfatta, allora viene stampato
vicino al post il tasto di elimina post e riporta un problema. */

include("../DB-buono/connection.php");

    $xmlString = "";
    foreach ( file("SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $TAG_Area = $_GET['TAG_AREA'];
    
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
  
          
          /* valori è l'array in cui si memorizzano l'associazione coppie di valori: id ( variabile i che sarebbe la posizione del post è anche id ), e valutazione del post */
  
          $valori[$i]=$valutazioneP;
  
          
  
  
          arsort($valori); /* arsort ordina in base al secondo valore della coppia ovvero la valutazione del post, l'ordinamento è discendente */
      }
  
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

        $titleHTML = htmlentities($title);

        $text=$P_Text->item($posizione)->nodeValue;

        $textHTML= htmlentities($text);

              $att=$P_atta->item($posizione)->nodeValue;
              $tagName=$P_Tag->item($posizione)->nodeValue;
              $data=$P_Data->item($posizione)->nodeValue;
              $visibile=$P_visibile->item($posizione)->nodeValue;


        if($TAG_Area==$tagName){
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

                    $elemento = $elementi->item($posizione); /* prende il post indicato da item($posizione) */
                    
                    $listaValutazioni = $elemento->getElementsByTagName("valutazionePost"); /* prende tutte le valutazioni di quel post e ne calcola la lunghezza   */
                    $numValutazioni = $listaValutazioni->length;

                    $contatore = 0;

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

                                    echo"<form method=\"post\" action=\"../riaggiuntaVotoPost.php\">
                                    <input type=\"hidden\" name=\"posizione\" value=\"$posizione\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Mi piace</button>
                                    </form>";
    
                                }
    
                            }

                            else{
                                $contatore = $contatore + 1;
            
                            }

                            
    
                            
                        }

                        if($contatore == $numValutazioni){
                            echo"<form method=\"post\" action=\"../aggiuntaVotoPost.php\">
                                <input type=\"hidden\" name=\"posizione\" value=\"$posizione\">
                                <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Mi piace</button>
                                </form>";
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


                if(isset($_SESSION['ruolo'])){

                    if($_SESSION['ruolo'] == 100){

                        $xmlString = "";
                        foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
                        $xmlString .= trim($node);
                        }


                        $docXML2 = new DOMDocument();
                        $docXML2->loadXML($xmlString);

                        $rootXML2 = $docXML2->documentElement;
                        $elementiXML2 = $rootXML2->childNodes;

                        $listaAree = $docXML2->getElementsByTagName("TAG_AREA");
                        $numAree = $listaAree->length;

                        $arrayAree=array();

                        for($n=0; $n<$elementiXML2->length; $n++){

                            $area_da_confrontare = $listaAree->item($n)->textContent;
                            array_push($arrayAree,$area_da_confrontare);

                            }

                            if(!in_array($tagName,$arrayAree)){

                                /*CONTROLLO SE L'UTENTE NON SIA GIA UN MODERATORE O UN DEPUTY*/

                                $sql = "SELECT *
                                FROM $db_tab_utente 
                                WHERE userID = \"$ID_CREATORE\"
                            ";
                    
                                if (!$result = mysqli_query($mysqliConnection, $sql)) {
                                    printf("Errore nella query di ricerca reputazioni\n");
                                exit();
                                }
                                
                            $row = mysqli_fetch_array($result);

                            $ruoloUtente= $row['ruolo'];

                            if($ruoloUtente == 1){

                                echo"<form method=\"post\" action=\"../promozioneUtenteAModeratore.php\">
                                <input type=\"hidden\" name=\"ID_CREATORE\" value=\"$ID_CREATORE\">
                                <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tagName\">
                                <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"Promuovi\">PROMUOVI A MODERATORE DI QUEST'AREA</button>
                                </form>";

                            }
                            else{
                                print('UTENTE GIA MODERATORE O DEPUTY');
                            }
                            

                                

                            }
                            else{
                                print('MODERATORE GIA PRESENTE');
                            }
                            
                    

                    }





                    if($_SESSION['ruolo'] == 10 || $_SESSION['ruolo'] == 15){
                        /* DEvo controllare che l'utente che sta visualizzando il post si una moderatore o un deputy, assegnati al post
                    cui stiamo facendo riferimento */

                    $xmlString2 = "";
                    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
                    $xmlString2 .= trim($node);
                    }


                    $docXML = new DOMDocument();
                    $docXML->loadXML($xmlString2);

                    $rootXML = $docXML->documentElement;
                    $elementiXML = $rootXML->childNodes;

                    
                    for ($j=0; $j<$elementiXML->length; $j++) {
                    
                        $elementoXML = $elementiXML->item($j);
                        
                        $ID_M = $elementoXML->firstChild;
                        $ID_MOD = $ID_M->textContent;
                        
                        $area=$ID_M->nextSibling;
                        $area_Moderatore=$area->textContent;

                        $listaDeputy = $elementoXML->getElementsByTagName('ID_DEP');
                        $numDeputy=$listaDeputy->length;

                        /* Controllo che l'ID dell'utente che sta lavorando in questo momento è un moderatore e se quel moderatore è
                        un moderatore dell' area a cui fa riferimento il post e soprattutto se quel post non é stato creato dall'utente che sta utilizzando il sito (sennó é un moderatore che si potrebbe
                        promuovere anche a deputy*/
                    
                        if($_SESSION['ID']==$ID_MOD && $TAG_Area == $area_Moderatore && $ID_CREATORE != $_SESSION['ID'] ){

                            include("DB-buono/connection.php");

                                $query ="   SELECT *
                                            FROM $db_tab_utente 
                                            WHERE userID=\"$ID_CREATORE\"
                                            ";
                                
                                if (!$result = mysqli_query($mysqliConnection, $query)) {
                                        printf("Errore nella query di salvataggio reputazione finale\n");
                                    exit();
                                    }

                                    $row = mysqli_fetch_array($result);

                                    
                                    $ruolo = $row['ruolo'];


                                if($ruolo != 100 ){
                                    if ($ruolo == 1){
                                        echo"<form method=\"post\" action=\"../promozioneUtenteADeputy.php\">
                                        <input type=\"hidden\" name=\"ID_CREATORE\" value=\"$ID_CREATORE\">
                                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"Promuovi\">PROMUOVI A DEPUTY</button>
                                        </form>";

                                    }
                                    else{
                                        print('UTENTE GIA MODERATORE O DEPUTY');

                                    }
                                    

                                    echo "<form method=\"post\" action=\"../EliminazionePostDaModerazione.php\">
                                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"ELIMINA\">ELIMINA POST</button>
                                    </form>";
                
                                    /* Nel caso in cui l'utente che sta visualizzando questo post sia un moderatore o un deputy, viene sbloccato un tasto di 
                            REPORT attraverso un semplice bottone che manda alla form di compilazione*/
            
                                    echo"<form method=\"post\" action=\"../gestioneModificaPost.php\">
                                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                                    <input type=\"hidden\" name=\"ID_UT\" value=\"$ID_CREATORE\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">RIPORTA UN PROBLEMA</button>
                                    </form>";

                                }

                                
                        }

                        
                        /*Se invece l'utente che sta lavorando è un deputy, allora devo controllare che sia un deputy dell' area del post
                        a cui sto facendo riferimento */

                        if($_SESSION['ruolo']==15){

                            if($TAG_Area == $area_Moderatore){
                                for($x=0;$x<$numDeputy;$x++){
                                    $deputy = $listaDeputy->item($x);
                                    $deputyDaConfrontare = $deputy->textContent;
                                    if($_SESSION['ID'] == $deputyDaConfrontare){

                                        echo "<form method=\"post\" action=\"../EliminazionePostDaModerazione.php\">
                        <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"ELIMINA\">ELIMINA POST</button>
                        </form>";
    
                        /* Nel caso in cui l'utente che sta visualizzando questo post sia un moderatore o un deputy, viene sbloccato un tasto di 
                REPORT attraverso un semplice bottone che manda alla form di compilazione*/
    
                echo"<form method=\"post\" action=\"../gestioneModificaPost.php\">
                <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                <input type=\"hidden\" name=\"ID_UT\" value=\"$ID_CREATORE\">
                <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">RIPORTA UN PROBLEMA</button>
                </form>";
                                    }

                            }
                        }



                        }

                }

                    }

                
            }
            echo"</div>";

        }

    }

}
    
    ?>

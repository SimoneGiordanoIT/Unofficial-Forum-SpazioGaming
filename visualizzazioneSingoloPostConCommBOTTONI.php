<?php

/* Lo script inizia con l'identificazione del post di riferimento, passato da un'altra pagina attraverso una GET contenente l'ID del post di riferimento. Vengono parsati i vari campi ponendo una particolare attenzione
ai campi che verranno visualizzati a schermo, modificandoli, grazie alla funzione htmlentities, in un formato leggibile dall'HTML. Nella variabile valutazioneP vengono salvate le valutazioni del post, in quanto,
vengono salvate nella lista Valutazioni tutte le valutazioni e viene controllato il campo di valutazione che puo essere o uno o zero, quindi vengono sommate alle valutazioni in precedenza. Viene fatta una richiesta al DB
per controllare l username utente del creatore del post tramite una semplice query dove la variabile di WHERE viene impostata con l'ID dell'utente creatore del post. Se l'utente che sta visualizzando il sito é un
ADMIN (controllo effettuato tramite la variabile session ruolo) vengono sbloccate due funzionalitá dipendenti dallo stato dell'utente; se l'utente creatore del post é bannato, allora viene sbloccato il pulsante 
sbanna, se l'utente non é bannato, é possibile farlo. Vengono stampati i dettagli del post e, se il campo di attachment é diverso da zero, viene stampata l'immagine di riferimento con nome del tag del post id.
Gli utenti hanno la possibilitá di aggiungere o meno un mi piace al post, per far cio, vengono controllati tre fattori: il primo é controllare non ci sono valutazioni a quel post, in questo caso compare il tasto di
"mi piace", il secondo é controllare se l'utente ha gia messo mi piace al post e in questo caso compare il tasto di "non mi piace", e il terzo é controllare se l'utente ha gia una valutazione, ma la valutazione é non mi piace
allora il pulsante diviene "mi piace".
Se il creatore del post é anche l'utilizzatore del sito, compare il pulsante di modifica post.
Al post é possibile sottomettere un commento "generico", ovvero non vincolato da altri commenti presenti sul post, i valori passati allo script di creazione commento, cambiano nel caso in cui vi siano o meno 
dei commenti al post, in quanto se vi erano presenti, viene passato l'ultimo ID dei commenti, altrimenti quella variabile viene impostata a 0.
La visualizzazione dei commenti al post é ordinata in base a due fattori: il primo é la temporalitá e il secondo la parentela. I commenti vengono ordinati in base al fatto se essi sono figli, e quindi risposte, 
ad altri commenti e in base all'ordine di sottomissione (dal piu vecchio al piu giovane).
Innanzitutto si controlla se vi siano commenti al post, e si inizializzano gli array di commenti usati e commenti figli. Si controlla se il commento di riferimento sia un commento madre, e quindi l'elemento id commento
superiore non é presente, si ricava il voto del commento (tramite apposito script) e si visualizza il link alle informazioni personali del cretore del commento tramite richiesta al db per la ricerca dell'
username. Oltre alle varie informazioni del commento, come il testo e il voto, viene visualizzata a schermo un elenco di stelle che stanno ad identicare l'eventuale valutazione da assegnare a quel commento, valutazione
che viene confermata se cliccato il pulsante "Invia". É possibile creare un commento figlio del commento in questione grazie al pulsante di "rispondi". Se l'utente visualizzatore é un ADMIN, o un deputy
moderator o moderatore di quell'area, viene visualizzato il pulsante di eliminazione del commento. A questo punto, é necessario cercare, all'interno dei commenti del post, se sono presenti risposte a quel determinato
commento. Per far cio, vengono analizzati tutti i commenti del post, e si controlla se sono presenti commenti che possiedono la variabile id commento superiore uguale a quella del commento di riferimento (e che
quindi sono loro figli). Se si trova un commento con la presenza della variabile id commento superiore, si analizza se é all'interno dell'array usati, se il riscontro da esito positivo, il commento viene scartato, 
altrimenti si controlla se l'id commento superiore é uguale a quello del commento superiore, se risulta allora vengono visualizzate le informazioni relative a quel commento come visto prima e l'id del commento viene
inserito negli array di usati e figli. Se il confronto tra l'id del commento superiore e l'id del commento avesse dato esito negativo, allora si controlla se l'id del commento superiore é all'interno dell' array
dei figli, in modo da identificare una risposta a una risposta ad un commento. La gestion del commento é totalmente analoga a quella della risposta a commento.

*/
   

    $xmlString = "";
    foreach ( file("SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $ID_POST_SPECIFICO = $_GET['ID_POST'];

    
    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);

        $ID = $elemento->firstChild;
        $ID_Check=$ID->textContent;

        if($ID_Check==$ID_POST_SPECIFICO){

            $tag = $ID->nextSibling;
            $tagName = $tag->textContent;
    
            $ID_C = $tag->nextSibling;
            $ID_Creatore = $ID_C->textContent;
    
            $dataCre = $ID_C->nextSibling;
            $data = $dataCre->textContent;
    
            $titolo = $dataCre->nextSibling;
            $title = $titolo->textContent;

            $titleHTML = htmlentities($title);
    
            $testo = $titolo->nextSibling;
            $text = $testo->textContent;

            $textHTML = htmlentities($text);

            
            $attachment = $testo->nextSibling;
            $att = $attachment->textContent;
            
            $vis = $attachment->nextSibling;
            $visibile = $vis->textContent;
    
            $valutazioneP = 0;

            $listaValutazioni = $elemento->getElementsByTagName("valutazionePost"); /* prende tutte le valutazioni di quel determinato post */
            $numValutazioni = $listaValutazioni->length;
    
            for($j = 0;$j <$numValutazioni ; $j++){  /* calcolo della votazione del post dato dalla somma tra valutazioni passate ( salvate in valutazioneP) e la valutazione successiva (flag_voto) */

                $valutazione = $listaValutazioni->item($j);

                $flag_voto = $valutazione->lastChild->textContent;

                $valutazioneP = $valutazioneP + $flag_voto;
            }


    /*CERCO NOME CREATORE*/

            include("../DB-buono/connection.php");
        
            $sql = "SELECT *
                    FROM $db_tab_utente 
                    WHERE userID = \"$ID_Creatore\"
                ";
        
                    if (!$result = mysqli_query($mysqliConnection, $sql)) {
                        printf("Errore nella query di ricerca reputazioni\n");
                    exit();
                    }
                    
                $row = mysqli_fetch_array($result);

                $nomeUtente= $row['nome'];
                $banUt = $row['ban'];

                if(isset($_SESSION['ruolo'])){

                    if($_SESSION['ruolo']==100){
                        if($banUt==0){
                        
                        echo"
                        <form method=\"post\" action=\"../bannaUtente.php\">
                        <input type=\"hidden\" name=\"ID_CREATORE\" value=\"$ID_Creatore\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"BAN\">Banna utente</button>
                        </form>
                        ";
                        }
                        else{
                        echo"
                        <form method=\"post\" action=\"../SbannaUtente.php\">
                        <input type=\"hidden\" name=\"ID_CREATORE\" value=\"$ID_Creatore\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"SBAN\">Sbanna utente</button>
                        </form>
                        ";
                        }
                    }
                }
        
        echo " 

                    <a href='INFORMAZIONI PERSONALI.php?ID_CREAT=".$ID_Creatore."'<h3> Creato da: $nomeUtente. Clicca qui per visualizzare le sue info. </h3></a>

                    <h1>TITOLO: $titleHTML</h1>
                    <h2 class='title_descrip'>TAG: $tagName </h2>
                    <h3>Sottomesso in data: $data </h3>
                    <p class='textP'>$textHTML</p>
                    <h3>Like: $valutazioneP</h3>

                ";

            if ($att != '0') {

                $nomeImmagine = $tagName . $ID_POST_SPECIFICO;

                echo "<img src='ATTACHMENT/$nomeImmagine.png' />";
        
                }

                
                
                    
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
                                    <input type=\"hidden\" name=\"posizione\" value=\"$i\">
                                    <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"NON MI PIACE\">Non mi piace</button>
                                    </form>";
    
                                }

                                else{

                                    echo"<form method=\"post\" action=\"../riaggiuntaVotoPost.php\">
                                    <input type=\"hidden\" name=\"posizione\" value=\"$i\">
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
                                <input type=\"hidden\" name=\"posizione\" value=\"$i\">
                                <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"MI PIACE\">Mi piace</button>
                                </form>";
                        }

                    }

                /* se non ci sono valutazioni allora mette il tasto mi piace */

                else{
                    echo"<form method=\"post\" action=\"../aggiuntaVotoPost.php\">
                    <input type=\"hidden\" name=\"posizione\" value=\"$i\">
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

                if(isset($_SESSION['ID'])){
                    if($_SESSION['ID']==$ID_Creatore){
                        echo"<form method=\"post\" action=\"../ModificaPost.php\">
                        <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_Check\">
                        <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"Modifica\">Modifica Post</button>
                        <hr>
                        </form>
                ";


                    }
                }

            $listaCommenti = $elemento->getElementsByTagName("commento");
            $numCommenti = $listaCommenti->length;

                if(isset($_SESSION['ruolo'])){

                

            if($numCommenti != 0){
                $ULTIMO_ID = $listaCommenti->item($numCommenti-1);
                $ID_DA_PASSARE=$ULTIMO_ID->firstChild->textContent;
                

                echo"<form method=\"get\" action=\"../CreazioneCommento.php\">
                    <input type=\"hidden\" name=\"TAG\" value=\"$tagName\">
                    <input type=\"hidden\" name=\"ID_ULTIMO\" value=\"$ID_DA_PASSARE\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                    <button class='btn btn-success btn-lg btn-comm' type=\"submit\" value=\"RISPONDI COMM GENERICO\">Rispondi comm generico</button>
                    </form>";

            }else{
                echo"<form method=\"get\" action=\"../CreazioneCommento.php\">
                    <input type=\"hidden\" name=\"TAG\" value=\"$tagName\">
                    <input type=\"hidden\" name=\"ID_ULTIMO\" value=\"0\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                    <button class='btn btn-success btn-lg btn-comm' type=\"submit\" value=\"RISPONDI COMM GENERICO\">Rispondi comm generico</button>
                    </form>";

            }

        }



        if($numCommenti != 0){

            $ArrayUsati= array();

            for($j=0;$j<$numCommenti;$j++){

                $ArrayFigli=array();

                $commento=$listaCommenti->item($j);

                $ID_commento = $commento->firstChild;
                $ID_comm = $ID_commento->textContent;
                

                if ($commento->getElementsByTagName('ID_com_superiore')->length == 0){

                    $testoC = $ID_commento->nextSibling;
                    $textComm=$testoC->textContent;

                    $textC= htmlentities($textComm);

                    $ID_commentatore = $testoC->nextSibling->textContent;



                    include("scriptRicavoVotoCommenti.php");

                   /*CERCO NOME CREATORE COMMENTO*/

                   include("../DB-buono/connection.php");
                    
                   $sql = "SELECT *
                           FROM $db_tab_utente 
                           WHERE userID = \"$ID_commentatore\"
                       ";
               
               if (!$result = mysqli_query($mysqliConnection, $sql)) {
                   printf("Errore nella query di ricerca reputazioni\n");
               exit();
               }
               
           $row = mysqli_fetch_array($result);

           $nomeCommentatore= $row['nome'];
               
           

               echo "

               <div class='commento-madre'> 

                   <p class='textC'>$textC</p>
                   <a href='INFORMAZIONI PERSONALI.php?ID_CREAT=".$ID_commentatore."'<h3> Creato da: $nomeCommentatore. Clicca qui per visualizzare le sue info. </h3></a>
                   <h4><strong>  Votazione commento: $VOTO_COMM </strong></h4>
                   </div>
                   ";
                        if(isset($_SESSION['ruolo'])){


                    echo"<form method=\"post\" action=\"../valutazioneCommenti.php\">
                    <div class='row'>
                    <div class='box'>
                            <select name=\"voti\">
                                <option value=\"1\">&#9733;</option>
                                <option value=\"2\">&#9733;&#9733;</option>
                                <option value=\"3\">&#9733;&#9733;&#9733;</option>
                                <option value=\"4\">&#9733;&#9733;&#9733;&#9733;</option>
                                <option value=\"5\">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                            </select>
                    </div>
                    <input type=\"hidden\" name=\"ID_COMM\" value=\"$ID_comm\">
                    <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentatore\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" name=\"VOTO\" value=\"INVIA\">Invia</button>
                    </form>
                    ";

                    echo"<form method=\"get\" action=\"../CreazioneCommento.php\">
                    <input type=\"hidden\" name=\"IDMADRE\" value=\"$ID_comm\">
                    <input type=\"hidden\" name=\"TAG\" value=\"$tagName\">
                    <input type=\"hidden\" name=\"ID_ULTIMO\" value=\"$ID_DA_PASSARE\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"RISPONDI\">Rispondi</button>
                    </form>
                   </div>
                   "; 



                   if($_SESSION['ruolo'] == 100){
                    echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                    <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentatore\">
                    <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_comm\">
                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                    </form>
                                        
                    ";
                }




                   if($_SESSION['ruolo'] == 10 || $_SESSION['ruolo'] == 15){

                    $xmlString2 = "";
                    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
                    $xmlString2 .= trim($node);
                    }
        
        
                    $doc2 = new DOMDocument();
                    $doc2->loadXML($xmlString2);
        
                    $root2 = $doc2->documentElement;
                    $elementi2 = $root2->childNodes;
                    
                    for ($l=0; $l<$elementi2->length; $l++) {
                    
                    $elemento2 = $elementi2->item($l);
        
                        $ID_MOD = $elemento2->firstChild;
                        $ID_MOD_Area=$ID_MOD->textContent;
        
                        $AREA_MOD = $ID_MOD->nextSibling;
                        $TAG_AREA_MOD = $AREA_MOD->textContent;
        
                        if($_SESSION['ID'] == $ID_MOD_Area && $tagName == $TAG_AREA_MOD){
                            echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                            <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentatore\">
                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_comm\">
                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                            </form>
                                                
                            ";
                        }
        
                        $listaDeputy = $elemento2->getElementsByTagName("ID_DEP");
                        $numDeputy = $listaDeputy->length;
        
                        for($x = 0;$x <$numDeputy ; $x++){
        
                            $deputy = $listaDeputy->item($x);
                            $deputyDaConfrontare = $deputy->textContent;
        
                            if($_SESSION['ID'] == $deputyDaConfrontare){
                                echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentatore\">
                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_comm\">
                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                            </form>
                                                
                            ";
                            }
                            
                        }
        
        
                }

                   }






                   
                
                }

                }

                
                for ($k=0;$k<$numCommenti;$k++){
                    $risposta=$listaCommenti->item($k);

                    if ($risposta->getElementsByTagName('ID_com_superiore')->length!=0){
                        $ID_com_sup = $risposta->lastChild;
                        $ID_com_sup_text = $ID_com_sup->textContent;

                        $ID_FIGLIO=$risposta->firstChild;
                        $ID_Child=$ID_FIGLIO->textContent;


                        if(!in_array($ID_Child,$ArrayUsati)){
                            if( $ID_com_sup_text == $ID_comm){

                                $testoFiglio = $ID_FIGLIO->nextSibling;
                                $textChildComm=$testoFiglio->textContent;

                                $textChild= htmlentities($textChildComm);

                                $ID_commenta = $testoFiglio->nextSibling->textContent;

    
                                include("scriptRicavoVotoCommentiRisposte.php");


                                    /*CERCO NOME CREATORE COMMENTO*/

                                    include("../DB-buono/connection.php");
                                                                    
                                    $sql = "SELECT *
                                            FROM $db_tab_utente 
                                            WHERE userID = \"$ID_commenta\"
                                        ";

                                    if (!$result = mysqli_query($mysqliConnection, $sql)) {
                                    printf("Errore nella query di ricerca reputazioni\n");
                                    exit();
                                    }

                                    $row = mysqli_fetch_array($result);

                                    $nomeCommentaChild= $row['nome'];



                                    echo"  
                                    <div class='commento-child'>

                                    <p class='textC'>$textChild</p>
                                    <a href='INFORMAZIONI PERSONALI.php?ID_CREAT=".$ID_commenta."'<h3> Creato da: $nomeCommentaChild. Clicca qui per visualizzare le sue info. </h3></a>                    
                                    <h4><strong>  Votazione commento RISPOSTA:$VOTO_COMM </strong></h4>
                                    </div>                  
                                    ";
    
                            if(isset($_SESSION['ruolo'])){

                                echo"<form method=\"post\" action=\"../valutazioneCommenti.php\">
                                <div class='box'>
                                    <select name=\"voti\">
                                                <option value=\"1\">&#9733;</option>
                                                <option value=\"2\">&#9733;&#9733;</option>
                                                <option value=\"3\">&#9733;&#9733;&#9733;</option>
                                                <option value=\"4\">&#9733;&#9733;&#9733;&#9733;</option>
                                                <option value=\"5\">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                                    </select>
                                </div>
                                <input type=\"hidden\" name=\"ID_COMM\" value=\"$ID_Child\">
                                <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commenta\">
                                <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                <button class='btn btn-success btn-lg btn-block' type=\"submit\" name=\"VOTO\"   value=\"INVIA\">Invia</button>
                                </form>";

                                echo"<form method=\"get\" action=\"../CreazioneCommento.php\">
                                <input type=\"hidden\" name=\"IDMADRE\" value=\"$ID_Child\">
                                <input type=\"hidden\" name=\"TAG\" value=\"$tagName\">
                                <input type=\"hidden\" name=\"ID_ULTIMO\" value=\"$ID_DA_PASSARE\">
                                <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">

                                <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"RISPONDI\">Rispondi</button>
                                </form>
                                
                                ";

                                if($_SESSION['ruolo'] == 100){
                                    echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                    <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentatore\">
                                    <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_comm\">
                                    <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                                    </form>
                                                        
                                    ";
                                }
                            
                                if($_SESSION['ruolo'] == 10 || $_SESSION['ruolo'] == 15){

                                    $xmlString2 = "";
                                    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
                                    $xmlString2 .= trim($node);
                                    }
                        
                        
                                    $doc2 = new DOMDocument();
                                    $doc2->loadXML($xmlString2);
                        
                                    $root2 = $doc2->documentElement;
                                    $elementi2 = $root2->childNodes;
                                    
                                    for ($l=0; $l<$elementi2->length; $l++) {
                                    
                                    $elemento2 = $elementi2->item($l);
                        
                                        $ID_MOD = $elemento2->firstChild;
                                        $ID_MOD_Area=$ID_MOD->textContent;
                        
                                        $AREA_MOD = $ID_MOD->nextSibling;
                                        $TAG_AREA_MOD = $AREA_MOD->textContent;
                        
                                        if($_SESSION['ID'] == $ID_MOD_Area && $tagName == $TAG_AREA_MOD){
                                            echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                            <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commenta\">
                                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_Child\">
                                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                                            </form>
                                                                
                                            ";
                                        }
                        
                                        $listaDeputy = $elemento2->getElementsByTagName("ID_DEP");
                                        $numDeputy = $listaDeputy->length;
                        
                                        for($x = 0;$x <$numDeputy ; $x++){
                        
                                            $deputy = $listaDeputy->item($x);
                                            $deputyDaConfrontare = $deputy->textContent;
                        
                                            if($_SESSION['ID'] == $deputyDaConfrontare){
                                                echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                                <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commenta\">
                                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_Child\">
                                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                                            </form>
                                                                
                                            ";
                                            }
                                            
                                        }
                        
                        
                                }
                
                                   }
                            
                            }
    
                                array_push($ArrayFigli,$ID_Child);
    
                                array_push($ArrayUsati,$ID_Child);
                                
    
                            }
    
                            else if(in_array($ID_com_sup_text,$ArrayFigli)){
    
                                $ID_FIGLIO=$risposta->firstChild;
                                $ID_Child=$ID_FIGLIO->textContent;
    
                                $testoFiglio = $ID_FIGLIO->nextSibling;
                                $textChildComm=$testoFiglio->textContent;

                                $textChild= htmlentities($textChildComm);

                                $ID_commentato = $testoFiglio->nextSibling->textContent;
    
                                include("scriptRicavoVotoCommentiRisposte.php");
                                /*CERCO NOME CREATORE COMMENTO*/

                                include("../DB-buono/connection.php");
                                                                
                                $sql = "SELECT *
                                        FROM $db_tab_utente 
                                        WHERE userID = \"$ID_commentato\"
                                    ";

                                if (!$result = mysqli_query($mysqliConnection, $sql)) {
                                    printf("Errore nella query di ricerca reputazioni\n");
                                exit();
                                }

                                $row = mysqli_fetch_array($result);

                                $nomeCommentaChild_Child= $row['nome'];



                                echo"   
                                <div class='comment-child-child'>
                                
                                <p class='textC'>$textChild</p>
                                <a href='INFORMAZIONI PERSONALI.php?ID_CREAT=".$ID_commentato."'<h3> Creato da: $nomeCommentaChild_Child. Clicca qui per visualizzare le sue info. </h3></a>               
                                <h4><strong> Votazione commento:$VOTO_COMM </strong> </h4>
                                </div>
                                ";

                                if(isset($_SESSION['ruolo'])){
                                
                                echo"<form method=\"post\" action=\"../valutazioneCommenti.php\">
                                <div class='box'>
                                    <select name=\"voti\">
                                                <option value=\"1\">&#9733;</option>
                                                <option value=\"2\">&#9733;&#9733;</option>
                                                <option value=\"3\">&#9733;&#9733;&#9733;</option>
                                                <option value=\"4\">&#9733;&#9733;&#9733;&#9733;</option>
                                                <option value=\"5\">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
                                    </select>
                                </div>
                                <input type=\"hidden\" name=\"ID_COMM\" value=\"$ID_Child\">
                                <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentato\">
                                <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                <button class='btn btn-success btn-lg btn-block' type=\"submit\" name=\"VOTO\" value=\"INVIA\">Invia</button>
                                </form>
                                ";

                                echo"<form method=\"get\" action=\"../CreazioneCommento.php\">
                                            <input type=\"hidden\" name=\"IDMADRE\" value=\"$ID_Child\">
                                            <input type=\"hidden\" name=\"TAG\" value=\"$tagName\">
                                            <input type=\"hidden\" name=\"ID_ULTIMO\" value=\"$ID_DA_PASSARE\">
                                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"RISPONDI\">Rispondi</button>
                                            </form>   
                                                                  
                                  "; 
                                
                                  if($_SESSION['ruolo'] == 10 || $_SESSION['ruolo'] == 15){

                                    $xmlString2 = "";
                                    foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
                                    $xmlString2 .= trim($node);
                                    }
                        
                        
                                    $doc2 = new DOMDocument();
                                    $doc2->loadXML($xmlString2);
                        
                                    $root2 = $doc2->documentElement;
                                    $elementi2 = $root2->childNodes;
                                    
                                    for ($l=0; $l<$elementi2->length; $l++) {
                                    
                                    $elemento2 = $elementi2->item($l);
                        
                                        $ID_MOD = $elemento2->firstChild;
                                        $ID_MOD_Area=$ID_MOD->textContent;
                        
                                        $AREA_MOD = $ID_MOD->nextSibling;
                                        $TAG_AREA_MOD = $AREA_MOD->textContent;
                        
                                        if($_SESSION['ID'] == $ID_MOD_Area && $tagName == $TAG_AREA_MOD){
                                            echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                            <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentato\">
                                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_Child\">
                                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                                            </form>
                                                                
                                            ";
                                        }
                        
                                        $listaDeputy = $elemento2->getElementsByTagName("ID_DEP");
                                        $numDeputy = $listaDeputy->length;
                        
                                        for($x = 0;$x <$numDeputy ; $x++){
                        
                                            $deputy = $listaDeputy->item($x);
                                            $deputyDaConfrontare = $deputy->textContent;
                        
                                            if($_SESSION['ID'] == $deputyDaConfrontare){
                                                echo"<form method=\"post\" action=\"../EliminazioneCommentoDaModerazione.php\">
                                                <input type=\"hidden\" name=\"ID_COMMENTATORE\" value=\"$ID_commentato\">
                                            <input type=\"hidden\" name=\"ID_COMMENTO\" value=\"$ID_Child\">
                                            <input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST_SPECIFICO\">
                                            <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"EliminaCOMM\">Elimina Commento</button>
                                            </form>
                                                                
                                            ";
                                            }
                                            
                                        }
                        
                        
                                }
                
                                   }
                                }
    
                                array_push($ArrayFigli,$ID_Child);
                                array_push($ArrayUsati,$ID_Child);
                                
                            }

                        }
                       
                
                    }
                    
                    
                }
                
            }
            

        }

        }

    }
    
?>

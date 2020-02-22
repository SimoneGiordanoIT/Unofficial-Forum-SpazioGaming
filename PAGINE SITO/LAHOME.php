<!-- La pagina di informazioni personali é divisa in 2 parti fondamentali: Parte superiore e parte inferiore, a sua volta suddivisa in 3 colonne.
La parte superiore della pagina presenta l'immagine del sito cliccabile, che, se cliccata, rimanda alla home del sito, e 4 scritte: nel caso in cui l'utente non sia loggato, esse si suddividono in HOME (che rimanda alla
gome del sito), SEARCH (che rimanda alla pagina di ricerca), LOGIN (che rimanda alla pagina di LOGIN) e SIGN-UP(che rimanda alla pagina di registrazione). Le ultime due cambiano nel caso l'utente sia loggato, 
e quindi se é settata una sessione, in quanto la scritta di LOGIN viene sostituita dal'username dell'utente loggato (che se cliccata rimanda alla pagina personale) e dal tasto di logout che 
cancella la sessione corrente. Tutti questi casi sono applicati tramite una href che fa riferimento alla pagina di riferimento.
La parte inferiore della pagina presenta, nella colonna di sinistra, funzioni specifiche al tipo di utente utilizzatore del sito e la visualizzazione delle aree visibili nel sito; Se l'utente utilizzatore é loggato
e il suo ruolo é 1, ovvero semplice utente registrato, viene sbloccato il pulsante di richiesta informazioni all'ADMIN che, se cliccato, rimanda alla form, nel caso in cui l'utente sia un ADMIN, e che quindi
il ruolo salvato nell variabile SESSION sia 100, viene sbloccato il pulsante di creazione area. Per la visualizzazione delle aree, viene aperto il file xml AreeInteresse, che al suo interno sono salvate tutte le 
informazioni delle aree presenti sul sito,esse vengono salvate nella variabile elementi, che, tramite un ciclo for che si conclude col numero di elementi, stampa a schermo le aree di interesse se e solo se la variabile
di visibile é impostata a 1; per passare da un elemento all'altro di un singolo nodo, viene utilizzata la funzione di nextSibling. La colonna centrale presenta l'inclusione dello script di Visualizzazione dei post.
La colonna di destra, invece, presenta 3 oppure 2 div cliccabili rispettivamente nel caso in cui l'utente sia loggato o meno. Nel primo caso, le div, e quindi le schede, sono la Creazione del post (che apre la
form di creazione post), la visualizzazione del numero di notifiche (che apre la pagina delle notifiche) e le regole (che apre la pagina delle regole del sito). Nel secondo caso, quindi venendo meno
la variabile SESSION, la div della creazione del post diviene NON cliccabile (impedendo quindi la creazione di un post ad un utente non registrato), e viene meno la scheda di visualizzazione delle notifiche.
-->



<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
    <PHP> <link rel="stylesheet" type="text/css" href="CSS/MENUPROVA.css"></head> <PHP>
        <title>HOMEPAGE</title>
    </head>
    
    <body class="superior_page">
        <div id="main_page" class="main_page">
                <div id="head_wrap" class="head_wrap"> 
                    <div id="head_box" class="head_box_fix">
                        <a class="home_link" title="Spaziogaming" href="./LAHOME.php"><img id="img_titolo" src="./IMMAGINI/titolo_sito.png"></a>
                    </div>
                <div id="navig" class="navig"> 
                    <div class="nav_box" id="navBox">
                        <ul class="navigation">
                        <li> <a id="home" title="home" href="./LAHOME.php"><img src="./IMMAGINI/home.png"></a> </li>
                        <li> <a id="serch" title="ricerca commenti e post" href="./RICERCA.php"><img src="./IMMAGINI/search.png"></a> </li>
                        <?php

                        /* se l'utente è loggato allora viene stampato il suo username al posto dell'icona signup e il logout al post di login, altrimenti stampa signup e login*/ 

                            session_start();   
                            if(isset($_SESSION['ruolo'])){
                                $nomeDaVisualizzare=$_SESSION['username'];
                                echo"<li> <a id='AreaPersonale' title='Area Personale' href='PAGINA PERSONALE.php' style = 'font-family:Comic Sans MS;color:#490086;text-decoration: none;
                                font-size:200%;font-weight: bold;'>$nomeDaVisualizzare</a> </li>
                                <li> <a id='logout' title='logout' href='../DB-buono/logout.php'><img src='./IMMAGINI/logout.png'></a> </li>
                                ";
                            }
                            else{
                                echo"<li> <a id='sign-up' title='iscriviti' href='../DB-buono/createUser.php'><img src='./IMMAGINI/signup.png'></a> </li>
                                <li> <a id='login' title='accedi' href='../DB-buono/loginUser.php'><img src='./IMMAGINI/login.png'></a> </li>
                                ";

                            }
                            ?>
                            </ul>
                        <div class="nav_fix"></div>
                    </div>
                </div>
                </div>
            <div class="contentwrapper">
                
                    <div class="leftcolumnwidget">
                        <div class="leftcolumnwidgettop">
                         
                            <img class="menuIMG" src='./IMMAGINI/menu.png'>
                            </div>
                            
                            <?php

                            /* se loggato come utente registrato,  prima della visualizzazione delle aree stampa il tasto per l'assistenza e la creazione di nuove aree. Se è loggato un admin(2ndo if) allora 
                             viene stampata il bottone per la funzione di crea aree */ 

                            if(isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 1 ){
                            
                                echo"<div class='vertical_menu'>
                                            <form  action='../CreazioneMessaggioUtente-ADM.php'>
                                            
                                            <button>CHIEDI ALL'ADMIN DI AGGIUNGERE UN GIOCO O PER ASSISTENZA</button>
                                            </form>
                            </div>

                            <hr>";
                            }else if(isset($_SESSION['ruolo']) && $_SESSION['ruolo'] == 100){
                                echo"<div class='vertical_menu'>
                                            <form  action='../creazioneAree.php'>
                                            
                                            <button>CREA UNA NUOVA AREA</button>
                                            </form>
                            </div>

                            <hr>";

                            }

                
                            include("CSS/MENUPROVA.css");

                            $xmlString = "";
                            foreach ( file("SCHEMI/AreeInteresse.xml") as $node ) {
                            $xmlString .= trim($node);
                            }


                            $doc = new DOMDocument();
                            $doc->loadXML($xmlString);

                            $root = $doc->documentElement; /* la radice da cui prendere i successivi elementi o i childnodes*/ 
                            $elementi = $root->childNodes;
                            
                            for ($i=0; $i<$elementi->length; $i++) {
                            
                            $elemento = $elementi->item($i); /* focus su un singolo elemento alla volta */

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
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$tag_Area\">
                                            <button type \"submit\" value=\"VAI\"> NOME GIOCO: \"$nome_Area\" con TAG: \"$tag_Area\"</button>
                                            </form>
                                        </div>
                                            
                                        
                                    "
                                        ;
                                }
                            }
                            
                            ?>
                        
                    </div>
                    <div class="central-column">
                        <div class="postscolumnwidgettop">
                        <img class="popolareIMG" src="./IMMAGINI/post_popolari.png">
                        </div>
                            <div class="postscolumnwidgetbody">
                            
                                <?php
                                include("../visualizzazionePost.php");
                                ?>

                            </div>
                        
                    </div>
                        <div class="rightcolumnwidget">
                            <div class="rightcolumnwidgetbody">
                                <div class="rightcolumnwidgettop">

                                </div>
                                <?php
                                if(isset($_SESSION['ID'])){ /* se è settato l'id dell'utente allora visualizza il tasto per la creazione post. Altrimenti visualizza solo la scritta di crea post */
                                    echo"<a href='../creazionePost.php'>
                                
                                    <div class='card'>

                                    <img class='creaIMG' src='./IMMAGINI/creazionePostStelle.png'>
                                        <p class='testo_Creapost'>Crea nuovi post e condividi le tue opinioni con i membri del forum ma, mi raccomando, attento alle regole.</p>
                                    </div>
                                    </a>";
                                }
                                else{
                                    echo"
                                    <div class='card'>

                                    <img class='creaIMG' src='./IMMAGINI/creazionePostStelle.png'>
                                        <p class='testo_Creapost'>Crea nuovi post e condividi le tue opinioni con i membri del forum ma, mi raccomando, attento alle regole.</p>
                                    </div> ";
                                }

                                /* contatoreNotificheUtente mostra il numero di notifiche non lette internamente a una div */
                                    if(isset($_SESSION['ruolo'])){ 
                                        echo"<a href='VISUALIZZAZIONE NOTIFICHE.php'>";
                                        include("../ContatoreNotificheDiUtente.php"); 
                                        echo"</a>";
                                    }
                                ?>


                                <div class="card">
                                    <img class="rulesIMG" src='./IMMAGINI/zona_rules.png'>
                                    <p class="desc"> Attento alle nostre regole, &egrave; importante che vengano rispettate nel forum per postare buoni contenuti.</p>
                                </div>
                            </div>
                        </div>
            
            </div>
        </div>
    </body>
</html>


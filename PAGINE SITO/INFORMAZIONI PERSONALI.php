<!-- La pagina di informazioni personali é divisa in 2 parti fondamentali: Parte superiore e parte inferiore, a sua volta suddivisa in 3 colonne.
La parte superiore della pagina presenta l'immagine del sito cliccabile, che, se cliccata, rimanda alla home del sito, e 4 scritte: nel caso in cui l'utente non sia loggato, esse si suddividono in HOME (che rimanda alla
gome del sito), SEARCH (che rimanda alla pagina di ricerca), LOGIN (che rimanda alla pagina di LOGIN) e SIGN-UP(che rimanda alla pagina di registrazione). Le ultime due cambiano nel caso l'utente sia loggato, 
e quindi se é settata una sessione, in quanto la scritta di LOGIN viene sostituita dal'username dell'utente loggato (che se cliccata rimanda alla pagina personale) e dal tasto di logout che 
cancella la sessione corrente. Tutti questi casi sono applicati tramite una href che fa riferimento alla pagina di riferimento.
La parte inferiore della pagina presenta, nella colonna di sinistra presenta l'inclusione dello script della visualizzazione dei preferiti e, suddivisi da una linea orizzontale lo script della visualizzazione delle
aree che sono possibili da aggiungere ai preferiti. La colonna centrale include lo script della visualizzazione delle informazioni personali e, quella di destra presenta una div cliccabile che rimanda alla pagina 
delle informazioni personali; se invece, l'utente che sta utilizzando il sito é un ADMIN , allora viene sbloccata una seconda div cliccabile che apre la pagina di moderazione dell'ADMIN-->





<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
    <PHP> <link rel="stylesheet" type="text/css" href="CSS/MENUPROVA.css"></head> <PHP>
        <title>PAGINE PERSONALE</title>
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
                            session_start();
                            if(isset($_SESSION['ruolo'])){
                                $nomeDaVisualizzare=$_SESSION['username'];
                                echo"<li> <a id='AreaPersonale' title='Area Personale' href='PAGINA PERSONALE.php' style = 'font-family:Comic Sans MS; color:#490086;text-decoration: none;
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
                
                            include("CSS/MENUPROVA.css");

                            include('../visualizzazionePreferiti.php');

                            echo"<hr>";

                            include('../VisualizzazioneAreeDaAggiungereAPreferitiUtente.php');

                            
                            ?>
                        
                    </div>
                    <div class="central-column">
                        <div class="postscolumnwidgettop">
                        <img class="infoIMG" src="./IMMAGINI/InfoTue.png">
                        </div>
                            <div class="postscolumnwidgetbody">
                            
                                <?php
                                include("../VisualizzazioniInformazioniPersonaliUtente.php");
                                ?>

                            </div>
                        
                    </div>
                        <div class="rightcolumnwidget">
                            <div class="rightcolumnwidgetbody">
                                <div class="rightcolumnwidgettop">

                                </div>
                                <a href="INFORMAZIONI PERSONALI.php">
                                <div class="card">

                                   <img class="infoTueIMG" src='./IMMAGINI/InfoTue.png'>
                                    <p class="testo_Creapost">Clicca qui per visualizzare le tue informazioni personali</p>

                                </div>
                                </a>
                            <?php

                                if($_SESSION['ruolo']==100){
                                    ?>
                                   
                                <a href="PAGINA ADMIN.php">
                                <div class="card">

                                    <p class="testo_Creapost">Vai alla tua pagina di moderazione ADMIN</p>

                                </div>
                                </a>

                                    
                                <?php 
                                }
                                ?>
                            
                            </div>
                        </div>
            
            </div>
        </div>
    </body>
</html>


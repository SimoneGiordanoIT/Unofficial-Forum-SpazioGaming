<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
    <PHP> <link rel="stylesheet" type="text/css" href="CSS/MENUPROVA.css"></head> <PHP>
        <title>MODERAZIONE ADMIN</title>
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
                         
                            </div>
                            
                
                            <div class='card'>
                                            
                            <img class='utentiBannati' src='./IMMAGINI/utentiBannati.png'>
                            <br>
                            <br>
                            <?php
                            
                            include("../UtentiBannatiSbannabili.php");
                            echo" </div>";

                                        
                                        ?>
                        
                    </div>
                    <div class="central-column">
                        <div class="postscolumnwidgettop">
                        </div>
                            <div class="postscolumnwidgetbody">
                            
                                <?php
                                include("../visualizzazioneAreeDaRendereInvisibiliADMIN.php");
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

                                    

                                    echo"
                                    <div class='card'>
                                    <img class='promozioniDeputy' src='./IMMAGINI/promozioneDeputy.png'>
                                    <br>
                                    <br>
                                    ";

                                    include("../promozioneDeputyMod.php");
                                    echo"</div>";
                                }


                            ?>
                            </div>
                        </div>
            
            </div>
        </div>
    </body>
</html>


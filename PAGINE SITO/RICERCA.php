<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    
    <head>
    <PHP> <link rel="stylesheet" type="text/css" href="CSS/MENUPROVA.css"></head> <PHP>
        <title>POST AREA</title>
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
                
                            include("CSS/MENUPROVA.css");

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
                                include("../RICERCA script.php");
                                ?>

                            </div>  
                    </div>

                    <div class="rightcolumnwidget">
                            <div class="rightcolumnwidgetbody">
                                <div class="rightcolumnwidgettop">

                                </div>
                                <?php
                                if(isset($_SESSION['ID'])){
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


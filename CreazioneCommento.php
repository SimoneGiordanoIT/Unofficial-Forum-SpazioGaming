<?php

/* Lo script si occupa di gestire la creazione di un commento. Inizialmente viene effettuato il controllo su add, ovvero si verifica che sia stata impostato, successivamente viene memorizzato l'id del post che si è
commentato, si memorizza, se presente, l'id del commento madre che abbiamo commentato, il tag primario che il commento eredita dal post, e l'id dell'ultimo commento sottomesso, tramite questo ultimo campo potremmo
determinare l'id del nuovo commento, da assegnare successivamente, incrementando di uno il valore di ID_COMM; infine viene impostata la variabile voto (al nuovo commento) a zero. Successivamente si accede al file post.xml, si scorrono gli elementi
e si verifica se l'id del post che si è commentato coincide con l'id memorizzato nel file xml; se il controllo da esito positivo allora verrà creata la struttura commento con i suoi campi, verranno appesi al suo
interno, e successivamente, se l'utente ha selezionato più tag aggiuntivi per il nuovo commento, questi verranno aggiunti mediante la creazione di nuovi campi nella struttura commento. Inoltre viene controllato se
la variabile di ID commento superiore è impostata, in caso affermativo anche tale ID verrà aggiunto con la creazione di un campo a lui dedicato nella struttura. Infine viene aggiunta la nuova struttura nel file xml
e viene salvato. 
Successivamente viene aperto il file commenti.xml per aggiungere al suo interno la struttura dati linker che conterrà ID del creatore del nuovo commento, l'ID del post in cui è stato inserito il commento, e l'id 
del commento (la struttura sarà necessaria in futuro al momento della votazione da parte degli utenti, a quello specifico commento).
Nella parte inferiore dello script è presente la form per la creazione del nuovo commento; tra i vari campi presenti sarà visibile la checkbox al cui interno verranno stampati i tag delle aree di interesse presenti 
sul sito; tali tag potranno essere selezionati dall'utente per aggiungerli al tag primario del commento. */

session_start();
if(isset($_SESSION['ruolo'])){


if (isset($_POST['add'])){ /* se è settato l'add allora viene eseguito tutto lo script */

    $ID_POST_SPECIFICO=$_GET['ID_POST'];
    if(isset($_GET['IDMADRE'])){  /* se il commento è una risposta a una madre allora memorizza pure l'id della madre */
      $ID_SUP = $_GET['IDMADRE'];
    }
    $TAG = $_GET['TAG']; /* tag primario del commento che eredita dal post */
    $ID_COMM=$_GET['ID_ULTIMO']; /* ID dell'ultimo commento in modo da assegnare il nuovo id al commento nuovo */

    $voto=0;

    $commentatore= $_SESSION['ID']; 

    $ID_COMMENTO=$ID_COMM+1;

    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;



    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);

        $ID = $elemento->firstChild;
        $ID_Check=$ID->textContent;

        if($ID_Check==$ID_POST_SPECIFICO){ /* se gli ID coincidono allora creo il nuobo commento */

            $newComment = $doc->createElement("commento");

            $newID = $doc->createElement("ID_commento",$ID_COMMENTO);
            $newTesto = $doc->createElement("testo", $_POST['commento']);
            $newCommentatore = $doc->createElement("ID_commentatore", $commentatore);

            $newTag = $doc->createElement("tagC", $TAG);

            /* se il commento è la risposta a un commento allora aggiungo il campo ID comm superiore */
            if(isset($ID_SUP)){
              $newCom_SUP = $doc->createElement("ID_com_superiore", $ID_SUP);
            }

            $newComment->appendChild($newID);
            $newComment->appendChild($newTesto);
            $newComment->appendChild($newCommentatore);
            $newComment->appendChild($newTag);

            for($j=0;$j<sizeof($_POST['checkbox']);$j++){  /* se ci sono più tag per il post allora per ogni tag aggiunto questo viene confrontato con il tag primario e se diverso dal tag primario lo aggiunge ai tag */
              if($TAG != $_POST['checkbox'][$j]){
                
                $newTag2 = $doc->createElement("tagC", $_POST['checkbox'][$j]); 
                $newComment->appendChild($newTag2);
              }
            }

            if(isset($ID_SUP)){
              $newComment->appendChild($newCom_SUP);        
            }
            
            $elemento->appendChild($newComment);


            $doc->save("PAGINE SITO/SCHEMI/post.xml");





            $xmlString2 = "";
            foreach ( file("PAGINE SITO/SCHEMI/commenti.xml") as $node ) {
              $xmlString2 .= trim($node);
            }


            $docXML = new DOMDocument();
            $docXML->loadXML($xmlString2);

            $rootXML = $docXML->documentElement;
            $elementi2 = $rootXML->childNodes;

            $newCommentVal = $docXML->createElement("linker");

            $newID_Creatore = $docXML->createElement("ID_creatore",$commentatore);
            $newID_POST = $docXML->createElement("ID_post", $ID_POST_SPECIFICO);
            $newID_Commento = $docXML->createElement("ID_comm", $ID_COMMENTO);

            $newCommentVal->appendChild($newID_Creatore);
            $newCommentVal->appendChild($newID_POST);
            $newCommentVal->appendChild($newID_Commento);

            $rootXML->appendChild($newCommentVal);

            $docXML->save("PAGINE SITO/SCHEMI/commenti.xml");




            echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
            



        }
    }
  }
}


?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">


<head>
    <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <PHP>	<link rel="stylesheet" type="text/css" href="DB-buono/createUser.css"> <PHP>
<title>AGGIUNGI UN COMMENTO</title>
</head>

<body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">

                      <form class="form-horizontal login-form" action="<?php $_SERVER['PHP_SELF']?>" method="post">

                        <h1>Crea Commento</h1>
                        <p>Inserisci il tuo commento</p>

                        <div class="form-group relative">
                          <label for="description"><b>Descrizione</b></label>
                          <br>
                          <textarea class="form-control input-desc" type="text" rows="9" cols="70" class="form-control" name="commento" required></textarea>
                        </div>
                          <label><b>Seleziona il tag:</b></label>
                          <br>
                          <?php
                              $xmlString = "";
                              foreach ( file("PAGINE SITO/SCHEMI/AreeInteresse.xml") as $node ) {
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
                                      
                                      <input type = \"checkbox\" value = \"$tag_Area\" name = \"checkbox[]\"/>$tag_Area<br>";
                        
                                  }
                              }
                          ?>
                          
                        <div class="form-group">
                          <button class="btn btn-success btn-lg btn-block" type="submit" value="aggiungi" name="add" class="submit">Invia</button>
                          <button class="btn btn-success btn-lg btn-block" onclick="goBack()">Go back</button>
                        </div>

                      </form>
                      </div>
				          </div>
              </div>
          </div>
		</div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    <script src="./DB-buono/gradient.js"></script>
    <script>
        function goBack() {
        window.history.back();
        }
    </script>
</body>

</html>

<?php

/*Lo script gestisce la modifica della descrizione di un post. A tal scopo, si accede al contenuto del file post.xml, si memorizza in una variabile il valore dell'ID del post di interesse, e il testo da modificare
(entrambi valori passati mendiante variabili post); successivamente si scandiscono gli elementi del file ricercando il post il cui ID coincide con quello che dobbiamo modificare. Una volta trovato il post di interesse,
si imposterà il contenuto del campo testo a quello della variabile contenente il nuovo testo del post. Infine viene salvato il file, e viene caricata la pagina personale dell'utente.
Nella parte superiore dello script è presente la form mediante la quale l'utente potrà modificare il testo del post. */

?>


<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<PHP>	<link rel="stylesheet" type="text/css" href="./DB-buono/createUser.css"> <PHP>
	<title>Modifica Post</title>
</head>

<body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">

                
						<form class="form-horizontal login-form" action="<?php $_SERVER['PHP_SELF']?>" method="post">

						<div class="form-group relative">
							<label for="description"><b>Description</b></label>
							<br>
							<textarea class="form-control input-desc" type="text" rows="9" cols="70" class="form-control" name="testo" required></textarea>
						</div>

						<div class="form-group">
                <?php 

                    /* DEVO FAR IN MODO CHE VENGA PASSATO ANCHE L'ID DEL POST NELLO SCRIPT DI PHP*/

                    $ID_POST = $_POST['ID_POST'];
                    echo"<input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">";
                ?>
                <button class="btn btn-success btn-lg btn-block" type="submit" name="add">MODIFICA</button>
								<button class="btn btn-success btn-lg btn-block" type="reset">Reset</button>
								<button class="btn btn-success btn-lg btn-block" onclick="goBack()">Go back</button>
						</div>

						</form>

					</div>
				</div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>  
    <script src="./DB/gradient.js"></script>
    <script>
        function goBack() {
        window.history.back();
        }
    </script>
</body>

</html>

<?php

if (isset($_POST['add'])){
  
    $xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
      $xmlString .= trim($node);
    }

    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    $ID_POST_DA_MODIFICARE = $_POST['ID_POST'];

    
    $testo=$_POST['testo'];

    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);
        
        $ID = $elemento->firstChild;
        $ID_POST=$ID->textContent;

        if($ID_POST_DA_MODIFICARE==$ID_POST){
               
                $doc->getElementsByTagName("Testo")->item($i)->nodeValue = $testo;

        }
        
    }
    $doc->save("PAGINE SITO/SCHEMI/post.xml");
    
    header("Location: PAGINE SITO/PAGINA PERSONALE.php");

  }
    
?>

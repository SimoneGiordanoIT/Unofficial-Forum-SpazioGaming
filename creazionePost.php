<?php

/*Lo script creazionePost si occupa della creazione dei post. Inizialmente si verifica che la variabile invio sia settata, se il controllo da esito positivo, si accede al file post.xml, e si scandiscono i suoi
elementi, al fine di trovare l'ultimo post e memorizzare il suo ID. Una volta memorizzato il suo ID, si potrà definire l'ID del nuovo post che sarà pari al valore dell'ID dell'ultimo post incrementato di 1. 
Successivamente vengono inizializzate le variabili necessarie per la creazione dei campi del nuovo post, e viene creata una nuova struttura dedicata al nuovo post con i suoi campi. Il valore assegnato ai campi del nuovo
post saranno quelli delle variabili precendetemente inizializzate. Inoltre viene verificata l'eventuale presenza di attachment nel post, controllando sia che la variabile attach non sia impostata, sia che non è stato fatto
l'upload del file immagine da inserire nel post; se il controllo da esito positivo allora il valore del campo attachment sarà posto a zero. Altrimenti se il controllo da esito negativo allora verrà memorizzato il
percorso dati del file e il nome dell'immagine diverrà tag del post e id del post (es: LOL9); verrà memorizzato il file nel percorso dati di riferimento e verrà assegnato al campo attachment il nome del file (tag 
del post e ID del post). Infine verranno appesi i campi creati alla struttura, e verrà appesa la struttura nel file xml.
In fondo al documento è presenta la form per la creazione del post in cui l'utente può inserire i campi necessari per la creazione del post. */

session_start();

if (isset($_POST['invio'])){ 
	
	$xmlString = "";
	foreach ( file("PAGINE SITO/SCHEMI/post.xml") as $node ) {
	$xmlString .= trim($node);
	}

	$doc = new DOMDocument();
	if (!$doc->loadXML($xmlString)) {
  	die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
	}

    $root = $doc->documentElement;
    
	$elementi = $root->childNodes;
	

    for ($i=0; $i<$elementi->length; $i++) { /* ciclo for per trovare il valore numerico dell'ultimo ID-post */

      $elemento = $elementi->item($i);

      $ID = $elemento->firstChild;
      $numID = $ID->textContent;
    
    }

    $autore = $_SESSION['ID']; /* assegna i valori nuovi ai campi del post  */
    $visibile = 1;
    $valutazionePost = 0;
	$newNumID=$numID + 1; 

    $tag=$_POST['tag'];
    $titolo=$_POST['titolo'];
	$testo=$_POST['testo'];
	

	
	

	// creazione di un nuovo <record>
    $newPost = $doc->createElement("post"); /* crea la nuova struttura dati e assegna i valori delle variabili inizializzate prima */

    $newID = $doc->createElement("ID",$newNumID);
	$newTag = $doc->createElement("tag", $tag);
	$newIDCreat = $doc->createElement("ID_Creat", $autore);
	$newDate = $doc->createElement("Data_creaz", date("d/m/y"));
	$newTitle = $doc->createElement("Titolo", $titolo);
	$newText = $doc->createElement("Testo", $testo);



	if (!isset($_FILES['attach']) || !is_uploaded_file($_FILES['attach']['tmp_name'])){
		$newAttach = $doc->createElement("Attachment", 0); /* se l'att non c è viene messo a zero il suo valore, altrimenti viene messo il suo valore a quello fornito dall'utente */
	}    
	else{

			/* memorizzo il percorso dati del file, e il nome dell'img sarà tag del post e id del post  */

			$uploaddir = 'PAGINE SITO/ATTACHMENT/'. $tag . $newNumID .'.png'; 

			/*Memorizzo il file in una variabile temporanea e assegna al file un nome temp*/ 
			$userfile_tmp = $_FILES['attach']['tmp_name'];

			move_uploaded_file($userfile_tmp, $uploaddir);

			$newAttach = $doc->createElement("Attachment", $tag . $newNumID);

		
	}

	$visibilita = $doc->createElement("visibile", $visibile);	
    

/* aggiunge i nuovi campi e la nuova struttura */
	$newPost->appendChild($newID);
    $newPost->appendChild($newTag);
	$newPost->appendChild($newIDCreat);
	$newPost->appendChild($newDate);
	$newPost->appendChild($newTitle);
	$newPost->appendChild($newText);
	
	$newPost->appendChild($newAttach);

    $newPost->appendChild($visibilita);

	
	$root->appendChild($newPost);

	$doc->save("PAGINE SITO/SCHEMI/post.xml");

	header("Location: PAGINE SITO/LAHOME.php");

	

	
}

?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<PHP>	<link rel="stylesheet" type="text/css" href="./DB-buono/createUser.css"> <PHP>
	<title>Aggiunta Post</title>
</head>

<body class="login">
    <div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">
                
						<form class="form-horizontal login-form" action="<?php $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
						<h1>Creazione post</h1>

						<div class="form-group relative">
							<label for="titolo"><b>Titolo</b></label>
							<br>
							<input class="form-control input-lg" type="text" placeholder="Inserire titolo" name="titolo" required>
						</div>

						<div class="form-group relative">
							<label for="tag"><b>Tag</b></label>
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

								$tag_aree=array();
								
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

										array_push($tag_aree,$tag_Area);

									}
								}

								echo"<div class='box'>
									<select name=\"tag\">";
										foreach($tag_aree as $tag):
										echo '<option value="'.$tag.'">'.$tag.'</option>';
										endforeach;
									echo"</select>
										</div>";
								
								?>
							





						</div>

						<div class="form-group relative">
							<label for="attachment"><b>Attachment</b></label>
							<br>
							<input  type="file"  name="attach">
						</div>

						<div class="form-group relative">
							<label for="description"><b>Description</b></label>
							<br>
							<textarea class="form-control input-desc" type="text" rows="9" cols="70" class="form-control" name="testo" required></textarea>
						</div>

						<div class="form-group">
                                <button class="btn btn-success btn-lg btn-block" type="submit" name="invio">Crea</button>
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
    <script src="./DB-buono/gradient.js"></script>
    <script>
        function goBack() {
        window.history.back();
        }
    </script>
</body>

</html>
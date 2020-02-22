<?php

/*Lo script si occupa della creazione di un'area di interesse; la creazione può avvenire in due modi: l'area viene creata a seguito della richiesta di un utente, pertanto bisognerà tenere traccia di tale utente per
promuoverlo a moderatore della nuova area; l'area viene creata per volere dell'admin. Inizialmente si verifica che la sessione sia quella di un admin, successivamente si verifica che sono state impostate le variabili
post relative al tag dell'area e al suo nome, e che sia stato premuto il tasto di invio nella form; subito dopo viene effettuato il controllo sulla variabile post contenente l'id dell'utente (quindi se si proviene
da un messaggio di richiesta da parte dell'utente). Se il controllo da esito positivo, si accede al file ModeratoreAreaDeputy.xml, per creare al suo interno la struttura moderatore per il nuovo utente con i suoi
campi, si inserisce la nuova struttura e si salva il file; infine si effettua una query di update sul DB per impostare il ruolo di quel determinato utente a 10, così da renderlo moderatore a tutti gli effetti.
Per il passo successivo, ovvero la creazione dell'area, si accede al file AreeInteresse.xml, si effettua un ciclo for per determinare il valore dell'ultimo ID dell'ultimo area, in modo da incrementarne il valore
e assegnare il nuovo ID alla nuova area. Insieme al nuovo ID, viene inizializzata anche la variabile di flag_attivo e posta a 1; il valore di tale variabile verrà utilizzato al momento della creazione dei campi
dell'area per indicare che l'area sarà visibile. Infine viene creata la nuova struttura, ovvero il nuovo gioco, con i suoi campi, viene appesa nel file e viene salvato il file. Nella parte finale dello script è 
presente la form per la creazione dell'area */

session_start();
if($_SESSION['ruolo']==100){


	if (isset($_POST['add'])){  
		$tag=$_POST['tag'];
		$nome=$_POST['nome'];


		/* NEL CASO IN CUI PROVENGO DA UN MESSAGGIO CHE MI HA CHIESTO DI AGGIUNGERE UN AREA RENDO L'UTENTE CHE ME LO HA CHIESTO, MODERATORE DI QUELL'AREA*/

		if(isset($_POST['ID_UTENTE'])){

			$xmlString = "";
			foreach ( file("PAGINE SITO/SCHEMI/ModeratoreAreaDeputy.xml") as $node ) {
			$xmlString .= trim($node);
			}
	
			$doc = new DOMDocument();
			if (!$doc->loadXML($xmlString)) {
			die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
			}
	
			$root = $doc->documentElement;

			
			

			$newModeratore = $doc->createElement("mod_dep");    /* crea la struttura moderatore e creo i suoi campi con i valori inseriti (riga 30 33) */

		$newID_MOD = $doc->createElement("ID_MOD",$_POST['ID_UTENTE']);
		$newTAG = $doc->createElement("TAG_AREA", $tag);
		


		$newModeratore->appendChild($newID_MOD); /* inserisce i nuovi campi compilati */
		$newModeratore->appendChild($newTAG);
		
		
		$root->appendChild($newModeratore); /* inserisce la nuova struttura */

		$doc->save("PAGINE SITO/SCHEMI/ModeratoreAreaDeputy.xml");
	
			
		} /* TERMINA L'IF IN CUI SI PROMUOVE L'UTENTE */
		
		$xmlString = "";
		foreach ( file("PAGINE SITO/SCHEMI/AreeInteresse.xml") as $node ) {
		$xmlString .= trim($node);
		}

		$doc = new DOMDocument();
		if (!$doc->loadXML($xmlString)) {
		die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
		}

		$root = $doc->documentElement;
		
		$elementi = $root->childNodes;

		for ($i=0; $i<$elementi->length; $i++) { /* for per trovare il valore dell'ultimo ID e incrementarlo per assegnarlo a una nuova area. sopra non è necessario questo passaggio perchè l'id viene fornito 
												dalla session */

		$elemento = $elementi->item($i);

		$ID = $elemento->firstChild;
		$numID = $ID->textContent;
		
		}

		$flag_attivo = 1;
		$newNumID=$numID + 1;

		
		// creazione di un nuovo <record>
		$newArea = $doc->createElement("gioco"); 

		$newID = $doc->createElement("ID",$newNumID);
		$newNome = $doc->createElement("nome", $nome);
		$newTag = $doc->createElement("tag", $tag);
		$newFlag = $doc->createElement("flag_attivo", $flag_attivo);


		$newArea->appendChild($newID);
		$newArea->appendChild($newNome);
		$newArea->appendChild($newTag);
		$newArea->appendChild($newFlag);
		
		$root->appendChild($newArea);

		$doc->save("PAGINE SITO/SCHEMI/AreeInteresse.xml");

		header("Location: PAGINE SITO/LAHOME.php");

	}
	
	
}else{
	print("NON POSSIEDI I REQUISITI PER AGGIUNGERE L'AREA");  /* else è in riferimento all'if della riga 3, la stampa avverrà se l'utente prova ad aprire questa pagina senza essere admin*/ 
}

?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./DB-buono/createUser.css">
		<title>Aggiunta Area di Interesse</title>

</head>

<body class="login">
	<div class="radial-gradient"></div>
        <div class="container">
            <div class="login-container-wrapper clearfix">
                <div class="tab-content">
                    <div class="tab-pane active" id="login">

						<form class="form-horizontal login-form" action="<?php $_SERVER['PHP_SELF']?>" method="post">

							<h1>Creazione area</h1>

							<div class="form-group relative">
							<label for="tag"><b>Tag</b></label>
							<input class="form-control input-lg" type="text" placeholder="Inserire tag" name="tag" required><br>
							</div>

							<div class="form-group relative">
							<label for="nomeG"><b>Nome gioco</b></label>
							<input class="form-control input-lg" type="text" placeholder="Inserire nome" name="nome" required><br>
							</div>

							<?php 

								/* DEVO FAR IN MODO CHE VENGA PASSATO ANCHE L'ID DEL POST NELLO SCRIPT DI PHP*/
								if(isset($_POST['ID_UTENTE'])){
									$ID_UTENTE = $_POST['ID_UTENTE'];    		/* serve quando l'utente fa rischiesta di aggiungere l'area e quindi il suo id serve per farlo diventare moderatore */
								echo"<input type='hidden' name='ID_UTENTE' value='$ID_UTENTE'>";  /* la echo serve per passare la variabile ID utente alla sezione di php, poichè essendo var locale andrebbe distrutta non
																										al termine della sezione html */

								}
								
							?>

							<div class="form-group">
							<button class="btn btn-success btn-lg btn-block" type="submit" value="aggiungi" name="add" class="submit">Invio</button>
							<button class="btn btn-success btn-lg btn-block" type="reset" class="cancel">Cancel</button>
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
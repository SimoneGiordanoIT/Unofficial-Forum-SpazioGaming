<?php

/*Lo script si occupa della valutazione dei commenti, in particolare crea un nuovo voto al commento di un utente e aggiorna successivamente la reputazione dell'utente che ha ricevuto il voto al proprio commento.
Nella prima parte dello script si gestisce la creazione di una nuova valutazione al commento; si accede, pertanto, al contenuto del file commenti.xml, e si memorizzano in apposite variabili le informazioni necessarie
per l'aggiunta del voto al post e per l'aggiornamento della reputazione dell'utente. Successivament si scorrono gli elementi del documento e i campi di ognuno di esso, verificando che ci sia corrispondenza tra 
l'ID del post contenuto nel file xml e l'ID del post di interesse in cui è presente il commento; se il controllo da esito positivo, si controlla che ci sia corrispondenza tra l'ID del commento nel file xml e l'ID
del commento di interesse. Se c'è corrispondenza allora viene creata la struttura dati dedicata al nuovo voto con i campi, rispettivamente ID dell'utente votante e il voto aggiunto. Infine si appendono i campi nella
struttura, si appende la struttura e si salva il file.
Nella seconda parte dello script si gestisce l'aggiornamento della reputazione dell'utente; si accede al file commenti.xml e si effettua la connessione al DB. Si scorrono gli elementi del documento con i loro campi,
e si verifica che l'ID dell'utente creatore del commento nel file xml coincida con l'ID dell'utente creatore di interesse. Se la condizione è soddisfatta allora si prelevano di volta in volta tutte le votazioni
a quel commento e gli ID degli utenti che hanno votato; per ogni utente si preleva dal DB la sua reputazione e si calcola il voto pesato (voto*reputazione utente) e il numeratore e il denominatore della media pesata.
Una volta effettuato il calcolo, viene effettuata una query di update in cui si aggiorna la reputazione dell'utente creatore del commento votato.*/


session_start();

if (isset($_POST['VOTO'])){
	
	$xmlString = "";
	foreach ( file("PAGINE SITO/SCHEMI/commenti.xml") as $node ) { /* nella prima parte creo la nuova valutazione( aggiungendo quindi al linker 
		 															di riferimento voto e chi ha votato) */
	$xmlString .= trim($node);
	}

	$doc = new DOMDocument();
	if (!$doc->loadXML($xmlString)) {
  	die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
	}

    $root = $doc->documentElement;
    
	$elementi = $root->childNodes;
	
	$ID_POST=$_POST['ID_POST'];
	$ID_COMM=$_POST['ID_COMM'];
	$voto=$_POST['voti'];
	$ID_votante=$_SESSION['ID'];

	$ID_CREATORE_COMMENTO=$_POST['ID_COMMENTATORE'];


	for ($n=0; $n<$elementi->length; $n++) {
		$elemento = $elementi->item($n);
		
		$ID_creat = $elemento->firstChild;
		
		$ID_POST_RIF = $ID_creat->nextSibling;
		$ID_POST_RIFERIMENTO = $ID_POST_RIF->textContent;

		if($ID_POST_RIFERIMENTO == $ID_POST){

			$ID_COMM_RIF = $ID_POST_RIF ->nextSibling;
			$ID_COMM_RIFERIMENTO = $ID_COMM_RIF->textContent;

			if($ID_COMM_RIFERIMENTO == $ID_COMM){

				$newValutazioneComm = $doc->createElement('voto');

				$Newvoto = $doc->createElement('votoDato', $voto);
				$New_IDVotante = $doc->createElement('ID_votante',$ID_votante);
				

				$newValutazioneComm->appendChild($Newvoto);
				$newValutazioneComm->appendChild($New_IDVotante);

				$elemento->appendChild($newValutazioneComm);

				$doc->save("PAGINE SITO/SCHEMI/commenti.xml");

			}
		}
	}


	/*Ora devo aggiornare la reputazione dell'utente che ha creato il commento che è stato appena votato*/

	$xmlString2 = "";
	foreach ( file("PAGINE SITO/SCHEMI/commenti.xml") as $node ) {  /* nella seconda parte riapro il documento aggiornato con la nuova valutazione inserita prima. Si stabilisce la connessione col db per prelevare
																	le reputazioni degli utenti votanti*/
      $xmlString2 .= trim($node);
    }

    $docXML = new DOMDocument();
    $docXML->loadXML($xmlString2);

    $rootCOMM = $docXML->documentElement;
	$elementiCOMM = $rootCOMM->childNodes;
	
	$APPOGGIO_VOTAZIONE = 0;
    $APPOGGIO_REPUTAZIONE = 0;

	include("DB-buono/connection.php");
	
	for ($l=0; $l<$elementiCOMM->length; $l++) {

		$elementoCOMM = $elementiCOMM->item($l);

		$ID_Creatore = $elementoCOMM->firstChild;			
		$ID_Creatore_comm = $ID_Creatore->textContent;
		print("ECCO1");

   	/* trovo il creatore al commento e verifico che coincida con quello passato dal post. Trovo il voto al commento e l'id del votannte. Prelevo la reputazione del votante e la uso per calcolare la media */

		if($ID_Creatore_comm==$ID_CREATORE_COMMENTO){
			

			$listaValutazioni = $elementoCOMM->getElementsByTagName("voto");
			$numValutazioni = $listaValutazioni ->length;

			if($numValutazioni !=0 ){
				print("ECCO2");

				for($p=0 ; $p<$numValutazioni ; $p++){
					print("ECCO3");

					$valutazione =  $listaValutazioni -> item($p);


					$VOTO_COMM=$valutazione->firstChild->textContent;

					$ID_Votante_Commento=$valutazione->lastChild->textContent;

					$sql = "SELECT *
					FROM $db_tab_utente 
					WHERE userID = \"$ID_Votante_Commento\"
				";

					if (!$result = mysqli_query($mysqliConnection, $sql)) {
						printf("Errore nella query di ricerca reputazioni\n");
					exit();
					}
					
					$row = mysqli_fetch_array($result);

					$VOTO_PESATO= $row['reputazione'] * $VOTO_COMM;

					$APPOGGIO_REPUTAZIONE= $APPOGGIO_REPUTAZIONE + $row['reputazione']; /* denominatore della media pesata */

					$APPOGGIO_VOTAZIONE=$APPOGGIO_VOTAZIONE + $VOTO_PESATO; /* numeratore della media pesata */

					


				}
			}
		}
	}

			$REPUTAZIONE=$APPOGGIO_VOTAZIONE / $APPOGGIO_REPUTAZIONE; /* calcolo della media e aggiorno la reputazione del creatore del commento */


			$query ="   UPDATE $db_tab_utente 
						SET reputazione=('$REPUTAZIONE')
						WHERE userID=('$ID_CREATORE_COMMENTO')
						";
			
			if (!$result = mysqli_query($mysqliConnection, $query)) {
					printf("Errore nella query di salvataggio reputazione finale\n");
				exit();
				}

					

				

}

echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";


?>
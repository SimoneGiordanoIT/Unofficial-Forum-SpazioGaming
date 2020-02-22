<?php   

/*Lo script si occupa della promozione di un utente a deputy. A tal scopo, si memorizza inizialmente l'ID del'utente creatore del post da promuovere, e successivamente si interroga il DB per eseguire una query di
update in cui si aggiorna il ruolo dell'utente a 15, ovvero l'utente diviene deputy moderator. Successivamente si accede al contenuto del file moderatoreAreaDeputy.xml e si memorizzano in apposite variabili
l'ID del moderatore passato dalla session, e l'ID dell'utente creatore del post che si vuole promuovere; il passo successivo sarà scorrere gli elementi del documento per determinare la corrispondenza tra l'ID
del moderatore passato dalla session e l'ID del moderatore presente nel file. Infine, se c'è corrispondenza, si aggiungerà a quel moderatore un nuovo deputy, creando un nuovo elemento nella struttura e appendendolo.
Il file viene salvato e viene caricata la pagina precedente a quella corrente. */

session_start();

    $ID_CREATORE_POST=$_POST['ID_CREATORE'];

    include("DB-buono/connection.php");

    $query ="   UPDATE $db_tab_utente 
				SET ruolo=\"15\"
				WHERE userID=\"$ID_CREATORE_POST\"
				";
	
	if (!$result = mysqli_query($mysqliConnection, $query)) {
			printf("Errore nella query di salvataggio reputazione finale\n");
		exit();
		}
		
		$xmlString = "";
		foreach ( file("PAGINE SITO/SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
		  $xmlString .= trim($node);
		}
	
	
		$doc = new DOMDocument();
		$doc->loadXML($xmlString);
	
		$root = $doc->documentElement;
		$elementi = $root->childNodes;
	
		$ID_MODERATORE_PROMOTORE = $_SESSION['ID'];

		$ID_UTENTE_DIVENTATO_DEPUTY = $_POST['ID_CREATORE'];
	
		for ($i=0; $i<$elementi->length; $i++) {
		  
		  $elemento = $elementi->item($i);
	
			$ID = $elemento->firstChild;
			$ID_Check_Mod = $ID->textContent;
	
			if($ID_Check_Mod==$ID_MODERATORE_PROMOTORE){
	
	
				$new_MOD_DEP = $doc->createElement("ID_DEP",$ID_UTENTE_DIVENTATO_DEPUTY);
				
				$elemento->appendChild($new_MOD_DEP);

				$doc->save("PAGINE SITO/SCHEMI/moderatoreAreaDeputy.xml");

				echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
				
			}
		}
        
?>
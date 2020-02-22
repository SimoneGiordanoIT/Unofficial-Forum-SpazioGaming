<?php   

/*Lo script gestisce la promozione di un utente a moderatore. Per far ciò inizialmente si memorizzano in apposite variabili l'ID dell'utente da promuovere e il tag dell'area di cui diventerà moderatore passati 
mediante variabile post. Successivamene si interroga il DB per aggiornare il ruolo dell'utente a 10, rendendolo quindi un moderatore; il passo successivo, sarà accedere al file moderatoreAreaDeputy.xml, creare
una nuova struttura con i rispettivi campi in cui memorizzare l'ID del nuovo moderatore e la sua area di moderazione. Infine i campi vengono appesi alla struttura, viene appesa la struttura nel file. Si salva il
file xml e si carica la pagina precedente a quella corrente. */


session_start();

    $ID_CREATORE_POST=$_POST['ID_CREATORE'];

    $TAG_AREA = $_POST['TAG_AREA'];

    include("DB-buono/connection.php");

    $query ="   UPDATE $db_tab_utente 
				SET ruolo=\"10\"
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
	
		$newModeratore = $doc->createElement("mod_dep");

		$newID_MOD = $doc->createElement("ID_MOD",$ID_CREATORE_POST);
		$newTAG = $doc->createElement("TAG_AREA", $TAG_AREA);
		


		$newModeratore->appendChild($newID_MOD);
		$newModeratore->appendChild($newTAG);
		
		
		$root->appendChild($newModeratore);

        $doc->save("PAGINE SITO/SCHEMI/ModeratoreAreaDeputy.xml");
        echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";
        
        
?>
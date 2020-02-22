<?php

/*Lo script gestisce i messaggi che il moderatore invia a un utente in relazione alla modifica di un suo post. Per far ciò, si verifica che l'add sia settato, e si accede al contenuto del file gestioneMessaggiModeraPost.xml
Si memorizzano in apposite variabili, i valori di ID del post da modificare e ID dell'utente proprietario del post, passati entrambi mediante variabile post. Successivamente viene creato una nuova struttura dati 
dedicata al nuovo messaggio e contenente tutti i campi necessari; si assegnano i valori ai campi e in particolare viene posto il flag di lettura pari a 1, poichè il messaggio appena creato deve ancora essere letto
dall'utente. Infine si appendono i campi all'interno della struttura, si appende la nuova struttura nel file. Viene salvato il file xml e ricaricata la pagina precedente a quella corrente.
Nella parte inferiore dello script è presente la form tramite la quale il moderatore inserisce il testo del messaggio e contatta l'utente.  */

if(isset($_POST['add'])){
    session_start();

    $xmlString = "";
	foreach ( file("PAGINE SITO/SCHEMI/gestioneMessaggiModeraPost.xml") as $node ) {
	$xmlString .= trim($node);
	}

	$doc = new DOMDocument();
	if (!$doc->loadXML($xmlString)) {
  	die ("Errore nel salvataggio del file XML nel salvataggio nel doc\n");
	}

    $root = $doc->documentElement;
    
    $elementi = $root->childNodes;

    $ID_POST = $_POST['ID_POST'];
    $ID_UTENTE= $_POST['ID_UT'];

    $newMessage=$doc->createElement("mex");

    $newID_UT= $doc->createElement("ID_UT",$ID_UTENTE);
    $newID_MOD= $doc->createElement("ID_MOD",$_SESSION['ID']);
    $newID_POST= $doc->createElement("ID_POST",$ID_POST);
    $newMex = $doc->createElement("testoMess",$_POST['testo']);
    $newFlag = $doc->createElement("flag_lett",'1');

    $newMessage->appendChild($newID_UT);
    $newMessage->appendChild($newID_MOD);
    $newMessage->appendChild($newID_POST);
    $newMessage->appendChild($newMex);
    $newMessage->appendChild($newFlag);

    $root->appendChild($newMessage);

    $doc->save("PAGINE SITO/SCHEMI/gestioneMessaggiModeraPost.xml");

    echo"<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
            <script>
                
                window.history.back();
                
            </script>";


}
    
?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<body>
    <div class="container">

        <form action="<?php $_SERVER['PHP_SELF']?>" method="post">

            <input type="text" placeholder="testo" name="testo"><br>


            <?php 

                    /* DEVO FAR IN MODO CHE VENGA PASSATO ANCHE L'ID DEL POST e DELL UTENTE NELLO SCRIPT DI PHP*/

                    $ID_POST = $_POST['ID_POST'];
                    $ID_UT = $_POST['ID_UT'];
                    echo"<input type=\"hidden\" name=\"ID_POST\" value=\"$ID_POST\">
                         <input type=\"hidden\" name=\"ID_UT\" value=\"$ID_UT\">
                         ";
                ?>

            <button type="submit" value="aggiungi" name="add" class="submit">SEND</button>

        </form>
    </div>
</body>

</html>
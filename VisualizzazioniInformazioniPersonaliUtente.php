<?php

/*Lo script si occupa di stampare le informazioni personali di un utente, a cui si può accedere o cliccando sul nome del creatore di un post, oppure dalla casella di info personali. Inizialmente si verifica che è 
impostata la variabile di get contenente l'ID del creatore del post di cui vogliamo visualizzare le info. Successivamente si memorizza in una apposita variabilel'ID e si interroga il DB prelevando le informazioni
dell'utente con tale ID e si stampano in una div (nome, reputazione, email, username e se l'utente è stato bannato o meno). Successivamente si verifica se l'utente è un moderatore o un deputy; se il controllo da esito
positivo allora si accede al contenuto del file moderatoreAreaDeputy.xml e si scorrono i suoi elementi prelevando il contenuto di alcuni campi e controllando che l'ID del creatore del post coincida con l'ID del 
moderatore memorizzato nel file. Se il controllo da esito positivo e si stampa il nome dell'area di moderazione di quel moderatore e il bottone che rimanda all'area. Altrimenti se il controllo da esito negativo allora 
si preleva la lista degli ID dei deputy e si controlla per ognuno che ci sia corrispondenza con l'ID del creatore del post; se il controllo da esito positivo allora viene stampata l'area di cui è deputy-moderator e il
bottone che rimanda all'area. Se l'utente non è ne deputy ne moderatore, allora si verifica che è un admin; se admin, verrà stampato il messaggio che indicherà che l'utente è appunto un admin. 
Se invece si è fatto accesso alle proprie informazioni, tramite la casella delle info personali, si verifica che l'ID dell'utente coincida con quello passato dalla variabile di session. Se la condizione è soddisfatta
allora si interroga il DB per prelevare le info dell'utente che verranno stampate. Successivamente si verifica che l'utente è un moderatore o un deputy, in tal caso si stamparenno le sue aree di moderazione e il
bottone che rimanda all'area.*/

    if(isset($_GET['ID_CREAT'])){ /* ci sono due modi per arrivare alle info di un utente o accedo alle info di un utente creatore di un post oppure alle proprie info personali */
        $ID_CREAT=$_GET['ID_CREAT'];  /* vengono gestite le info dell'utente creatore e nel caso stampate le aree di cui l'utente è moderatore */

        include("../DB-buono/connection.php");

    $sql = "SELECT *
            FROM $db_tab_utente 
            WHERE userID = \"$ID_CREAT\"
		";

			if (!$result = mysqli_query($mysqliConnection, $sql)) {
				printf("Errore nella query di ricerca reputazioni\n");
			exit();
			}
			
        $row = mysqli_fetch_array($result);

        $nomeUtente= $row['nome'];
        $reputazione= $row['reputazione'];
        $email= $row['email'];
        $username= $row['username'];
        $ruolo = $row['ruolo'];

        $ban = $row['ban'];
        
        
        echo"<div class='card'>
                        

                        <p class='text'>La reputazione di $nomeUtente &egrave;: $reputazione</p>

                        <p class='text'>La mail di $nomeUtente &egrave;: $email</p>
                        
                        <p class='text'>Il suo username &egrave;: $username</p>";
                        
                        if($ban == 1){
                            echo"<p class='text'>L'utente $username &egrave; stato Bannato</p>";
                        }
                        
                    
        echo"</div>";


        if($ruolo==10 || $ruolo==15){
            echo"<div class='card'>
                          
            <h1 class='titolo'>Queste sono le sue aree di moderazione:</h1>";

            $xmlString = "";
            foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
            $xmlString .= trim($node);
            }


            $doc = new DOMDocument();
            $doc->loadXML($xmlString);

            $root = $doc->documentElement;
            $elementi = $root->childNodes;
            
            for ($i=0; $i<$elementi->length; $i++) {
            
            $elemento = $elementi->item($i);

                $ID = $elemento->firstChild;
                $ID_MOD_Area=$ID->textContent;

                $AREA = $ID->nextSibling;
                $TAG_AREA = $AREA->textContent;

                if($ID_CREAT == $ID_MOD_Area){
                    echo"<p class='text'> Moderatore di: $TAG_AREA</p>
                    
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$TAG_AREA\">
                                            <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">VAI A CONTROLLARE</button>
                                            </form>
                                        
                    ";
                }

                else{

                    $listaDeputy = $elemento->getElementsByTagName("ID_DEP");
                $numDeputy = $listaDeputy->length;

                for($j = 0;$j <$numDeputy ; $j++){

                    $deputy = $listaDeputy->item($j);
                    $deputyDaConfrontare = $deputy->textContent;

                    if($ID_CREAT == $deputyDaConfrontare){
                        echo"<p class='text'>Deputy-moderator di: $TAG_AREA</p>
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$TAG_AREA\">
                                            <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">VAI A CONTROLLARE</button>
                                            </form>
                        
                        ";
                    }
                    
                }

                }



        }
        echo"</div>";

                    
    }
    else if($ruolo == 100){
        echo"<div class='card'>
        <p class='text'>Si tratta di un ADMIN</p>
                                            </div>";
    }


    }

    else{
        $ID_UTENTE = $_SESSION['ID'];

    include("../DB-buono/connection.php");

    $sql = "SELECT *
            FROM $db_tab_utente 
            WHERE userID = \"$ID_UTENTE\"
		";

			if (!$result = mysqli_query($mysqliConnection, $sql)) {
				printf("Errore nella query di ricerca reputazioni\n");
			exit();
			}
			
        $row = mysqli_fetch_array($result);

        $nomeUtente= $row['nome'];
        $reputazione= $row['reputazione'];
        $email= $row['email'];
        $username= $row['username'];
        
        
        echo"<div class='card'>
                          
                        <h1 class='titolo'>CIAO $nomeUtente... QUESTE SONO LE TUE INFORMAZIONI</h1>

                        <p class='text'>LA TUA REPUTAZIONE &Egrave;: $reputazione</p>
                        <p class='text'>LA TUA MAIL DI ACCESSO &Egrave;: $email</p>
                        <p class='text'>IL TUO USERNAME DI ACCESSO &Egrave;: $username</p>
                        
                        <form method=\"post\" action=\"../ModificaPasswordUtente.php\">
                    <button class='btn btn-success btn-lg btn-block' type=\"submit\" value=\"ModificaPSW\">MODIFICA PASSWORD</button>
                    </form>
                    </div>";
        if($_SESSION['ruolo']==10 || $_SESSION['ruolo']==15){
            echo"<div class='card'>
                          
            <h1 class='titolo'>Queste sono le tue aree di moderazione:</h1>";

            $xmlString = "";
            foreach ( file("SCHEMI/moderatoreAreaDeputy.xml") as $node ) {
            $xmlString .= trim($node);
            }


            $doc = new DOMDocument();
            $doc->loadXML($xmlString);

            $root = $doc->documentElement;
            $elementi = $root->childNodes;
            
            for ($i=0; $i<$elementi->length; $i++) {
            
            $elemento = $elementi->item($i);

                $ID = $elemento->firstChild;
                $ID_MOD_Area=$ID->textContent;

                $AREA = $ID->nextSibling;
                $TAG_AREA = $AREA->textContent;

                if($ID_UTENTE == $ID_MOD_Area){
                    echo"<p class='text'> Sei moderatore di: $TAG_AREA</p>
                    
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$TAG_AREA\">
                                            <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">VAI A CONTROLLARE</button>
                                            </form>
                                        
                    ";
                }

                $listaDeputy = $elemento->getElementsByTagName("ID_DEP");
                $numDeputy = $listaDeputy->length;

                for($j = 0;$j <$numDeputy ; $j++){

                    $deputy = $listaDeputy->item($j);
                    $deputyDaConfrontare = $deputy->textContent;

                    if($_SESSION['ID'] == $deputyDaConfrontare){
                        echo"<p class='text'>Sei deputy-moderator di: $TAG_AREA</p>
                                            <form method=\"get\" action=\"POST DI AREA DETERMINATA.php\">
                                            <input type=\"hidden\" name=\"TAG_AREA\" value=\"$TAG_AREA\">
                                            <button class='btn btn-success btn-lg btn-like' type=\"submit\" value=\"PROBLEMA\">VAI A CONTROLLARE</button>
                                            </form>
                        
                        ";
                    }
                    
                }


        }
        echo"</div>";

                    
    }

    }


    
        
?>
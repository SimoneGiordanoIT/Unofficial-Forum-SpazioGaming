<?php

session_start();

$xmlString = "";
    foreach ( file("PAGINE SITO/SCHEMI/gestionePref.xml") as $node ) {
      $xmlString .= trim($node);
    }

    $controllo= 1;
   


    $doc = new DOMDocument();
    $doc->loadXML($xmlString);

    $root = $doc->documentElement;
    $elementi = $root->childNodes;

    for ($i=0; $i<$elementi->length; $i++) {
      
      $elemento = $elementi->item($i);



        $ID = $elemento->firstChild;
        $ID_Check_Utente=$ID->textContent;


        if($ID_Check_Utente==$_SESSION['ID']){

          $listaPreferiti = $elemento->getElementsByTagName("ID_Area");
          $numPreferiti = $listaPreferiti->length;

          
          for($j=0; $j<$numPreferiti; $j++ ){

              $preferito= $listaPreferiti->item($j);
              $ID_prefCheck = $preferito->textContent;

              if( $ID_prefCheck == $_POST['ID_Area']){
                $controllo= 0; /* il controllo diventa falso quando trovo corrispondenza tra l'area già presente e l'area da aggiungere */
                break;
              }
            }

          if($controllo==1){

            $newID_AREA = $doc->createElement("ID_Area", $_POST['ID_Area']);
              
              $elemento->appendChild($newID_AREA);

              $doc->save("PAGINE SITO/SCHEMI/gestionePref.xml");

              header("Location: PAGINE SITO/PAGINA PERSONALE.php");

          } 
          else {

            header("Location: PAGINE SITO/PAGINA PERSONALE.php");

          }
              
        }
      } 
    
  ?>
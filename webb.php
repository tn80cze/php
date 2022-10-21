<?php
/*
  PHP skript pro zasilani emailu z programu SendMails
  
  Parametry:
  pass ... bezpecnostni heslo - bez jeho zadani neni mozne zasilani zprav. Heslo zmenite v sekci "HESLO"   
  toemail ... prijemce
  subject ... predmet zpravy
  body ... telo zpravy
  header ... pripravena hlavicka zpravy 
  action ... provadena akce:
     1 .. vrati cislo verze
     2 .. test nastaveni hesla
     100 .. zasilani zpravy
  verzePC ... verze programu SendMails                         
*/

//***********HESLO - NUTNE UPRAVIT! Nesmi zustat prazdne!!!!!!!************
  $HESLO = '8306'; 
//*************************************************************************

  
	header('content-type:text/plain');

//konstanty NEMENIT
  $VERZE = '103';
  $ERROR_PARAMS = 'ERROR-PARAMS'; //chybne parametry
  $ERROR_PASS = 'ERROR-PASS'; //chybne heslo
  $ERROR_PASS2 = 'ERROR-PASS2'; //bezpecnostni heslo neni nastavene
  $OK = 'OK';   
  $ERR_SEND = 'ERROR-SEND'; //chyba pri odesilani   
//-----------------  

//funkce pro nacteni parametru   
  function GetPar($param, $default = '') {
    if (isset($_POST[$param]))
      if (get_magic_quotes_gpc())
        return stripslashes($_POST[$param]);
      else  
        return $_POST[$param];
    else
      return $default;
  }

  if (isset($_POST)) {
   //nacteni parametru   
    $pass = GetPar('pass');
    $toemail = GetPar('toemail');
    $subject = GetPar('subject');
    $body = GetPar('body');
    $header = GetPar('header');
    $action = intval(GetPar('action'));
    $verzePC = intval(GetPar('verzePC'));
    
   //verze
    if ($action == 1)
      echo "$OK\n$VERZE";   
    else { 
     //test hesla
      if ($HESLO == '')
        echo $ERROR_PASS2; //prazdne heslo
      else if ($pass != $HESLO)
        echo $ERROR_PASS; //chybne heslo        
      else if ($action == 2)
        echo $OK; //heslo v poradku (pokud jde pouze o test hesla)
     //odesilani   
      else if ($action == 100) {
        if (mail($toemail, $subject, trim($body), trim($header))) { 
          echo $OK;
          echo "\n$toemail\n$subject";
        }  
        else
          echo $ERR_SEND;  
      }
      else
        echo $ERROR_PARAMS;               
    }        
  }
  else
    echo $ERROR_PARAMS;

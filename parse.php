<?php
// ---------------------------------------- - -
//    Author: Nela Vlasakova (xvlasa14)
//      Date: 20. 2. 2020
// ---------------------------------------- - -

include 'parser/prints.php';
include 'parser/syntax.php';
include 'parser/random.php';

/* - - - - random variables - - - - */
$lineCount = 0;         // counter for lines in given file
$codeCount = 1;         // counts instructions
$comm = 1;

/* - - - - help argument options - - - - */
argcheck($argc);
if ($argc == 1) {
      /* - - - - open file, read line - - - - */
      $lines = file("php://stdin");       // load whole file
      if (!(preg_grep('/\A.ippcode20/i', $lines))) {
            errorPrint(ERROR_HEADER);
      }
      
      $inputLine = $lines[$lineCount];    // get first ([0]) line
      $inputLine = trim($inputLine);      // trim from the sides
      
      /* - - - - check stuff - - - - */
      // POŘÁD ŘEŠÍŠ PRÁZDNÉ ŘÁDKY A KOMENTÁŘE PŘED HLAVIČKOU
      while (preg_match('/\A#.*/', $inputLine) || $inputLine == "" || $inputLine == "\n") {
            //// echo "GOT HERE \n";
             $lineCount++;                             // increase the lineCount so next line is accessed
             $inputLine = $lines[$lineCount];
      }      
      $code = explode(' ', $inputLine, 2);
     
      
      if ($inputLine) {       // if the file is not empty                                           
           // // echo "HERE \n";
            if (preg_match('/\A.IPPCODE20\z/i', $code[0]) || preg_match('/\A.IPPCODE20(#|\n)/i', $code[0])) {                       // compare if the first piece is the correct header
                  $c1 = (preg_match('/#[\s\w]*/i', $code[1]));    // first condition, so the lines aren't that long
                  $c2 = (count($code) == 1);                      // second condition, same reason
                  // // echo $c1,"\n";
                  // print_r($code);
                  // // echo $c2,"\n";
                  if ($c1 || $c2) {                               // if there is some cooment or header is the only thing there
                        $counter = count($lines);                 // count the lines in the file 
                        /* - - - - start printing - - - - */
                        $xml = xmlwriter_open_memory();     // start new XMLWriter
                        beginning($xml);
                        // // echo "got here 1\n";
                        for ($i=1; $i < $counter; $i++) {         // do this for every line in the file
                              $lineCount++;                             // increase the lineCount so next line is accessed
                              $inputLine = $lines[$lineCount];          // load next line
                              //print_r($inputLine);
                              // take care of comments, fix whitespaces, separate etc:
                              $inputLine = preg_replace('/#.+/', ' ', $inputLine);  
                              $inputLine = trim($inputLine);                        
                              $inputLine = preg_replace('/\s+/', ' ', $inputLine);
                              // print_r($code);
                              $code = explode(' ', $inputLine);         // split current line into individual pieces
                              if ($code[0] != "") {                     // skip empty lines
                                    if (synCtrl($code, $xml, $codeCount)) {               // if syntax is okay
                                          $codeCount++;
                                    }
                                    else {
                                          errorPrint(ERROR_LEXSYN);
                                    }
                              }
                              else {
                                    continue;
                              }
                              // // echo $i, $code[0], "\n";                    
                        }
                        ending($xml);
                  }
            }
            else {
                  errorPrint(ERROR_HEADER);
            }
            exit(0);
      }
}
else {
      errorPrint(ERROR_PARAMETERS);
}
?>
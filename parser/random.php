<?php
// ---------------------------------------- - -
//    Author: Nela Vlasakova (xvlasa14)
//      Date: 20. 2. 2020
// ---------------------------------------- - -

// TO DO:
//    - stuff funkce default
//    - 134 type xml výstup
function argcheck($argc) {
      $getOptions = getopt("h", ["help"]);      // makes it so -opt and --opt can be used
      if ($argc == 2) {                         
            $longArgc = array_key_exists('help', $getOptions);    // long options
            $shortArgc = array_key_exists('h', $getOptions);      // short options
            
            if ($longArgc || $shortArgc) {      // if any of the help options is evoked
                  helpPrint();                
            }
            else {
                  errorPrint(ERROR_PARAMETERS);
            }
      }
}

/* - - - - prints beginning - - - - */
function beginning($xml) {
      xmlwriter_set_indent($xml, 1);      // toggle indentation
      xmlwriter_set_indent_string($xml, "\t");    // set horizontal tab as indent
      xmlwriter_start_document($xml, '1.0', 'UTF-8');
      
      xmlwriter_start_element($xml, "program");
            xmlwriter_start_attribute($xml, 'language');
                  xmlwriter_text($xml, "IPPcode20");
            xmlwriter_end_attribute($xml);
}

/* - - - - prints the end - - - - */
function ending($xml) {
      xmlwriter_end_element($xml); 
      xmlwriter_end_document($xml);
      echo xmlwriter_output_memory($xml);
}

function hereIam($xml, $yolo, $order, $isLabel) {
      if ($order == 1) {
          //  // // echo "ORDER 1 \n";
            xmlwriter_start_element($xml, "arg1");
      }
      elseif ($order == 2) {
            xmlwriter_start_element($xml, "arg2");
         //   // // echo "ORDER 2 \n";
      }
      elseif ($order == 3) {
            xmlwriter_start_element($xml, "arg3");
          //  // // echo "ORDER 3 \n";
      }
      xmlwriter_start_attribute($xml, 'type');
            if($isLabel == true) {
            //      // // echo "IS LABEL TRUE \n";
                  xmlwriter_text($xml, "label");
                  xmlwriter_end_attribute($xml);
                  xmlwriter_text($xml, $yolo); 
            }
            elseif (preg_match('/(GF|LF|TF)@/', $yolo)) {
                  xmlwriter_text($xml, "var");
                  // // echo "IS VAR GF LF or TF \n";
                  xmlwriter_end_attribute($xml);
                  xmlwriter_text($xml, $yolo); 
             }
            else {
             //     // // echo "IS NOT LABEL \n";
                  typeAttr($xml, $yolo);
                  xmlwriter_end_attribute($xml);
            }
      xmlwriter_end_element($xml);
}
/* - - - - writes type - - - - */
function typeAttr($xml, $attr) {
      if (preg_match('/(GF|LF|TF)@/', $attr)) {
           // // // echo "4.1 \n";
           // // echo "VAR TYPE ATTR \n";
            xmlwriter_text($xml, "var");
      }
      elseif (preg_match('/(int|bool|string)/', $attr)) {
            # <arg2 type="type">int</arg2>
            if (preg_match('/string/', $attr)) {
                  xmlwriter_text($xml, "string");
                  typePrint($xml, $attr); 
            }
            elseif (preg_match('/bool/', $attr)) {
                  // // echo "BOOL \n";
                  xmlwriter_text($xml, "bool");
                  typePrint($xml, $attr); 
            }
            elseif(preg_match('/int/', $attr)) {
                  // // echo "INT \n";
                  xmlwriter_text($xml, "int");
                  typePrint($xml, $attr);
            }            
      }
      elseif (preg_match('/(int|bool|string|nil)@.*/', $attr)) {
           // // // echo "4.2\n";
            $what = explode("@", $attr);
           //  print_r($what);
            stuff($xml, $what);
      }
}

function typePrint($xml, $typeOut) {
      if (preg_match('/(bool|string|int)/', $typeOut)) {
            $fin = explode("@", $typeOut, 2);
            xmlwriter_end_attribute($xml);
            // // echo $fin[1], "\n";
            if (preg_match('/\'/', $typeOut, $apos)) {
                 $finn = preg_replace('/\'/', "&apos;", $fin[1]);
                 // // echo $fin[1];
                  xmlwriter_text($xml, $finn);
            }
            else {
                  xmlwriter_text($xml, $fin[1]);
            }
      }
      else {
            xmlwriter_end_attribute($xml);
            // // echo "HERE \n";
            xmlwriter_text($xml, $typeOut);
      }
}
/* - - - - i don't know how to describe this function - - - - */
function stuff($xml, $what) {
     // // // echo "5 - ";
      // print_r($what);
      switch ($what[0]) {
            case 'string':
                  // // // echo "6\n";
                  xmlwriter_text($xml, "string");
                  xmlwriter_end_attribute($xml);
                  // // // echo $what[1];
                  if (!empty($what[1])) {
                        xmlwriter_text($xml, $what[1]);
                  }
                 // // // echo $what[1], "\n";
            break;
            
            case 'int':
                  xmlwriter_text($xml, "int");
                  xmlwriter_end_attribute($xml);
                  
                  xmlwriter_text($xml, $what[1]);
            break;
            
            case 'bool':
                  xmlwriter_text($xml, "bool");
                  xmlwriter_end_attribute($xml);
                  
                  xmlwriter_text($xml, $what[1]);
            break;
            
            case 'nil':
                  xmlwriter_text($xml, "nil");
                  xmlwriter_end_attribute($xml);
                  
                  xmlwriter_text($xml, $what[1]);
            break;
            
            default:
            return false;
      break;
      }
}

function write($xml, $code, $codeFlag, $i) {
      // <instruction order=" " opcode=" ">
     // // // echo "1\n";
      xmlwriter_start_element($xml, "instruction");
            xmlwriter_start_attribute($xml, 'order');
            xmlwriter_text($xml, $i);
            xmlwriter_end_attribute($xml);
            xmlwriter_start_attribute($xml, 'opcode');
            xmlwriter_text($xml, $code[0]);
            xmlwriter_end_attribute($xml);

            switch ($codeFlag) {
                  case "3VS": case "3VT": case "2V": case "4VSS":
                        // <var>
                       // // // echo "2\n";
                       // // echo $code[1], "\n";
                        hereIam($xml, $code[1], 1, NULL);
                        
                        if ($codeFlag == "3VS" || $codeFlag == "4VSS" ) {
                              // <symb>
                              // // // echo "3\n";
                              // // echo $code[2], "\n";
                              hereIam($xml, $code[2], 2, NULL);

                              if ($codeFlag == "4VSS") {
                                    // <symb>
                                    hereIam($xml, $code[3], 3, NULL);
                              }
                        }
                        elseif ($codeFlag == "3VT") {
                              // xml write type TO DO 
                              // <type>
                             // hereIam($xml, $code[2], 2, NULL);
                             if (preg_match('/\A(int|bool|string)\z/', $code[2])) {
                                    xmlwriter_start_element($xml, "arg2");
                                    xmlwriter_start_attribute($xml, "type");
                                    xmlwriter_text($xml, "type");
                                    xmlwriter_end_attribute($xml);
                                    xmlwriter_text($xml, $code[2]);
                                    xmlwriter_end_element($xml);
                             }
                             else {
                                   errorPrint(ERROR_LEXSYN);
                             }

                        }
                  break;
                  
                  case "2L": case "4LSS":
                        hereIam($xml, $code[1], 1, true);
                        if ($codeFlag == "4LSS") {
                              hereIam($xml, $code[2], 2, NULL);
                              hereIam($xml, $code[3], 3, NULL);
                        }
                  break;

                  case "2S":
                        hereIam($xml, $code[1], 1, NULL);
                  break;

                  case "1":
                  break;
                  default:
                        errorPrint(ERROR_INTERNAL);
                        break;
            }
      xmlwriter_end_element($xml);
            }           

            
      
      /*
      1

  
       */

?>

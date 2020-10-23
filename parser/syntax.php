<?php
// ---------------------------------------- - -
//    Author: Nela Vlasakova (xvlasa14)
//      Date: 20. 2. 2020
// ---------------------------------------- - -
// Syntax verification, help functions
// that check if variables, labels, 
// symbols and types are correct. 
// ---------------------------------------- - -

// co returnovat když je špatný typ? 
function typeCtrl($type) {   
      if (preg_match('/\A(int|string|bool)\z/', $type)) { 
            return true;
      }
      else {
            errorPrint(ERROR_LEXSYN);
      }
}

function symbCtrl($symbol) {  // !!! NOT DONE !!!
      // if symbol starts with frame, it is a variable:
            if (preg_match('/\A(GF|LF|TF)@.*\z/', $symbol)) { 
                  return varCtrl($symbol);                           
            }
            
            // if symbol starts with int, bool, string or nill, it is a constant

            elseif (preg_match('/\A(int|bool|string|nil)@.*\z/', $symbol)) {
                  $symvar = explode("@", $symbol, 2);    // separete with @ delimiter to determine what kind of constant it is, with limit of 2 just to be sure
                  switch ($symvar[0]) {
                        case 'int':
                              if(preg_match('/(\d|-[\d])/', $symvar[1])){  
                                    return true;
                              }
                              else {
                                    errorPrint(ERROR_LEXSYN);
                              }
                        break;
                        
                        case 'bool':
                              if (preg_match('/\A(true|false)\z/', $symvar[1])) {
                                    return true;
                              }
                              else {
                                    errorPrint(ERROR_LEXSYN);
                              }
                        break;
                        
                        case 'string':
                              if (preg_match('/\A(\s|.*)(\s|.*)\z/', $symvar[1])) {
                                    if (preg_match('/\\\\[0-9]{2}[\D]|\\\\[0-9][a-zA-Z]|\\\\[a-zA-Z]/', $symvar[1])) {
                                         return false;
                                    }
                                    else {
                                          return true;
                                    }
                              }
                              else {
                                    errorPrint(ERROR_LEXSYN);
                              }
                        break;
                        
                        case 'nil':
                              if ($symvar[1] == 'nil') {
                                    return true;
                              }
                              else {
                                    errorPrint(ERROR_LEXSYN);
                              }
                        break;

                        default:
                        errorPrint(ERROR_LEXSYN);
                        break;
            }
      }
      else {
            errorPrint(ERROR_LEXSYN);
      }
}

function labCtrl($label) {    // !!! NOT DONE !!!
      $lab = preg_match('/\A(_|-|\$|&|%|\*|!|\?|[a-zA-Z])(_|-|\$|&|%|\*|!|\?|[a-zA-Z0-9])*\z/', $label);
      if ($lab) {
            return true;
      }
      else {
            errorPrint(ERROR_LEXSYN);
      }
}

function varCtrl($variable) { // IFK MAYBE DONE 
      $var = preg_match('/\A(GF|LF|TF)@[\D]+(_|-|\$|&|%|\*|!|\?|[a-zA-Z0-9]*)*\z/', $variable);
      if ($var) {
           // // echo $variable;
            return true;
      }
      else {
            return false;
      }
}

/* - - - - syntax control - - - - */
function synCtrl($code, $xml, $i) {
      if (count($code) == 0 || count($code) > 4) {
            errorPrint(ERROR_LEXSYN);
      }
      
      // operation code has to be converted to uppercase to achieve successful comparison
      switch ($code[0] = strtoupper($code[0])) {
            // COUNT CODE: 1
            case 'CREATEFRAME': case 'PUSHFRAME': 
                  case 'POPFRAME': case 'RETURN': 
                  case 'BREAK':      
                  if (count($code) == 1) {
                        $codeFlag = "1";
                        write($xml, $code, $codeFlag, $i);
                        return true;
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;
            
            // COUNT CODE: 2; VAR
            case 'DEFVAR': case 'POPS':
                  if (count($code) == 2) {
                        if (varCtrl($code[1])) {
                              $codeFlag = "2V";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;

            // COUNT CODE: 2; LABEL
            case 'CALL': case 'LABEL': case 'JUMP':
                  if (count($code) == 2) {
                        if (labCtrl($code[1])) {
                              $codeFlag = "2L";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;

            // COUNT CODE: 2; SYMB
            case 'PUSHS': case 'WRITE': 
            case 'EXIT': case 'DPRINT':
                  if (count($code) == 2) {
                        if (symbCtrl($code[1])) {
                              $codeFlag = "2S";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;
            
            // COUNT CODE: 3; VAR SYMB
            case 'MOVE': case 'NOT': 
            case 'INT2CHAR': case 'STRLEN': 
            case 'TYPE':              
                  if (count($code) == 3 ) {
                        $var1 = varCtrl($code[1]);
                        $symb1 = symbCtrl($code[2]);

                        if ($var1 && $symb1) {
                              $codeFlag = "3VS";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;

            // COUNT CODE: 3; VAR TYPE
            case 'READ':
                  if (count($code) == 3) {
                        $var1 = varCtrl($code[1]);
                        $type1 = typeCtrl($code[2]);
                        if ($var1 && $type1) {
                              $codeFlag = "3VT";
                             // // echo $code, "\n";
                              write($xml, $code, $codeFlag, $i);                                          
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;
                       
            // COUNT CODE: 4; VAR SYMB SYMB
            case 'LT': case 'GT': case 'EQ': 
            case 'AND': case 'OR': case 'STRI2INT': 
            case 'CONCAT': case 'GETCHAR': case 'SETCHAR': 
            case 'ADD': case 'SUB': case 'MUL': case 'IDIV':
                  if (count($code) == 4) {
                        $var1 = varCtrl($code[1]);
                        $symb1 = symbCtrl($code[2]);
                        $symb2 = symbCtrl($code[3]);
                        
                        if ($var1 && $symb1 && $symb2) {
                              $codeFlag = "4VSS";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;
            
            // COUNT CODE: 4; LABEL SYMB SYMB
            case 'JUMPIFEQ': case 'JUMPIFNEQ':
                  if (count($code) == 4) {
                        $lab1 = labCtrl($code[1]);
                        $symb1 = symbCtrl($code[2]);
                        $symb2 = symbCtrl($code[3]);

                        if ($lab1 && $symb1 && $symb2) {
                              $codeFlag = "4LSS";
                              write($xml, $code, $codeFlag, $i);
                              return true;
                        }
                        else {
                              errorPrint(ERROR_LEXSYN);
                        }
                  }
                  else {
                        errorPrint(ERROR_LEXSYN);
                  }
            break;

            default:
            errorPrint(ERROR_OPERATION_CODE);
            break;
      }
}


?>

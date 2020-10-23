<?php
// ---------------------------------------- - -
//    Author: Nela Vlasakova (xvlasa14)
//      Date: 20. 2. 2020
// ---------------------------------------- - -

/* - - - - error codes - - - - */
const ERROR_PARAMETERS = 10;
const ERROR_INPUT_FILE = 11;
const ERROR_OUTPUT_FILE = 12;
const ERROR_INTERNAL = 99;

// parse.php errors:
const ERROR_HEADER = 21;
const ERROR_OPERATION_CODE = 22;
const ERROR_LEXSYN = 23;

// interpret.py errors:
const ERROR_XML_FORMAT = 31;
const ERROR_XML_STRUCT = 32;

// test.php errors:
const ERROR_SEM = 52;
const ERROR_OPERAND = 53;
const ERROR_VARIABLE = 54;
const ERROR_FRAME = 55;
const ERROR_VALUE = 56;
const ERROR_OP_VALUE = 57;
const ERROR_STRING = 58;

function errorPrint($errorFlag){
      switch ($errorFlag) {
            case ERROR_SEM:
                  fprintf(STDERR, "Oopsie. Semantic error.\n");
            break;

            case ERROR_OPERAND:
                  fprintf(STDERR, "Oopsie. Wrong types of operands.\n");
            break;

            case ERROR_VARIABLE:
                  fprintf(STDERR, "Oopsie. You're trying to use non-existing variables. \n");
            break;

            case ERROR_FRAME:
                  fprintf(STDERR, "Oopsie. This frame doesn't exist.\n");
            break;

            case ERROR_VALUE:
                  fprintf(STDERR, "Oopsie. Missing value.\n");
            break;

            case ERROR_OP_VALUE:
                  fprintf(STDERR, "Oopsie. Wrong value of operand. Maybe dividing by zero?\n");
            break;

            case ERROR_STRING:
                  fprintf(STDERR, "Oopsie. Error during work with string.\n");
            break;

            case ERROR_PARAMETERS:
                  fprintf(STDERR, "Oopsie. Missing arguments or the use of forbidden argument combinations.\n");
            break;
            
            case ERROR_INPUT_FILE:
                  fprintf(STDERR, "Oopsie. Input file error. Maybe it doesn't exist or the permissions are not sufficent.\n");      
            break;

            case ERROR_OUTPUT_FILE:
                  fprintf(STDERR, "Oopsie. Output file error. Insufficent permissions.\n");
            break;

            case ERROR_INTERNAL:
                  fprintf(STDERR, "Oopsie. Internal error. Probably memory allocation fail.
                        \n");
            break;
            
            case ERROR_HEADER:
                  fprintf(STDERR, "Oopsie. Faulty or missing header.\n");

            break;

            case ERROR_OPERAND:
                  fprintf(STDERR, "Oopsie. Unknown or faulty operation code.\n");
            break;

            case ERROR_LEXSYN:
                  fprintf(STDERR, "Oopsie. Some lexical or syntactic error.\n");
            break;

            case ERROR_XML_FORMAT:
                  fprintf(STDERR, "Oopsie. Faulty XML format.\n");
            break;

            case ERROR_XML_STRUCT:
                  fprintf(STDERR, "Oopsie. Unexpected XML structure.\n");
            break;

            default:
                  fprintf(STDERR, "Oopsie. Something went wrong.\n");
            break;
      
      break;
      }
      exit($errorFlag);
}

function helpPrint(){
      print "
      - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
                               < WHAT IS THIS SORCERY? >
            Filtr type script. Loads source code in IPPcode20 from stdin,
            checks code syntax and puts out XML representation on stdout.
      - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            --help:    prints this help
                -h:    also prints this help\n";     
                exit(0);
}

?>

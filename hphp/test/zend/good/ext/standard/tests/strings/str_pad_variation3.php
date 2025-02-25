<?hh
/* Prototype  : string str_pad  ( string $input  , int $pad_length  [, string $pad_string  [, int $pad_type  ]] )
 * Description: Pad a string to a certain length with another string
 * Source code: ext/standard/string.c
*/

/* Test str_pad() function: with unexpected inputs for '$pad_string'
 *  and expected type for '$input' and '$pad_length'
*/

//defining a class
class sample  {
  public function __toString() :mixed{
    return "sample object";
  }
}
<<__EntryPoint>> function main(): void {
echo "*** Testing str_pad() function: with unexpected inputs for 'pad_string' argument ***\n";


//getting the resource
$file_handle = fopen(__FILE__, "r");

// array with different values for $input
$pad_strings =  vec[

          // integer values
/*1*/      0,
          1,
          -2,
          2147483647,
          -2147483648,

          // float values
/*6*/      10.5,
          -20.5,
          10.1234567e10,

          // array values
/*9*/      vec[],
          vec[0],
          vec[1, 2],

          // boolean values
/*12*/      true,
          false,
          TRUE,
          FALSE,

          // null vlaues
/*16*/      NULL,
          null,

          // objects
/*18*/      new sample(),

          // resource
/*19*/      $file_handle,
];

//defining '$input' argument
$input = "Test string";
$pad_length = 20;

// loop through with each element of the $pad_strings array to test str_pad() function
$count = 1;
foreach($pad_strings as $pad_string) {
  echo "-- Iteration $count --\n";
  try { var_dump( str_pad($input, $pad_length, $pad_string) ); } catch (Exception $e) { echo "\n".'Warning: '.$e->getMessage().' in '.__FILE__.' on line '.__LINE__."\n"; }
  $count ++;
}

fclose($file_handle);  //closing the file handle

echo "===DONE===\n";
}

<?hh
/* Prototype  : string vsprintf(string format, array args)
 * Description: Return a formatted string 
 * Source code: ext/standard/formatted_print.c
*/

/*
 * Test vsprintf() when different scientific formats and non-scientific values are passed to
 * the '$format' and '$args' arguments of the function
*/
<<__EntryPoint>> function main(): void {
echo "*** Testing vsprintf() : scientific formats and non-scientific values ***\n";

// defining array of non-scientific formats
$formats = 
  '%e %+e %-e 
   %le %Le %4e %-4e
   %10.4e %-10.4e %04e %04.4e
   %\'#2e %\'2e %\'$2e %\'_2e
   %3$e %4$e %1$e %2$e';

// Arrays of non scientific values for the format defined in $format.
// Each sub array contains non scientific values which correspond to each format in $format
$args_array = vec[

  // array of float values
  vec[2.2, .2, 10.2,
        123456.234, 123456.234, -1234.6789, +1234.6789,
        20.00, +212.2, -411000000000, 2212.000000000001,
        12345.780, 12.000000011111, -12.00000111111, -123456.234,
        3.33, +4.44, 1.11,-2.22 ],

  // array of strings
  vec[" ", ' ', 'hello',
        '123hello', "123hello", '-123hello', '+123hello',
        "\12345678hello", "-\12345678hello", '0123456hello', 'h123456ello',
        "1234hello", "hello\0world", "NULL", "true",
        "3", "4", '1', '2'],

  // different arrays
  vec[ vec[0], vec[1, 2], vec[-1, -1],
         vec["123"], vec['123'], vec['-123'], vec["-123"],
         vec[true], vec[false], vec[TRUE], vec[FALSE],
         vec["123hello"], vec["1", "2"], vec['123hello'], dict[12=>"12twelve"],
         vec["3"], vec["4"], vec["1"], vec["2"] ],

  // array of boolean data
  vec[ true, TRUE, false,
         TRUE, 0, FALSE, 1,
         true, false, TRUE, FALSE,
         0, 1, 1, 0,
         1, TRUE, 0, FALSE],
  
];

// looping to test vsprintf() with different scientific formats from the above $format array
// and with non-scientific values from the above $args_array array
$counter = 1;
foreach($args_array as $args) {
  echo "\n-- Iteration $counter --\n";
  var_dump( vsprintf($formats, $args) );
  $counter++;
}

echo "Done";
}

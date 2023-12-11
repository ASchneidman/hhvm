<?hh
/* Prototype  : array array_intersect_assoc(array $arr1, array $arr2 [, array $...])
 * Description: Returns the entries of arr1 that have values which are present in all the other arguments.
 * Keys are used to do more restrictive check
 * Source code: ext/standard/array.c
*/

/*
* Passing different types of arrays to $arr2 argument and testing whether
* array_intersect_assoc() behaves in an expected way with the other arguments passed to the function.
* The $arr1 argument passed is a fixed array.
*/
<<__EntryPoint>> function main(): void {
echo "*** Testing array_intersect_assoc() : Passing different types of arrays to \$arr2 argument ***\n";

/* Different heredoc strings passed as argument to $arr2 */
// heredoc with blank line
$blank_line = <<<EOT


EOT;

// heredoc with multiline string
$multiline_string = <<<EOT
hello world
The big brown fox jumped over;
the lazy dog
This is a double quoted string
EOT;

// heredoc with different whitespaces
$diff_whitespaces = <<<EOT
hello\r world\t
1111\t\t != 2222\v\v
heredoc\ndouble quoted string. with\vdifferent\fwhite\vspaces
EOT;

// heredoc with quoted strings and numeric values
$numeric_string = <<<EOT
11 < 12. 123 >22
'single quoted string'
"double quoted string"
2222 != 1111.\t 0000 = 0000\n
EOT;

// array to be passsed to $arr1 argument
$arr1 = darray [
  0 => 1, 1 => 1.1, 2 => 1.3, 1 => true, 3 => "hello", 4 => "one", 5 => NULL, 6 => 2,
  7 => 'world', 8 => true, 9 => false, 3 => "b\tbbb", 10 => "aaaa\r",
  11 => $numeric_string, "h3" => $diff_whitespaces, "true" => true,
  "one" => "ten", 4 => "four", "two" => 2, 6 => "six",
  12 => '', '' => "null", '' => 'emptys'
];

// arrays to be passed to $arr2 argument
$arrays = varray [
/*1*/  vec[1, 2], // array with default keys and numeric values
       vec[1.1, 1.2, 1.3], // array with default keys & float values
       vec[false,true], // array with default keys and boolean values
       vec[], // empty array
/*5*/  vec[NULL], // array with NULL
       vec["a\v\f","aaaa\r","b","b\tbbb","c","\[\]\!\@\#\$\%\^\&\*\(\)\{\}"],  // array with double quoted strings
       vec['a\v\f','aaaa\r','b','b\tbbb','c','\[\]\!\@\#\$\%\^\&\*\(\)\{\}'],  // array with single quoted strings
       dict[0 => $blank_line, "h2" => $multiline_string, "h3" => $diff_whitespaces, 1 => $numeric_string],  // array with heredocs

       // associative arrays
/*9*/  dict[1 => "one", 2 => "two", 6 => "six"],  // explicit numeric keys, string values
       dict["one" => 1, "two" => 2, "three" => 3 ],  // string keys & numeric values
       dict[ 1 => 10, 2 => 20, 4 => 40, 3 => 30],  // explicit numeric keys and numeric values
       dict[ "one" => "ten", "two" => "twenty", "three" => "thirty"],  // string key/value
       dict["one" => 1, 2 => "two", 4 => "four"],  //mixed

       // associative array, containing null/empty/boolean values as key/value
/*14*/ dict['' => "NULL", '' => "null", "NULL" => NULL, "null" => null],
       dict[1 => "true", 0 => "false", "false" => false, "true" => true],
       dict["" => "emptyd", '' => 'emptys', "emptyd" => "", 'emptys' => ''],
       dict[1 => '', 2 => "", 3 => NULL, 4 => null, 5 => false, 6 => true],
       dict['' => 1, "" => 2, '' => 3, '' => 4, 0 => 5, 1 => 6],

       // array with repetative keys
/*19*/ dict["One" => 1, "two" => 2, "One" => 10, "two" => 20, "three" => 3]
];

// loop through each sub-array within $arrrays to check the behavior of array_intersect_assoc()
$iterator = 1;
foreach($arrays as $arr2) {
  echo "-- Iteration $iterator --\n";

  // Calling array_intersect_assoc() with default arguments
  var_dump( array_intersect_assoc($arr1, $arr2) );

  // Calling array_intersect_assoc() with more arguments
  // additional argument passed is the same as $arr1 argument
  var_dump( array_intersect_assoc($arr1, $arr2, $arr1) );
  $iterator++;
}

echo "Done";
}

<?hh
/* Prototype  : int strncasecmp ( string $str1, string $str2, int $len );
 * Description: Binary safe case-insensitive string comparison of the first n characters
 * Source code: Zend/zend_builtin_functions.c
*/

/* Test strncasecmp() function with the unexpected inputs for 'str1' and 'str2' */

/* declaring a class */
class sample  {
  public function __toString() :mixed{
  return "object";
  }
}
<<__EntryPoint>> function main(): void {
echo "*** Testing strncasecmp() function: with unexpected values for 'str1' and 'str2' ***\n";

/* get resource handle */
$file_handle = fopen(__FILE__, "r");


/* array with different values */
$values =  vec[
  /* integer values */
  0,
  1,
  12345,
  -2345,

  /* float values */
  10.5,
  -10.5,
  10.5e10,
  10.6E-10,
  .5,

  /* hexadecimal values */
  0x12,
  -0x12,

  /* octal values */
  012,
  -012,
  01.2,

  /* array values */
  vec[],
  vec[0],
  vec[1],
  vec[1, 2],
  dict['color' => 'red', 'item' => 'pen'],

  /* boolean values */
  true,
  false,
  TRUE,
  FALSE,

  /* nulls */
  NULL,
  null,

  /* empty string */
  "",
  '',

  /* resource */
  $file_handle,

  /* object */
  new sample()
];

/* loop through each element of the array and check the working of strncasecmp() */
$counter = 1;
for($index = 0; $index < count($values); $index ++) {
  echo "-- Iteration $counter --\n";
  $str1 = $values[$index];
  $str2 = $values[$index];
  try { $len = strlen($values[$index]) + 1; } catch (Exception $e) { echo "\n".'Warning: '.$e->getMessage().' in '.__FILE__.' on line '.__LINE__."\n"; }
  try { var_dump( strncasecmp($str1, $str2, $len) ); } catch (Exception $e) { echo "\n".'Warning: '.$e->getMessage().' in '.__FILE__.' on line '.__LINE__."\n"; }
  $counter ++;
}

fclose($file_handle);  //closing the file handle

echo "*** Done ***\n";
}

<?hh

<<__EntryPoint>>
function main(): void {
  // Purposely test a non-utf8 string
  $a = "a�c\n";
  echo "done\n";
}

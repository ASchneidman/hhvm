<?hh

function test(): void {
  $args = vec["hello"];
  // HHVM doesn't support unpacking into isset
  isset(...$args);
}

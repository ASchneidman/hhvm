<?hh

/**
 * Test AKempty to AKvarray upgrade when inside a nested type
 */
function test(): void {
  $a = Vector { vec[] };
  $a[0][] = 'aaa';
  f($a);
}

function f(ConstVector<int> $_): void {}

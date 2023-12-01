<?hh

/**
 * Shape-like arrays preserve element types - usage that should report errors.
 */

function test(): void {
  $a = dict[];
  $a['k1'] = 4;
  $a['k2'] = 'aaa';
  take_string($a['k1']);
}

function take_string(string $_): void {}

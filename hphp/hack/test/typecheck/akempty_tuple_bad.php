<?hh
// Copyright 2004-present Facebook. All Rights Reserved.

function omg(): (?string, varray<int>) {
  $x = vec[];
  return $x;
}

function breakit(): void {
  $x = omg();
  list($y, $z) = $x;
  echo $y;
}

//breakit();

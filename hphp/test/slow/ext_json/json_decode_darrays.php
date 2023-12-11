<?hh

// parameter for json_decode
enum IsAssoc: int {
  FALSE = 0;
  TRUE = 1;
}

function make_object($d) :mixed{
  $obj = new stdClass;
  foreach ($d as $k => $v) {
    $obj->$k = $v;
  }
  return $obj;
}

function report($msg, $obj1, $obj2) :mixed{
  echo "$msg\nShould be\n";
  var_dump($obj1);
  echo "But found\n";
  var_dump($obj2);
  echo "\n";
}

function is_equal($result1, $result2) :mixed{
  if ($result1 === null && $result2 === null) {
    return true;
  }

  $type1 = gettype($result1);
  $type2 = gettype($result2);
  if ($type1 !== $type2) {
    report('Incorrect type', $type1, $type2);
    return false;
  }

  if ($type1 !== 'array') {
    // use the equality-comparison operator (==) for objects
    // because identity-comparison (===)
    // requires the two objects to be the same instance.
    // http://php.net/manual/en/language.oop5.object-comparison.php
    if (is_object($result1) && is_object($result2)) {
      if ($result1 != $result2) {
        report('Two objects not kinda-equal', $result1, $result2);
        return false;
      }
      return true;
    }
    if ($result1 !== $result2) {
      report('Two values not equal', $result1, $result2);
      return false;
    }
    return true;
  }

  $n1 = count($result1);
  $n2 = count($result2);
  if ($n1 !== $n2) {
    report('Mismatched size', $n1, $n2);
    return false;
  }

  if ($n1 === 0) {
    return true;
  }

  invariant(is_darray($result1), 'expect darray here');
  invariant(is_darray($result2), 'expect darray here');

  $keys1 = array_keys($result1);
  $keys2 = array_keys($result2);

  sort(inout $keys1);
  sort(inout $keys2);

  if ($keys1 !== $keys2) {
    report('Mismatched keys', $keys1, $keys2);
    return false;
  }

  foreach ($keys1 as $key) {
    if (!is_equal($result1[$key], $result2[$key])) {
      report('Incorrect value at index: '. $key, $result1[$key], $result2[$key]);
      return false;
    }
  }

  return true;
}

<<__EntryPoint>> function main(): void {
  $tests = vec[
    // Scalar types tests from HHJsonDecodeTest.php
    dict[
      'input' => 'null',
      'options' => 0,
      'expected' => dict[
        IsAssoc::TRUE => null,
        IsAssoc::FALSE => null,
      ],
    ],
    dict[
      'input' => '0',
      'options' => JSON_FB_LOOSE,
      'expected' => dict[
        IsAssoc::TRUE => 0,
        IsAssoc::FALSE => 0,
      ],
    ],
    dict[
      'input' => '"0"',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => '0',
        IsAssoc::FALSE => '0',
      ],
    ],
    dict[
      'input' => 'true',
      'options' => JSON_FB_LOOSE,
      'expected' => dict[
        IsAssoc::TRUE => true,
        IsAssoc::FALSE => true,
      ],
    ],

    // basic vec inputs
    dict[
      'input' => '[]',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[],
        IsAssoc::FALSE => dict[],
      ],
    ],
    dict[
      'input' => '[2]',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 2],
        IsAssoc::FALSE => dict[0 => 2],
      ],
    ],
    dict[
      'input' => '[42, "foo", "0"]',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 42, 1 => 'foo', 2 => '0'],
        IsAssoc::FALSE => dict[0 => 42, 1 => 'foo', 2 => '0'],
      ],
    ],

    // basic dict inputs
    dict[
      'input' => '{}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[],
        IsAssoc::FALSE => make_object(dict[]),
      ],
    ],
    dict[
      'input' => '{"0": 1}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 1],
        IsAssoc::FALSE => make_object(dict[0 => 1]),
      ],
    ],
    dict[
      'input' => '{"0": 1, "a": "b"}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 1, 'a' => 'b'],
        IsAssoc::FALSE => make_object(dict[0 => 1, 'a' => 'b']),
      ],
    ],
    dict[
      'input' => '{"{" : "}"}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict['{' => '}'],
        IsAssoc::FALSE => make_object(dict['{' => '}']),
      ],
    ],
    dict[
      'input' => '{"0": 1, "a": "b", "[]": null, "#": false}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 1,  'a' => 'b', '[]' => null, '#' => false],
        IsAssoc::FALSE => make_object(dict[0 => 1, 'a' => 'b', '[]' => null, '#' => false]),
      ],
    ],

    // LooseModeCollections from HHJsonDecodeTest.phpi
    // Single-quoted strings
    dict[
      'input' => '[\'value\']',
      'options' => JSON_FB_LOOSE | JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 'value'],
        IsAssoc::FALSE => dict[0 => 'value'],
      ],
    ],
    // Unquoted keys
    dict[
      'input' => '{key: "value"}',
      'options' => JSON_FB_LOOSE | JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict['key' => 'value'],
        IsAssoc::FALSE => make_object(dict['key' => 'value']),
      ],
    ],
    // Boolean keys
    dict[
      'input' => '{true: "value"}',
      'options' => JSON_FB_LOOSE | JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict['true' => 'value'],
        IsAssoc::FALSE => make_object(dict['true' => 'value']),
      ],
    ],
    // Null keys
    dict[
      'input' => '{null: "value"}',
      'options' => JSON_FB_LOOSE | JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict['null' => 'value'],
        IsAssoc::FALSE => make_object(dict['null' => 'value']),
      ],
    ],

    // Nested hack-arrays
    dict[
      'input' =>  '[2,"4",{0:[]}]',
      'options' => JSON_FB_LOOSE | JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict[0 => 2, 1 => '4', 2 => dict[0 => dict[]]],
        IsAssoc::FALSE => dict[0 => 2, 1 => '4', 2 => make_object(dict[0 => dict[]])],
      ],
    ],
    dict[
      'input' => '{"vec": [], "map": {}}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE => dict['vec' => dict[], 'map' => dict[]],
        IsAssoc::FALSE => make_object(dict['vec' => dict[], 'map' => make_object(dict[])]),
      ],
    ],
    dict[
      'input' => '{"vec" : [{"z" : []}], "map" : {"a" : {"]" : "["}}}',
      'options' => JSON_FB_DARRAYS,
      'expected' => dict[
        IsAssoc::TRUE =>
          dict[
            'vec' => dict[0 => dict['z' => dict[]]],
            'map' => dict['a' => dict[']' => '[']]
          ],
        IsAssoc::FALSE =>
          make_object(dict[
            'vec' => dict[0 => make_object(dict['z' => dict[]])],
            'map' => make_object(dict['a' => make_object(dict[']' => '['])])
          ]),
      ],
    ],
  ];

  foreach ($tests as $idx => $test) {
    foreach (vec[IsAssoc::TRUE, IsAssoc::FALSE] as $is_assoc) {
      $output =
        json_decode($test['input'], (bool) $is_assoc, 512, $test['options']);

      if (!is_equal($test['expected'][$is_assoc], $output)) {
        report(
          sprintf('** final comparison #%d**', $idx),
          $test['expected'][$is_assoc],
          $output,
        );
      }
    }
  }
  echo "Done\n";
}

<?hh

<<__EntryPoint>>
function main(): void {
  // Get the autoloader to initialize
  HH\autoload_is_native();

  // === FileDecls should succeed parsing this text
  $filepath = __DIR__.'/FileDecls_testcontent.txt';
  $instance = HH\FileDecls::parsePath($filepath);

  echo "=== Methods\n";
  var_dump($instance->getMethods('MyClass'));
  var_dump($instance->getMethod('MyClass', 'myMethod1'));
  var_dump($instance->getMethod('MyClass', 'missing'));
  var_dump($instance->getMethod('MyClass', ''));

  echo "=== Static methods\n";
  var_dump($instance->getStaticMethods('MyClass'));
  var_dump($instance->getStaticMethod('MyClass', 'mySMethod1'));
  var_dump($instance->getStaticMethod('MyClass', 'missing'));
  var_dump($instance->getStaticMethod('MyClass', ''));

  echo "=== Consts\n";
  var_dump($instance->getConsts('MyClass'));
  var_dump($instance->getConst('MyClass', 'MY_CONST1'));
  var_dump($instance->getConst('MyClass', 'missing'));
  var_dump($instance->getConst('MyClass', ''));

  echo "=== Typeconsts\n";
  var_dump($instance->getTypeconsts('MyClass'));
  var_dump($instance->getTypeconst('MyClass', 'MyTypeAlias1'));
  var_dump($instance->getTypeconst('MyClass', 'missing'));
  var_dump($instance->getTypeconst('MyClass', ''));

  echo "=== Properties\n";
  var_dump($instance->getProps('MyClass'));
  var_dump($instance->getProp('MyClass', 'myInstanceField1'));
  var_dump($instance->getProp('MyClass', 'missing'));
  var_dump($instance->getProp('MyClass', ''));

  echo "=== Static Properties\n";
  var_dump($instance->getStaticProps('MyClass'));
  var_dump($instance->getStaticProp('MyClass', '$myStaticField2'));
  var_dump($instance->getStaticProp('MyClass', 'myStaticField2')); // $ should be added
  var_dump($instance->getStaticProp('MyClass', 'missing'));
  var_dump($instance->getStaticProp('MyClass', ''));

  echo "=== Attributes\n";
  var_dump($instance->getAttributes('MyClass'));
  var_dump($instance->getAttribute('MyClass', 'MyAttribute1'));
  var_dump($instance->getAttribute('MyClass', 'missing'));
  var_dump($instance->getAttribute('MyClass', ''));

  echo "=== File Consts\n";
  var_dump($instance->getFileConsts());
  var_dump($instance->getFileConst('MY_GLOBAL_CONST1'));
  var_dump($instance->getFileConst('missing'));
  var_dump($instance->getFileConst(''));

  echo "=== File Functions\n";
  var_dump($instance->getFileFuncs());
  var_dump($instance->getFileFunc('myGlobalFunc2'));
  var_dump($instance->getFileFunc('missing'));
  var_dump($instance->getFileFunc(''));

  echo "=== File Modules\n";
  var_dump($instance->getFileModules());
  var_dump($instance->getFileModule('myModuleInner'));
  var_dump($instance->getFileModule('missing'));
  var_dump($instance->getFileModule(''));

  echo "=== File Typedef\n";
  var_dump($instance->getFileTypedefs());
  var_dump($instance->getFileTypedef('myGlobalType1'));
  var_dump($instance->getFileTypedef('missing'));
  var_dump($instance->getFileTypedef(''));

  echo "=== File Attributes\n";
  var_dump($instance->getFileAttributes());
  var_dump($instance->getFileAttribute('MyFileAttribute'));
  var_dump($instance->getFileAttribute('missing'));
  var_dump($instance->getFileAttribute(''));

  echo "=== XHP Classes\n";
  var_dump($instance->getClass('xhp_element'));
  var_dump($instance->getClass(':element'));
}

<?hh

class C {
  public static function f(): void {
    nameof C is string; // T171578360
  }
}

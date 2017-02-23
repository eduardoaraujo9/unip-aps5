<?php
class A {
    public $foo = 1;
	private $priv = 3;
	public function teste(){
		return 5;
	}
}
$a = new A;
$c = new A;
$b = $a; // $b é cópia do $a
$b->foo = 2;
echo $a->foo."\n";
echo $b->foo."\n";
echo $c->foo."\n";
echo $c->teste()."\n";
?>

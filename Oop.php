<?php

/**
* 
*/
class Oop{
	public $item = [];

	public function __conctruct($item){
		$this->item = $item;
	}

	public function A (){
		$item['a'] = 1;

		return $this;
	}

	public function B (){
		$item['b'] = 2;

		return $this;
	}
}

$o = new Oop();

$p = $o->A()->B();

print_r($p);

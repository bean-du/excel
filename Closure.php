<?php
class Di {

	public $factory;
	public function set($id,$value)
	{
		$this->factory[$id] = $value;
	}

	public function get($id)
	{
		$value = $this->factory[$id];
		return $value();
	}
}


class User
{
	public $userName;
	function __construct($userName)
	{
		$this->userName = $userName;
	}

	public function getUserName(){
		return $this->userName;
	}
}

$di = new Di();

$di->set('one',function(){
	return new User('bean');
});

echo $di->get('one')->getUserName();
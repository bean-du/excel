<?php
/**
* 
*/
class Bim
{
	public function Something()
	{
		echo __METHOD__,"|";	
	}
}

/**
* 
*/
class Bar
{
	private $bim;
	function __construct(Bim $bim)
	{
		$this->bim = $bim;
	}

	public function Something()
	{
		$this->bim->Something();
		echo __METHOD__,"|";
	}
}


/**
* 
*/
class Foo
{
	private $bar;
	function __construct(Bar $bar)
	{
		$this->bar=$bar;
	}

	public function Something()
	{
		$this->bar->Something();
		echo __METHOD__,"|";
	}
}


/**
* 
*/
class Container
{
	private $s = [];

	public function __set($k,$c)
	{
		$this->s[$k] = $c;
	}

	public function __get($k)
	{
		return $this->build($this->s[$k]);
	}

	public function build($className)
	{
		echo "<pre>";var_dump($className);
		 // 如果是匿名函数（Anonymous functions），也叫闭包函数（closures）
		if ($className instanceof  Closure) {
			// 执行闭包函数，并将结果
			return $className($this);
		}

		$reflector = new ReflectionClass($className);
		 // 检查类是否可实例化, 排除抽象类abstract和对象接口interface
		if(!$reflector->isInstantiable()){
			throw new Exception("Can't instantiate this.");

		}
		 /** @var ReflectionMethod $constructor 获取类的构造函数 */
		$constructor = $reflector->getConstructor();
		// 若无构造函数，直接实例化并返回
		if (is_null($constructor)) {
			return new $className;
		}
        // 取构造函数参数,通过 ReflectionParameter 数组返回参数列表
		$parameters = $constructor->getParameters();

        // 递归解析构造函数的参数
        $dependencies = $this->getDependencies($parameters);
        // 创建一个类的新实例，给出的参数将传递到类的构造函数。
        return $reflector->newInstanceArgs($dependencies);
	}

    public function getDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter){
            $dependency = $parameter->getClass();
            if (is_null($dependency)){
                $dependencies[] = $this->resolveNonClass($parameter);
            }else{
                $dependencies[] = $this->build($dependency->name);
            }
        }

        return $dependencies;
    }

    public function resolveNonClass($parameter)
    {
        if ($parameter->isDefaultValueAvaiable()){
            return $parameter->getDefaultValue();
        }
        throw new Exception('I have no idea what to do here.');
    }

}

$app = new Container();

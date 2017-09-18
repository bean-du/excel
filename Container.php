<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20
 * Time: 9:15
 */
class Container
{
    /**
     *  容器绑定，用来装提供的实例或者 提供实例的回调函数
     * @var array
     */
    public $building = [];

    /**
     * @param $abstract
     * @param null $concrete
     * @param bool $shared
     * 注册一个绑定到容器
     */
    public function bind($abstract,$concrete = null,$shared = false)
    {
        if (is_null($concrete)){
            $concrete = $abstract;
        }

        if (!$concrete instanceof Closure){
            $concrete = $this->getClosure($abstract,$concrete);
        }

        $this->building[$abstract] = compact('concrete','shared');
    }

    /**
     * @param $abstract
     * @param $concrete
     * @param bool $shared
     * 注册一个共享的绑定 单例
     */
    public function singleton($abstract,$concrete,$shared = true)
    {
        $this->bind($abstract,$concrete,$shared);
    }

    /**默认生成实例的回调闭包
     * @param $abstract
     * @param $concrete
     * @return Closure
     */
    public function getClosure($abstract,$concrete)
    {
        return function ($c) use($abstract,$concrete){
            $method = ($abstract == $concrete) ? 'build' : 'make';
            return $c->$method($concrete);
        };
    }

    public function make($abstract)
    {

        $concrete = $this->getConcrete($abstract);
        if ($this->isBuildable($concrete,$abstract)){
            $object = $this->build($concrete);
        }else{
            $object = $this->make($concrete);
        }

        return $object;
    }

    /**获取绑定的回调函数
     * @param $abstract
     * @return mixed
     */
    public function getConcrete($abstract)
    {
        if (!isset($this->building[$abstract])){
            return $abstract;
        }

        return $this->building[$abstract]['concrete'];
    }

    /**
     * @param $concrete
     * @param $abstract
     * @return bool
     * 判断 是否 可以创建服务实体
     */
    public function isBuildable($concrete, $abstract)
    {

        return $concrete === $abstract || $concrete instanceof Closure;
    }

    /**根据实例具体名称实例具体对象
     * @param $concrete
     * @return mixed|object
     * @throws Exception
     */
    public function build($concrete)
    {

        if ($concrete instanceof Closure){
            return $concrete($this);
        }
        //创建反射对象
        $reflector = new ReflectionClass($concrete);

        if (!$reflector->isInstantiable()){
            throw new Exception('无法实例化');
        }
        //获取构造函数参数
        $constructor  = $reflector->getConstructor();
        // 如果没有构造函数， 直接返回对象实例
        if (is_null($constructor)){
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instance = $this->getDependencies($dependencies);

        return $reflector->newInstanceArgs($instance);
    }

    /**通过反射解决参数依赖
     * @param $dependencies
     * @return array
     */
    public function getDependencies($dependencies)
    {
        $result = [];

        foreach ($dependencies as $dependency){
            $result[] = is_null($dependency->getClass()) ? $this->resolvedNonClass($dependency) : $this->resolvedClass($dependency);
        }

        return $result;
    }

    /**解决一个没有类型提示依赖
     * @param resolvedNonClass $parameter
     * @return mixed
     * @throws Exception
     */
    public function resolvedNonClass(resolvedNonClass $parameter)
    {
        if ($parameter->isDefaultValueAvaiable()){
            return $parameter->getDefaultValue();
        }
        throw  new Exception('出错');
    }

    /**通过容器解决依赖
     * @param $parameter
     * @return mixed|object
     */
    public function resolvedClass($parameter)
    {
        return $this->make($parameter->getClass()->name);
    }
}

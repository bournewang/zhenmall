<?php
namespace updc\bean;


class GateWayRequest{
	private $apiInterfaceId;

	private $methodName;

	private $version;

	private $bizContent;

	public function __set($name,$value){
		$this->$name=$value;
	}

	public function __get($name){
		return $this->$name;
	}

}
?>
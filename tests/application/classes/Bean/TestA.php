<?php

class Bean_TestA extends Soter_Bean {

	//id
	private $id;

	//name
	private $name;

	//gid
	private $gid;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getGid() {
		return $this->gid;
	}

	public function setGid($gid) {
		$this->gid = $gid;
		return $this;
	}

}
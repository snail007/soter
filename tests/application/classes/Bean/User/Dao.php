<?php

class Bean_User_Dao extends Soter_Bean {

	//catalog_id
	private $catalog_id;

	//name
	private $name;

	//parent_id
	private $parent_id;

	public function getCatalogId() {
		return $this->catalog_id;
	}

	public function setCatalogId($catalog_id) {
		$this->catalog_id = $catalog_id;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getParentId() {
		return $this->parent_id;
	}

	public function setParentId($parent_id) {
		$this->parent_id = $parent_id;
		return $this;
	}

}
<?php

namespace Core\Driver;

/**
 * 
 * @author 
 */
class FormDriverPGSQL extends FormDriver {
	
	public $form = null;
	
	/**
	 * (non-PHPdoc)
	 * @see \form\php\FormDriver::getFieldType()
	 */
	public function getFieldType($name) {
		$request['request'] = 'SELECT column_name, data_type FROM information_schema.columns WHERE table_name=:table AND column_name=:name;';
		$request['parameters'] = array("table" => strtolower($this->getForm()->table), "name" => strtolower($name));
		return $request;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \form\php\FormDriver::setForm()
	 */
	public function setForm($form) {
		$this->form = $form;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \form\php\FormDriver::getForm()
	 */
	public function getForm() {
		return $this->form;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \form\php\FormDriver::isIndex()
	 */
	public function isIndex($name) {
		$request['request'] = 'SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME=:table AND COLUMN_NAME = :column AND CONSTRAINT_NAME=:constraint;';
		$request['parameters'] = array("table" => strtolower($this->getForm()->table), "constraint" => strtolower($this->getForm()->table) . "_pkey", "column" => $name);
		$data = $this->getForm()->prepareExecute($request["request"], $request["parameters"]);
		
		return count($data) == 1;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \form\php\FormDriver::isAutoGenerated()
	 */
	public function isAutoGenerated($name) {
		$request['request'] = 'SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=:table AND COLUMN_NAME = :column AND COLUMN_DEFAULT LIKE \'next_val(%)\';';
		$request['parameters'] = array("table" => strtolower($this->getForm()->table), "column" => $name);
		$data = $this->getForm()->prepareExecute($request["request"], $request["parameters"]);
	
		return count($data) == 1;
	}
}
<?php

namespace App\FieldTypes\Text;

class TextFieldType
{
	public function beforeValidate($rules, $field)
	{
		

		return $rules;
	}

	public function beforeSave($postData, $field)
	{

		return $postData;
	}

	public function transform($postData, $field)
	{
		
		return $postData;
	}

	public function onEnable()
	{
		// fire off init setup
	}

	public function onDisable()
	{
		// fire off destroy setup
	}
}

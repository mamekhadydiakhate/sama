<?php
namespace App\Definitions;

use Swagger\Annotations as Swg;

/**
 * @Swg\Definition(definition="default")
 */
class DefaultResponse {
	
	
	/**
	 * show the state request
	 * @var boolean
	 * @Swg\Property(type="boolean", description="state request")
	 */
	public $success;
	
	/**
	 * The code response
	 * @var number
	 * @Swg\Property(type="number", description="state request", default=200)
	 */
	public $code;
	
	/**
	 * The content response
	 * @var object
	 * @Swg\Property(type="object")
	 */
	public $data;
}
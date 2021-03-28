<?php
namespace App\Traits;

use App\Libs\Common;

trait ExtendModel {

	public static function getAutoIncrement() {
		return Common::getAutoIncrementId(env('DB_DATABASE'), with(new static)->getTable());
	}
}
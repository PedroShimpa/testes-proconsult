<?php
namespace App\Helpers;

use Carbon\Carbon;

class Helper {

	public static function formatDate($date, $format = 'Y-m-d H:i:s')
	{
		return Carbon::createFromFormat($format, $date) ?? '';
	}
}

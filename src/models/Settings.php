<?php

namespace serieseight\craftiubenda\models;

use Craft;
use craft\base\Model;

/**
 * Iubenda Consent settings
 */
class Settings extends Model
{
	public $stateless = false;
	public $scripts = [];
	public $iframes = [];
}

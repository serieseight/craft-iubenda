<?php

namespace serieseight\craftiubenda;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use yii\base\Event;
use craft\web\View;
use craft\events\TemplateEvent;
use serieseight\craftiubenda\models\Settings;

/**
 * Iubenda Consent plugin
 *
 * @method static Iubenda getInstance()
 * @method Settings getSettings()
 * @author Series Eight <info@serieseight.com>
 * @copyright Series Eight
 * @license MIT
 */
class Iubenda extends Plugin
{
	public string $schemaVersion = '1.0.0';
	public bool $hasCpSettings = true;

	public static function config(): array
	{
		return [
			'components' => [
				// Define component configs here...
			],
		];
	}

	public function init()
	{
		parent::init();

		// check this isn't a control panel request
		if (Craft::$app->getRequest()->getIsCpRequest()) return;

		// replace page output with version processed by Iubenda
		Event::on(
			View::class,
			View::EVENT_AFTER_RENDER_TEMPLATE,
			function (TemplateEvent $event) {
				$event->output = $this->iubenda_replace($event->output);
			}
		);
	}

	protected function createSettingsModel(): ?Model
	{
		return Craft::createObject(Settings::class);
	}

	protected function settingsHtml(): ?string
	{
		return Craft::$app->getView()->renderTemplate('series-eight-iubenda/_settings.twig', [
			'plugin' => $this,
			'settings' => $this->getSettings(),
		]);
	}

	protected function iubenda_replace($html) {
		if (!$html) return $html;

		// test
		// return $html;

		require_once(__DIR__ . '/iubenda-cookie-class/iubenda.class.php');

		$stateless = $this->settings->stateless === '1';

		// if we're not stateless and the user has either given consent or is a bot exit early
		if (!$stateless && (\iubendaParser::consent_given() || \iubendaParser::bot_detected())) {
			return $html;
		}

		// run Iubenda on the provided html
		$iubenda = new \iubendaParser($html, [
			'type' => 'faster',
			'stateless' => $stateless,
			'scripts' => self::reformat_settings($this->settings->scripts, [
				4 => [
					'www.googletagmanager.com/gtm.js'
				]
			]),
			'iframes' => self::reformat_settings($this->settings->iframes)
		]);
		$html = $iubenda->parse();

		return $html;
	}

	// reformat custom tables into format expected by Iubenda
	protected static function reformat_settings($custom, $items = []) {
		if (!$custom) return $items;
		foreach ($custom as $row) {
			$purpose = $row[0];
			$content = $row[1];
			if (!$content) continue;
			if (isset($items[$purpose])) {
				$items[$purpose][] = $content;
			} else {
				$items[$purpose] = [$content];
			}
		}
		return $items;
	}
}

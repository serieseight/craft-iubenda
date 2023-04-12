# Iubenda Consent Manager

# Iubenda Consent Manager for Craft CMS

Handles prior-blocking for the [Iubenda Consent Manager](https://www.iubenda.com/en/) in Craft CMS.

The plugin does not handle any Iubenda configuration or addition of consent manager scripts, just prior blocking functionality.

## Installation

Install the plugin using composer. Currently this requires adding the Git repo to your `composer.json`:

```json
{
	"require": {
		"serieseight/craft-iubenda": "dev-main"
	},
	"repositories":[
		{
			"type": "vcs",
			"url": "git@github.com:serieseight/craft-iubenda.git"
		}
	]
}
```

And running `composer install`.

Then install the plugin with `./craft plugin/install iubenda-consent` or from the Craft control panel.

## Usage

The plugin blocks a set of common tracking scripts by default, as defined by Iubenda. Additional scripts and iframes can be added and categorised from the Plugin Settings.

If your site uses static caching like a CDN or Blitz, you will need to enable the "Stateless" setting. When disabled, the plugin will forgo prior blocking scripts if the user has already consented. This behaviour is incompatible with static caching strategies however.

## Credits

Created by and copyright [Series Eight](https://serieseight.com/).

This project is licensed under ht MIT license. The full license is included at [LICENSE.md](https://github.com/serieseight/craft-iubenda/blob/main/LICENSE.md).

<?php
/**
 * @link https://craftcms.com/
 * @copyright Copyright (c) Pixel & Tonic, Inc.
 * @license MIT
 */

namespace craft\contactform;

use Craft;
use craft\contactform\models\Settings;
use craft\contactform\variables\ContactFormVariable;
use craft\web\twig\variables\CraftVariable;
use yii\base\Event;

/**
 * Class Plugin
 *
 * @property Settings $settings
 * @property Mailer $mailer
 * @method Settings getSettings()
 */
class Plugin extends \craft\base\Plugin
{
    /**
     * @inheritdoc
     */
    public $hasCpSettings = true;

    /**
     * @return Mailer
     */
    public function getMailer(): Mailer
    {
        return $this->get('mailer');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $e) {
            $variable = $e->sender;
            $variable->set('contactform', ContactFormVariable::class);
        });
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        // Get and pre-validate the settings
        $settings = $this->getSettings();
        $settings->validate();

        // Get the settings that are being defined by the config file
        $overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->handle));

        return Craft::$app->view->renderTemplate('contact-form/_settings', [
            'settings' => $settings,
            'overrides' => array_keys($overrides),
        ]);
    }
}

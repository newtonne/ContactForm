<?php

namespace craft\contactform\variables;

use craft\contactform\Plugin;

/**
 * Contact Form variable
 *
 * Makes it possible to use plugin settings in front-end templates
 *
 */
class ContactFormVariable
{
    public function settings()
    {
         return Plugin::getInstance()->getSettings();
    }
}

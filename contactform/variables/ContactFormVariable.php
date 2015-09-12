<?php
namespace Craft;

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
        return craft()->plugins->getPlugin('contactform')->getSettings();
    }
}
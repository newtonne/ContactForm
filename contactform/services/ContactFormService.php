<?php
namespace Craft;

/**
 * Contact Form service
 */
class ContactFormService extends BaseApplicationComponent
{
	/**
	 * Sends an email submitted through a contact form.
	 *
	 * @param ContactFormModel $message
	 * @throws Exception
	 * @return bool
	 */
	public function sendMessage(ContactFormModel $message)
	{
		$settings = craft()->plugins->getPlugin('contactform')->getSettings();

		// Fire an 'onBeforeSend' event
		Craft::import('plugins.contactform.events.ContactFormEvent');
		$event = new ContactFormEvent($this, array('message' => $message));
		$this->onBeforeSend($event);

		if ($event->isValid)
		{
			if (!$event->fakeIt)
			{
				// Get the relevant email address(es) for the message subject
				foreach ($settings->toEmail as $row)
				{
					if ($message->subject == $row['subject'])
					{
						if (!$row['email'])
						{
							throw new Exception('The "To Email" address is not set on the plugin’s settings page.');
						}
						$toEmails = ArrayHelper::stringToArray($row['email']);
					}
				}
				
				foreach ($toEmails as $toEmail)
				{
					$email = new EmailModel();
					$emailSettings = craft()->email->getSettings();

					$email->fromEmail = $emailSettings['emailAddress'];
					$email->replyTo   = $message->fromEmail;
					$email->sender    = $emailSettings['emailAddress'];
					$email->fromName  = $settings->prependSender . ($settings->prependSender && $message->fromName ? ' ' : '') . $message->fromName;
					$email->toEmail   = $toEmail;
					$email->subject   = $settings->prependSubject . ($settings->prependSubject && $message->subject ? ' - ' : '') . $message->subject;
					$email->body      = "An email has been sent by $message->fromName ($message->fromEmail) using the contact form on the website. Here is the message:\n\n".$message->message;


					if ($message->attachment)
					{
						$email->addAttachment($message->attachment->getTempName(), $message->attachment->getName(), 'base64', $message->attachment->getType());
					}

					craft()->email->sendEmail($email);
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Fires an 'onBeforeSend' event.
	 *
	 * @param ContactFormEvent $event
	 */
	public function onBeforeSend(ContactFormEvent $event)
	{
		$this->raiseEvent('onBeforeSend', $event);
	}
}

<?php
namespace Craft;

class ContactFormModel extends BaseModel
{
	protected function defineAttributes()
	{
		return array(
			'fromName'   => array(AttributeType::String, 'required' => true, 'label' => 'Your name'),
			'fromEmail'  => array(AttributeType::Email,  'required' => true, 'label' => 'Your email'),
			'message'    => array(AttributeType::String, 'required' => true, 'label' => 'Message'),
			'subject'    => array(AttributeType::String, 'required' => true, 'label' => 'Subject'),
			'attachment' => AttributeType::Mixed,
		);
	}
}

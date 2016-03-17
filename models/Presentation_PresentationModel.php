<?php
namespace Craft;

/**
 * Class Presentation_PresentationModel
 *
 * @property string $template
 * @property string $handle
 *
 * @package Craft
 */
class Presentation_PresentationModel extends BaseModel
{
    protected function defineAttributes()
    {
        return array(
            'handle'   => array(AttributeType::String),
            'template' => array(AttributeType::String, 'required' => true)
        );
    }
}

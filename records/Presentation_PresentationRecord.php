<?php

namespace Craft;

/**
 * Class Presentation_PresentationRecord
 *
 * @property string $handle
 * @property string $template
 *
 * @package Craft
 */
class Presentation_PresentationRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'presentation';
    }

    protected function defineAttributes()
    {
        return array(
            'handle'   => AttributeType::String,
            'template' => AttributeType::String,
        );
    }
}

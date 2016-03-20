<?php

namespace Presentation\Renderer;

use Craft\EntryModel;
use Craft\Exception;
use Craft\FieldLayoutFieldModel;
use Craft\Presentation_PresentationFieldType;
use Presentation\AbstractRenderer;

/**
 * Class EntryRenderer
 *
 * Tries to find the presentation field in an entry, and returns its
 * parsed template. Adds the entry var to as argument to the template.
 *
 * @package Presentation\Renderer
 */
class EntryRenderer extends AbstractRenderer
{
    /**
     * Iterates over the fields in the entry, and returns the template property of the presentation fieldtype model.
     *
     * @param EntryModel $entry
     *
     * @return string|null
     *
     * @throws Exception
     */
    protected function extractTemplate($entry)
    {
        $template = null;

        // Add the entry to the arguments list so it's available in the template itself.
        $this->addArgument('entry', $entry);


        /** @var FieldLayoutFieldModel[] $fields */
        $fields = $entry->getFieldLayout()->getFields();

        foreach ($fields as $field)
        {
            $fieldType = $field->getField()->getFieldType();

            if ($fieldType instanceof Presentation_PresentationFieldType)
            {
                $fieldHandle = $field->getField()->getAttribute('handle');
                $template = $entry->getFieldValue($fieldHandle)->template;
            }
        }

        return $template;
    }
}

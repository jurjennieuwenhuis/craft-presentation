<?php

namespace Presentation\Renderer;

use Craft\Exception;
use Craft\FieldModel;
use Craft\MatrixBlockModel;
use Craft\Presentation_PresentationModel;
use Presentation\AbstractRenderer;

/**
 * Class MatrixBlockRenderer
 *
 * Iterates over the fields in a Matrix block, and returns the rendered
 * template property of the presentation field type. Adds the block as
 * an argument to the template service.
 *
 * @package Presentation\Renderer
 */
class MatrixBlockRenderer extends AbstractRenderer
{
    /**
     * Iterates over the fields in the entry, and returns the template property of the presentation fieldtype model.
     *
     * @param MatrixBlockModel $block
     *
     * @return string|null
     *
     * @throws Exception
     */
    protected function extractTemplate($block)
    {
        $template = null;

        // Add the $block to the arguments list so it's available in the template itself.
        $this->addArgument('block', $block);

        /** @var $field FieldModel  */
        foreach ($block->getType()->getFields() as $field)
        {
            $fieldValue = $block->getFieldValue($field->getAttribute('handle'));

            if ($fieldValue instanceof Presentation_PresentationModel && isset($fieldValue->template))
            {
                $template = $fieldValue->template;
            }
        }

        return $template;
    }
}

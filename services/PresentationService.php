<?php
namespace Craft;

/**
 * Class PresentationService
 *
 * @package Craft
 */
class PresentationService extends BaseApplicationComponent
{
    public function renderMatrix($matrix, $presentationFieldHandle = null, $blockTypeName = null)
    {
        $renderer = Presentation_BlockRenderer::factory($matrix, $presentationFieldHandle, $blockTypeName);

        return $renderer->render();
    }

    public function renderPresentation($options)
    {
        $renderer = new Presentation_PresentationRenderer(craft()->templates, craft()->path);

        return $renderer->render($options);
    }
}

<?php

namespace Presentation\Renderer;

use Craft\Presentation_PresentationModel;
use Presentation\AbstractRenderer;

/**
 * Class PresentationRenderer
 *
 * Given the Presentation_PresentationModel, it returns the rendered template
 * property of the model.
 *
 * @package Presentation\Renderer
 */
class PresentationRenderer extends AbstractRenderer
{
    /**
     * Return the template property of the Presentation Model.
     *
     * @param Presentation_PresentationModel $presentation
     *
     * @return string|null
     */
    protected function extractTemplate($presentation)
    {
        return $presentation->template;
    }
}

<?php

namespace Presentation;

/**
 * Interface RendererInterface
 * 
 * @package Presentation
 */
interface RendererInterface
{
    /**
     * Returns the rendered template property of the Presentation_PresentationModel
     *
     * @return string
     */
    public function render();
}

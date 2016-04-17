<?php
namespace Craft;

use Presentation\PresentationRendererFactory;

/**
 * Class PresentationService
 *
 * @package Craft
 */
class PresentationService extends BaseApplicationComponent
{
    /**
     * Renders a presentation based on the provided options.
     *
     * @param array $options Array containing render options
     *
     * @return string
     */
    public function renderPresentation($options)
    {
        $factory = PresentationRendererFactory::instance(craft()->templates, craft()->path, craft()->presentation);

        return $factory->getRenderer($options)->render();
    }

    /**
     * Returns the presentation base path.
     *
     * @return string
     */
    public function getPresentationBasePath()
    {
        $pluginSettings = craft()->plugins->getPlugin('presentation')->getSettings();

        $basePath = $pluginSettings->basePath;

        return trim($basePath . '/');
    }
}

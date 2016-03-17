<?php

namespace Craft;

use Presentation\PresentationHelper;


class PresentationPlugin extends BasePlugin
{
    public function init()
    {
        require 'vendor/autoload.php';
    }

    public function getName()
    {
        return Craft::t('Presentation');
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Jurjen Nieuwenhuis';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.kasanova.nl';
    }

    public function defineSettings()
    {
        return [
            'basePath' => [AttributeType::String],
        ];
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('presentation/settings', [
            'settings' => $this->getSettings(),
            'basePathOptionList' => PresentationHelper::getBasePathOptionList(),
        ]);
    }

    /**
     * Register twig extension
     */
    public function addTwigExtension()
    {
        return new PresentationTwigExtension();
    }
}

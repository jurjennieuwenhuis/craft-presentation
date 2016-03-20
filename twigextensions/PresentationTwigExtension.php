<?php

namespace Craft;


class PresentationTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'presentation';
    }

    public function getTokenParsers()
    {
        return [
            new Presentation_RenderPresentation_TokenParser(),
        ];
    }
}

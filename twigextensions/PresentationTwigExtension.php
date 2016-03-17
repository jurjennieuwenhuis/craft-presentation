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
        return array(
            new Presentation_RenderMatrix_TokenParser(),
            new Presentation_RenderPresentation_TokenParser(),
            new Presentation_RenderBlock_TokenParser(),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('renderBlocks', array($this, 'getRenderBlocksFunction')),
        );
    }
    public function getRenderBlocksFunction()
    {
        // stub
    }
}

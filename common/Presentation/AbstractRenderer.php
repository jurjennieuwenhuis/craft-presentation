<?php

namespace Presentation;

use Craft\PathService;
use Craft\TemplatesService;

/**
 * Class AbstractRenderer
 *
 * @package Presentation
 */
abstract class AbstractRenderer implements RendererInterface
{
    /**
     * @var object
     */
    private $presentation;

    /**
     * @var array Containing presentation arguments
     */
    private $arguments = array();

    /**
     * @var TemplatesService
     */
    private $templatesService;

    /**
     * @var PathService
     */
    private $pathService;

    /**
     * Class constructor.
     *
     * @param TemplatesService $templatesService
     * @param PathService $pathService
     * @param object|string $presentation
     * @param array $arguments
     */
    public function __construct(
        TemplatesService $templatesService,
        PathService $pathService,
        $presentation,
        array $arguments = array())
    {
        $this->templatesService = $templatesService ?: craft()->templates;
        $this->pathService = $pathService ?: craft()->path;
        $this->presentation = $presentation;
        $this->setArguments($arguments);
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    public function addArgument($key, $value)
    {
        $this->arguments[$key] = $value;
    }

    /**
     * Renders the fields of the matrix blocks
     *
     * @return string
     */
    public function render()
    {
        $template = $this->extractTemplate($this->presentation);

        if (null === $template)
        {
            return null;
        }

        $html = '';

        if (!empty($template))
        {
            $html .= $this->templatesService->render($template, $this->arguments);
        }

        return $html;
    }

    /**
     * @param object|string $object The object to extract the template path from.
     *
     * @return string|null
     */
    abstract protected function extractTemplate($object);
}

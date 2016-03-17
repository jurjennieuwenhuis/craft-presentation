<?php
namespace Craft;


class Presentation_PresentationRenderer implements Presentation_RendererInterface
{
    /**
     * @var string
     */
    private $template;

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
     * @param TemplatesService|null $templatesService
     * @param PathService|null $pathService
     */
    public function __construct($templatesService = null, $pathService = null)
    {
        $this->templatesService = $templatesService ?: craft()->templates;
        $this->pathService = $pathService ?: craft()->path;
    }

    /**
     * Builds and returns the block renderer.
     *
     * @param array $options
     *
     * @throws \BadMethodCallException
     *
     * @return Presentation_RendererInterface
     */
    public static function factory(array $options = array())
    {
        if (!isset($options['presentation']))
        {
            throw new \BadMethodCallException('Presentation parameter cannot be null');
        }

        $presentation = $options['presentation'];

        unset($options['presentation']);

        $renderer = new self();

        $renderer->setTemplate($presentation);
        $renderer->setArguments($options);

        return $renderer;
    }

    /**
     * @param string|Presentation_PresentationModel $presentation
     */
    public function setTemplate($presentation)
    {
        if ($presentation instanceof Presentation_PresentationModel)
        {
            $presentation = $presentation->template;
        }

        $this->template = $presentation;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Renders the fields of the matrix blocks
     *
     * @param array $options
     * @return string
     */
    public function render(array $options = array())
    {
        $this->setTemplate($options['presentation']);
        $this->setArguments($options);

        if (null === $this->template)
        {
            return null;
        }

        $html = '';

        if (!empty($this->template))
        {
            $html .= $this->templatesService->render($this->template, $this->arguments);
        }

        return $html;
    }
}

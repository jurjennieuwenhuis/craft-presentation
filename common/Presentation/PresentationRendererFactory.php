<?php
namespace Presentation;


use Craft\EntryModel;
use Craft\MatrixBlockModel;
use Craft\PathService;
use Craft\Presentation_PresentationModel;
use Craft\PresentationService;
use Craft\TemplatesService;
use Presentation\Renderer\EntryRenderer;
use Presentation\Renderer\MatrixBlockRenderer;
use Presentation\Renderer\PresentationRenderer;
use Presentation\Renderer\StringRenderer;

/**
 * Class PresentationRendererFactory
 *
 * @package Presentation
 */
class PresentationRendererFactory
{
    /**
     * @var $this
     */
    private static $instance;

    /**
     * @var TemplatesService
     */
    private $templatesService;

    /**
     * @var PathService
     */
    private $pathService;

    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * Create or get instance of class.
     *
     * @param TemplatesService $templatesService
     * @param PathService $pathService
     * @param PresentationService $presentationService
     *
     * @return PresentationRendererFactory
     */
    public static function instance(TemplatesService $templatesService = null, PathService $pathService = null, PresentationService $presentationService = null)
    {
        if (null !== static::$instance)
        {
            return static::$instance;
        }

        return static::$instance = new self($templatesService, $pathService, $presentationService);
    }

    /**
     * Class constructor.
     *
     * @param TemplatesService $templatesService
     * @param PathService $pathService
     * @param PresentationService $presentationService
     */
    public function __construct(TemplatesService $templatesService, PathService $pathService, PresentationService $presentationService)
    {
        $this->templatesService = $templatesService ?: \Craft\craft()->templates;
        $this->pathService = $pathService ?: \Craft\craft()->path;
        $this->presentationService = $presentationService ?: \Craft\craft()->presentation;
    }

    /**
     * Returns a renderer based on the 'presentation' key in the options array.
     *
     * @param array $options
     *
     * @return null|RendererInterface
     *
     * @throws \Craft\Exception
     */
    public function getRenderer(array $options = array())
    {
        $presentation = $options['presentation'];

        if ($presentation instanceof Presentation_PresentationModel)
        {
            return new PresentationRenderer($this->templatesService, $this->pathService, $this->presentationService, $presentation, $options);
        }
        else if ($presentation instanceof EntryModel)
        {
            return new EntryRenderer($this->templatesService, $this->pathService, $this->presentationService, $presentation, $options);
        }
        else if ($presentation instanceof MatrixBlockModel)
        {
            return new MatrixBlockRenderer($this->templatesService, $this->pathService, $this->presentationService, $presentation, $options);
        }
        else if (is_string($presentation))
        {
            return new StringRenderer($this->templatesService, $this->pathService, $this->presentationService, $presentation, $options);
        }

        throw new \Craft\Exception(\Craft\Craft::t('No renderer available for provided object'));
    }
}

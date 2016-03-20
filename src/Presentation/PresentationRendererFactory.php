<?php
namespace Presentation;


use Craft\EntryModel;
use Craft\MatrixBlockModel;
use Craft\PathService;
use Craft\Presentation_PresentationModel;
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
     * Create or get instance of class.
     *
     * @param TemplatesService $templatesService
     * @param PathService $pathService
     *
     * @return PresentationRendererFactory
     */
    public static function instance(TemplatesService $templatesService, PathService $pathService)
    {
        if (null !== static::$instance)
        {
            return static::$instance;
        }

        return static::$instance = new self($templatesService, $pathService);
    }

    /**
     * Class constructor.
     *
     * @param TemplatesService $templatesService
     * @param PathService $pathService
     */
    public function __construct(TemplatesService $templatesService, PathService $pathService)
    {
        $this->templatesService = $templatesService ?: craft()->templates;
        $this->pathService = $pathService ?: craft()->path;
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
            return new PresentationRenderer($this->templatesService, $this->pathService, $presentation, $options);
        }
        else if ($presentation instanceof EntryModel)
        {
            return new EntryRenderer($this->templatesService, $this->pathService, $presentation, $options);
        }
        else if ($presentation instanceof MatrixBlockModel)
        {
            return new MatrixBlockRenderer($this->templatesService, $this->pathService, $presentation, $options);
        }
        else if (is_string($presentation))
        {
            return new StringRenderer($this->templatesService, $this->pathService, $presentation, $options);
        }

        throw new \Craft\Exception(\Craft\Craft::t('No renderer available for provided object'));
    }
}

<?php
namespace Presentation;

class BlockRenderer implements RendererInterface
{
    private static $instance;

    /**
     * @var \Craft\MatrixBlockModel
     */
    private $block;

    /**
     * @var array Containing presentation arguments
     */
    private $arguments = array();

    /**
     * Returns an instance of this class.
     *
     * @return BlockRenderer
     */
    public static function instance()
    {
        if (null !== self::$instance)
        {
            return self::$instance;
        }

        return self::$instance = new self();
    }

    /**
     * @param \Craft\MatrixBlockModel $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    /**
     * Returns the selected presentation of a matrix block.
     *
     * @return string|null
     */
    public function getPresentation()
    {
        $content = $this->block->getContent();

        $attributes = $content->getAttributes(array('presentation'));
        $presentation = $attributes['presentation'];

        if ($presentation instanceof \Craft\Presentation_PresentationModel)
        {
            $value = $presentation->getAttribute('presentation');
            return $value;
        }

        return null;
    }

    public function setArguments(array $arguments = array())
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
        $this->setBlock($options['block']);
        $this->setArguments($options);

        if (!$this->block instanceof \Craft\MatrixBlockModel)
        {
            return null;
        }

        $html = '';

        $presentation = $this->getPresentation();

        if (!empty($presentation))
        {
            $html .= \Craft\craft()->templates->render($presentation, $this->arguments);
        }

        return $html;
    }
}

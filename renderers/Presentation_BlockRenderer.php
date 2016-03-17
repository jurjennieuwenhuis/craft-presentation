<?php
namespace Craft;


class Presentation_BlockRenderer implements Presentation_RendererInterface
{
    const DEFAULT_PRESENTATION_FIELD_HANDLE = 'presentation';

    const DEFAULT_BLOCK_TYPE_NAME = 'block';

    /**
     * @var MatrixBlockModel[]|array
     */
    private $matrix;

    /**
     * @var string
     */
    private $presentationFieldHandle;

    /**
     * @var string
     */
    private $blockTypeName;

    /**
     * @var EntryModel
     */
    private $entry;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // set defaults
        $this->setBlockTypeName(self::DEFAULT_BLOCK_TYPE_NAME);

        $this->setPresentationFieldHandle(self::DEFAULT_PRESENTATION_FIELD_HANDLE);
    }

    /**
     * Builds and returns the block renderer.
     *
     * @param ElementCriteriaModel|MatrixBlockModel[] $matrix
     * @param string|null $presentationFieldHandle
     * @param string|null $blockTypeName
     *
     * @throws \BadMethodCallException
     *
     * @return Presentation_RendererInterface
     */
    public static function factory($matrix, $presentationFieldHandle = null, $blockTypeName = null)
    {
        if (null === $matrix)
        {
            throw new \BadMethodCallException('Matrix parameter cannot be null');
        }

        $renderer = new self();

        $renderer->setMatrix($matrix);

        if (null !== $presentationFieldHandle)
        {
            $renderer->setPresentationFieldHandle($presentationFieldHandle);
        }

        if (null !== $blockTypeName)
        {
            $renderer->setBlockTypeName($blockTypeName);
        }

        return $renderer;
    }

    /**
     * @param string $blockTypeName
     */
    public function setBlockTypeName($blockTypeName)
    {
        $this->blockTypeName = $blockTypeName;
    }

    /**
     * @param EntryModel $entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @param ElementCriteriaModel $matrix
     */
    public function setMatrix($matrix)
    {
        if ($matrix instanceof ElementCriteriaModel)
        {
            $matrix = $matrix->find();
        }

        $this->matrix = $matrix;
    }

    /**
     * @param string $presentationFieldHandle
     */
    public function setPresentationFieldHandle($presentationFieldHandle)
    {
        $this->presentationFieldHandle = $presentationFieldHandle;
    }

    /**
     * Renders the fields of the matrix blocks
     *
     * @return string
     */
    public function render()
    {
        if (null === $this->matrix)
        {
            return null;
        }

        $html = '';

        /** @var $block MatrixBlockModel */
        foreach ($this->matrix as $block)
        {
            $presentation = null;

            /** @var $field FieldModel  */
            foreach ($block->getType()->getFields() as $field)
            {
                $fieldValue = $block->getFieldValue($field->handle);

                if ($fieldValue && isset($fieldValue->presentation))
                {
                    $presentation = $fieldValue->presentation;
                }
            }

            if (!empty($presentation))
            {
                $html .= craft()->templates->render($presentation, array(
                    $this->blockTypeName => $block->getFieldValue($block->getType()->handle),
                ));
            }
        }

        return $html;
    }
}

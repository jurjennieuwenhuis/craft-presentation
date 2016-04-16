<?php
namespace Presentation\Renderer;


use Craft\Exception;
use Presentation\AbstractRenderer;

/**
 * Class StringRenderer
 *
 * Accepts the presentation template as a path, and returns it as-is.
 *
 * @package Presentation\Renderer
 */
class StringRenderer extends AbstractRenderer
{
    /**
     * Iterates over the fields in the entry, and returns the template property of the presentation fieldtype model.
     *
     * @param string $path
     *
     * @return string|null
     *
     * @throws Exception
     */
    protected function extractTemplate($path)
    {
        return $path;
    }
}

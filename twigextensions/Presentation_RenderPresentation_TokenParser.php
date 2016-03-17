<?php
namespace Craft;
use Presentation\Twig\BaseTokenParser;

/**
 * Class Presentation_RenderPresentation_TokenParser
 *
 * Renders the content of a block types within a matrix, using the assigned template field type.
 *
 * Usage:
 *
 * <pre>
 *  {% renderBlock var='content' block=block %}
 *  {% renderBlock block %}
 * </pre>
 *
 * @author    Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Copyright (c) 2015, Jurjen Nieuwenhuis.
 * @package   craft.plugins.presentation.twigextensions
 * @since     1.0
 */
class Presentation_RenderPresentation_TokenParser extends BaseTokenParser
{
	/**
	 * Defines the tag name.
	 *
	 * @return string
	 */
	public function getTag()
	{
		return 'renderPresentation';
	}

	protected function getNodeClass()
	{
		return '\Craft\Presentation_RenderPresentation_Node';
	}
}

<?php
namespace Craft;

use Presentation\Twig\BaseTokenParser;

/**
 * Class Presentation_RenderMatrix_TokenParser
 *
 * Renders the content of a block types within a matrix, using the assigned template field type.
 *
 * Usage:
 *
 * <pre>
 *  {% renderMatrix var='contentBody' matrix=entry.content_matrix template='template' blockType='block' %}
 *  {% renderMatrix entry.content_matrix' %}
 * </pre>
 *
 * @author    Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Copyright (c) 2015, Jurjen Nieuwenhuis.
 * @package   craft.plugins.presentation.twigextensions
 * @since     1.0
 */
class Presentation_RenderMatrix_TokenParser extends BaseTokenParser
{
	protected function getNodeClass()
	{
		return '\Craft\Presentation_RenderMatrix_Node';
	}

	/**
	 * Parses {% renderMatrix %} tags.
	 *
	 * @param \Twig_Token $token
	 *
	 * @return Exit_Node
	 */
	public function parse(\Twig_Token $token)
	{
        $arguments = $this->getArguments();

		$lineno = $token->getLine();

		return new Presentation_RenderMatrix_Node(new \Twig_Node($arguments), $lineno, $this->getTag());
	}

	/**
	 * Defines the tag name.
	 *
	 * @return string
	 */
	public function getTag()
	{
		return 'renderMatrix';
	}
}

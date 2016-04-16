<?php
namespace Presentation\Twig;

/**
 * Class BaseTokenParser
 *
 * @author    Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Copyright (c) 2015, Jurjen Nieuwenhuis.
 * @package   Essentials\Twig
 * @since     1.0
 */
abstract class BaseTokenParser extends \Twig_TokenParser
{
    /**
     * @return string
     */
    abstract protected function getNodeClass();

    /**
     * Parses {% renderPresentation %} tags.
     *
     * @param \Twig_Token $token
     *
     * @return BaseNode
     */
    public function parse(\Twig_Token $token)
    {
        $arguments = $this->getArguments();

        $lineno = $token->getLine();

        $class = $this->getNodeClass();

        return new $class(new \Twig_Node($arguments), $lineno, $this->getTag());
    }

    /**
     * Parses a twig tag into a list of key/value arguments.
     *
     * Handles arguments of the 'key=value' kind as well as just the 'value' kind.
     * If the argument doesn't have a key, a index is used.
     *
     * For example:
     *
     * <pre>
     *   {% renderMatrix matrix=entry.content_matrix template='template' %}
     *   {% renderMatrix entry.content_matrix template='template' %}
     *   {% renderMatrix var='content' entry.content_matrix template='template' %}
     * </pre>
     *
     * @return \Twig_Node[]|array
     */
    protected function getArguments()
    {
        $arguments  = array();
        $count = 0;

        $stream = $this->parser->getStream();

        $end = false;
        $idx = 0;

        while (!$end)
        {
            // Check if the next token is the operator '='. If it is, we'll store this
            // token and the token after the '=' as a key/value argument
            if ($stream->look(1)->test(\Twig_Token::OPERATOR_TYPE, '='))
            {
                $arguments[$count]['name']  = $stream->getCurrent()->getValue();
                $stream->next();
                $stream->next();
                $arguments[$count]['value'] = $this->parser->getExpressionParser()->parseExpression();
                $count++;
            }

            // check if we've reached the end of the tag
            elseif ($stream->test(\Twig_Token::BLOCK_END_TYPE))
            {
                $end = true;
            }

            // stand-alone value. Store this with an index as key.
            else
            {
                $arguments[$count]['name'] = $idx;
                $arguments[$count]['value'] = $this->parser->getExpressionParser()->parseExpression();

                $idx++;
                $count++;
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return $arguments;
    }
}

<?php
namespace Presentation\Twig;

/**
 * Class BaseNode
 *
 * @author    Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Copyright (c) 2014, Jurjen Nieuwenhuis.
 * @package   Essentials\Twig
 * @since     1.0
 */
abstract class BaseNode extends \Twig_Node
{
    /**
     * @var \Twig_Node[]
     */
    protected $arguments = array();

    /**
     * @var bool
     */
    protected $strict = true;

    /**
     * Define the argument keys and their properties.
     *
     * For now the properties are only used for checking required parameters.
     *
     * <pre>
     *  return array(
     *    'var'      => array(),
     *    'matrix'   => array('required' => true),
     *    'template' => array(),
     *    'blockType' => array(),
     *  );
     * </pre>
     *
     * @return array
     */
    abstract protected function defineArguments();

    /**
     * Class constructor.
     *
     * @param \Twig_Node $arguments
     * @param array $lineno
     * @param string|null $tag
     */
    public function __construct(\Twig_Node $arguments, $lineno, $tag = null)
    {
        $this->arguments = $arguments;

        parent::__construct(array(), array(), $lineno, $tag);
    }

    /**
     * Sorts and validates the arguments based on the argument map.
     *
     * If the property '$strict' is set to true, the non-defined arguments are dropped from the resulting array.
     *
     * @param array $arguments
     *
     * @throws \Twig_Error_Syntax
     *
     * @return mixed
     */
    protected function mapArguments(array $arguments)
    {
        $args = array();

        $argumentMap = $this->defineArguments();

        foreach ($argumentMap as $key => $props)
        {
            if (isset($arguments[$key]))
            {
                $args[$key] = $arguments[$key];
            }
            else
            {
                if (isset($props['required']) && $props['required'] == true)
                {
                    throw new \Twig_Error_Syntax("The required argument '$key' is missing.");
                }
            }
        }

        if (!$this->strict)
        {
            // prepend dynamic arguments
            $remaining = array_diff($arguments, $args);
            $args = array_merge($args, $remaining);
        }

        return $args;
    }



    /**
     * Compile an array of the provided arguments
     *
     * @param \Twig_Compiler $compiler
     * @param array $arguments
     */
    protected function compileArray(\Twig_Compiler $compiler, array $arguments)
    {
        if (($length = count($arguments)) < 1)
        {
            $compiler->raw('array()');

            return;
        }

        $idx = 0;

        $compiler->raw('array("');

        foreach ($arguments as $key => $value)
        {
            // skip the 'special' key 'var' since it is only used to
            // return the resulting value
            if ('var' === $key)
            {
                $idx++;
                continue;
            }

            $compiler
                ->raw($key)
                ->raw('"=>')
                ->subcompile($value);

            if ($idx < ($length - 1))
            {
                $compiler->raw(',"');
            }
            $idx++;
        }

        $compiler->raw(')');
    }


    /**
     * Compile an array of the provided arguments and add as a single option array
     *
     * @param \Twig_Compiler $compiler
     * @param array $arguments
     */
    protected function compileOptionArray(\Twig_Compiler $compiler, array $arguments)
    {
        if (($length = count($arguments)) < 1)
        {
            $compiler->raw('array()');

            return;
        }

        $idx = 0;

        $compiler->raw('array(array("');

        foreach ($arguments as $key => $value)
        {
            // skip the 'special' key 'var' since it is only used to
            // return the resulting value
            if ('var' === $key)
            {
                $idx++;
                continue;
            }

            $compiler
                ->raw($key)
                ->raw('"=>')
                ->subcompile($value);

            if ($idx < ($length - 1))
            {
                $compiler->raw(',"');
            }
            $idx++;
        }

        $compiler->raw('))');
    }

    /**
     * Compile the return expression: echo or return value.
     *
     * @param \Twig_Compiler $compiler
     * @param array $arguments
     */
    protected function compileReturnExpression(\Twig_Compiler $compiler, array $arguments)
    {
        if (isset($arguments['var']) && !empty($arguments['var']))
        {
            $compiler
                ->raw('$context[')
                ->subcompile($arguments['var'])
                ->raw('] = ')
            ;
        }
        else
        {
            $compiler->raw('echo ');
        }
    }
}

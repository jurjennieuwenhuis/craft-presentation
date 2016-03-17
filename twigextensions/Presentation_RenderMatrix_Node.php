<?php

namespace Craft;

use Presentation\Twig\BaseNode;

/**
 * Class JuniTemplates_RenderMatrix_Node
 *
 * @author    Jurjen Nieuwenhuis <jurjennieuwenhuis@gmail.com>
 * @copyright Copyright (c) 2014, Jurjen Nieuwenhuis.
 * @package   craft.plugins.junitemplates.twigextensions
 * @since     1.0
 */
class Presentation_RenderMatrix_Node extends BaseNode
{
    /**
     * Define the argument keys and their properties.
     *
     * For now the properties are only used for checking required parameters.
     *
     * @return array
     */
    protected function defineArguments()
    {
        return array(
            'var'       => array(),
            'matrix'    => array('required' => true),
            'template'  => array(),
            'blockType' => array(),
        );
    }

    /**
     * Compiles a Exit_Node into PHP.
     *
     * @param \Twig_Compiler $compiler
     *
     * @throws \Twig_Error_Syntax
     *
     * @return null
     */
	public function compile(\Twig_Compiler $compiler)
	{
        $compiler
            ->addDebugInfo($this)
            ->addIndentation();

        $args = array();

        foreach ($this->arguments as $arg)
        {
            $args[$arg['name']] = $arg['value'];
        }

        // test if argument 'matrix' without key prefix is used
        if (isset($args[0]))
        {
            $args['matrix'] = $args[0];
        }

        // map and validate arguments
        $args = $this->mapArguments($args);

        // whether to print out the returning value, or just return it, based on the 'var' argument
        $this->compileReturnExpression($compiler, $args);

        $compiler->raw(' call_user_func_array(array(\Craft\craft()->presentation, "renderMatrix"), ');

        $this->compileArray($compiler, $args);

        $compiler->raw(");\n");
	}
}

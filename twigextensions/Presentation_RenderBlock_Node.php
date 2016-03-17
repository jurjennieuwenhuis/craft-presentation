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
class Presentation_RenderBlock_Node extends BaseNode
{
    protected $strict = false;

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
            'var'          => array(),
            'block'        => array('required' => true),
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

        // test if argument 'presentation' without key prefix is used
        if (isset($args[0]))
        {
            $args['block'] = $args[0];
        }

        // map and validate arguments
        $args = $this->mapArguments($args);

        // whether to print out the returning value, or just return it, based on the 'var' argument
        $this->compileReturnExpression($compiler, $args);

        $compiler->raw(' call_user_func_array(array(\Presentation\BlockRenderer::instance(), "render"), ');

        //$this->compileArray($compiler, $args);
        $this->compileOptionArray($compiler, $args);

        $compiler->raw(");\n");
	}
}

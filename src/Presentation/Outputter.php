<?php
/**
 * Created by PhpStorm.
 * User: pvandriel
 * Date: 16/03/16
 * Time: 20:34
 */

namespace Presentation;


class Outputter
{
    private static $instance;

    /**
     * @var \Craft\PathService
     */
    private $pathService;

    public function __construct(\Craft\PathService $pathService)
    {
        $this->pathService = $pathService;
    }

    public function output($message)
    {
        return $message;
    }

    public function getTemplatesPath()
    {
        return $this->pathService->getTemplatesPath();
    }

    public static function instance()
    {
        if (null !== static::$instance) {
            return null;
        }

        static::$instance = new self(\Craft\craft()->path);

        return static::$instance;
    }

}


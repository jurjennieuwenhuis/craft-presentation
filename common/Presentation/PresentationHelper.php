<?php

namespace Presentation;

use Craft\IOHelper;

/**
 * Class PresentationHelper
 *
 * @package Craft
 */
class PresentationHelper
{
    /**
     * Returns an key/value pair array with paths to choose from. The paths are relative to Craft's
     * site templates.
     *
     * @return array
     */
    public static function getBasePathOptionList()
    {
        return static::getFolderOptionList(static::getSiteTemplatesPath());
    }

    /**
     * Returns an array with template paths relative to the base presentation folder.
     *
     * Dependent on the plugin settings, this can be the craft site template path or a sub folder defined in
     * the plugin settings.
     *
     * @param string $basePath Optional path relative to Craft's template folder.
     *
     * @return array
     */
    public static function getTemplateLocationOptionList($basePath = null)
    {
        $path = static::getBasePath($basePath);

        return static::getFolderOptionList($path);
    }

    /**
     * Returns an option list of the presentation template available in the provided template location.
     *
     * @param string $templateLocation The folder containing the templates relative to the presentation base path.
     * @param string $basePath The base path.
     *
     * @return array
     */
    public static function getTemplateOptionList($templateLocation, $basePath)
    {
        $optionList = [];

        if (empty($templateLocation))
        {
            return $optionList;
        }

        // get list of templates within the template location
        $path = static::getSiteTemplatesPath() . '/' . $basePath . '/' . $templateLocation;

        $templatePaths = IOHelper::getFolderContents($path, false, '\.(twig|html)$');

        if (!$templatePaths || count($templatePaths) <= 0)
        {
            return $optionList;
        }

        foreach ($templatePaths as $templatePath)
        {
            $relPath = $templateLocation . '/' . basename($templatePath);
            $optionList[$relPath] = static::prettifyPresentationName(basename($templatePath));
        }

        return $optionList;
    }

    /**
     * @todo
     *
     * Returns a JSON string containing all the template locations and the presentation template therein.
     *
     * @param string $basePath Optional path relative to Craft's template folder.
     *
     * @return string
     */
    public static function getAllTemplatesJSON($basePath = null)
    {
        $path = static::getBasePath($basePath);
    }

    /**
     * Returns the path service.
     *
     * @return \Craft\PathService
     */
    private static function getPathService()
    {
        return \Craft\craft()->path;
    }

    /**
     * Returns Craft's site templates path, without trailing slash
     *
     * @return string
     */
    private static function getSiteTemplatesPath()
    {
        return rtrim(static::getPathService()->getSiteTemplatesPath(), '/\\');
    }

    /**
     * Returns the base path - the root folder of the presentation template folders,
     * relative to Craft's site template folder.
     *
     * @param string $basePath Optional path relative to Craft's template folder.
     *
     * @return string
     */
    public static function getBasePath($basePath = null)
    {
        $path = static::getSiteTemplatesPath();

        if (null !== $basePath)
        {
            $path .= '/' . trim($basePath, '/');
        }

        return $path;
    }

    /**
     * Returns an option list of non-empty folders in the provided folder path.
     *
     * @param string $path
     *
     * @return array
     */
    private static function getFolderOptionList($path)
    {
        $optionList = [];

        $folderContents = IOHelper::getFolderContents($path);

        if (!is_array($folderContents))
        {
            return $optionList;
        }

        foreach ($folderContents as $folder)
        {
            // Skip files and empty folders
            if (!is_dir($folder) || IOHelper::isFolderEmpty($folder))
            {
                continue;
            }

            // Get the path relative to Craft's site template folder. We will use it to store
            // the path to the presentation template.
            $relPath = str_replace($path, '', $folder);

            // Remove any trailing slashes
            $relPath = trim($relPath, '/');

            // We'll only want the folder name, not the complete path
            $folderName = str_replace($path, '', $folder);

            $optionList[$relPath] = static::prettifyFolderName($folderName);
        }

        return $optionList;
    }

    /**
     * Returns a human readable name of the template, based on the file name.
     *
     * @param string $filename
     * @return string
     */
    private static function prettifyPresentationName($filename)
    {
        // strip off extension (we only use .twig and .html files)
        $parts = explode('.', $filename);

        $ext = $parts[count($parts) - 1];

        $handle = basename($filename, '.' . $ext);

        // replace dashes with underscores - if applicable
        $handle = str_replace('-', '_', $handle);

        // replace any capital letters with the lowercase and add an underscore
        // E.g. RecentPostList becomes _recent_post_list
        $handle = preg_replace_callback('/([A-Z])/', function($matches)
        {
            return '_' . strtolower($matches[1]);
        }, $handle);

        // strip off preceding and trailing underscores
        $handle = trim($handle, '_');

        // Replace underscores with spaces
        $handle = str_replace('_', ' ', $handle);

        // Capitalize each word in handle
        $handle = ucwords($handle);

        return $handle;
    }

    /**
     * Return the folder name with dots as directory separator.
     *
     * @param string $folderName
     * @return string mixed
     */
    private static function prettifyFolderName($folderName)
    {
        $folderName = trim($folderName, '/');

        return str_replace('/', '.', $folderName);
    }
}

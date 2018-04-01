<?php namespace Newride\InlineAssets;

use Backend;
use Cms\Classes\Theme;
use System\Classes\CombineAssets;
use System\Classes\PluginBase;

/**
 * InlineAssets Plugin Information File
 */
class Plugin extends PluginBase
{
    public static function assetsCacheId(array $assets): string
    {
        $name = CombineAssets::combine($assets);
        $name = explode('/', $name);
        $name = array_pop($name);

        [$cacheId,] = explode('-', $name);

        return $cacheId;
    }

    public static function normalizeAssetPaths(array $assets): array
    {
        $themePath = Theme::getActiveTheme()->getPath();

        return array_map(function (string $path) use ($themePath) {
            return $themePath.'/'.$path;
        }, $assets);
    }

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'InlineAssets',
            'description' => 'Optimize assets delivery by inlining files.',
            'author'      => 'Newride',
            'icon'        => 'icon-leaf'
        ];
    }

    public function inlineAssets(array $assets): string
    {
        $assets = static::normalizeAssetPaths($assets);
        $cacheId = static::assetsCacheId($assets);

        $combiner = CombineAssets::instance();

        return $combiner->getContents($cacheId)->getContent();
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'inline' => [$this, 'inlineAssets']
            ],
        ];
    }
}

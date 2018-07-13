<?php namespace Newride\InlineAssets;

use Backend;
use Cms\Classes\Theme;
use Illuminate\Support\Str;
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

        $chunks = explode('-', $name);
        $cacheId = $chunks[0];

        return $cacheId;
    }

    public static function isAlias(string $path): bool
    {
        return Str::startsWith($path, '@');
    }

    public static function isExternalPath(string $path): bool
    {
        return Str::startsWith($path, '~')
            || Str::startsWith($path, '$');
    }

    public static function isSpecial(string $path): bool
    {
        return static::isAlias($path) || static::isExternalPath($path);
    }

    public static function normalizeAssetPaths(array $assets): array
    {
        $themePath = Theme::getActiveTheme()->getPath();

        return array_map(function (string $path) use ($themePath) {
            if (static::isSpecial($path)) {
                return $path;
            }

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
            'icon'        => 'icon-leaf',
            'homepage'    => 'https://github.com/newridetech/octobercms-inline-assets'
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

<?php

namespace bexvibi\SettingsJs\Generators;

use Exception;
use Illuminate\Filesystem\Filesystem as File;
use JShrink\Minifier;

/**
 * The SettingsJSGenerator class.
 *
 * @author  Rubens Mariuzzo <rubens@mariuzzo.com>
 */
class SettingsJsGenerator
{
    /**
     * The file service.
     *
     * @var File
     */
    protected $file;

    /**
     * The source path of the settings.
     *
     * @var string
     */
    protected $sourcePath;

    /**
     * Construct a new SettingsJSGenerator instance.
     *
     * @param File $file The file service instance.
     * @param string $sourcePath The source path of the settings.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Generate a JS Settings file from all settings.
     *
     * @param string $target The target directory.
     * @param array $options Array of options.
     *
     * @return int
     */
    public function generate($target, $options)
    {
        $settings = $this->getSettings();

        if ($options['no-lib']) {
            $template = $this->file->get(__DIR__ . '/Templates/settings.js');
        } else if ($options['json']) {
            $template = $this->file->get(__DIR__ . '/Templates/settings.json');
        } else {
            $template = $this->file->get(__DIR__ . '/../lib/settings.min.js');
        }

        $template = str_replace('\'{ settings }\'', json_encode($settings), $template);

        if ($options['compress']) {
            $template = Minifier::minify($template);
        }

        return $this->file->put($target, $template);
    }

    /**
     * Return all settings.
     *
     * @param bool $noSort Whether sorting of the settings should be skipped.
     * @return array
     *
     * @throws Exception
     */
    protected function getSettings()
    {
        $array = \Settings::getAll();

        if (is_array(config('settings-js.exclude_keys')) && !empty(config('settings-js.exclude_keys')))
            return $this->filterExcludedSettings($array, config('settings-js.exclude_keys'));

        return $array;
    }

    /**
     * With filter excluded keys from setting array.
     *
     * @return array|string of wildcard's
     *
     * @throws Exception
     */

    function filterExcludedSettings(array $array, $wildcard)
    {
        array_walk($array, function ($value, $key) use (&$arr, $wildcard) {
            if (is_array($wildcard))
                $wildcard = implode('|', $wildcard);

            $wildcard = str_replace('*', '.*', $wildcard);

            if (!preg_match('/^' . $wildcard . '$/i', $key)) {
                if (is_array($value))
                    return $arr[$key] = $this->filterExcludedSettings($value, $wildcard);
                else
                    $arr[$key] = $value;
            }
        });

        return $arr;
    }
}

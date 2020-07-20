<?php

namespace bexvibi\SettingsJs\Generators;

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
        $messages = $this->getSettings();

        if ($options['no-lib']) {
            $template = $this->file->get(__DIR__ . '/Templates/settings.js');
        } else if ($options['json']) {
            $template = $this->file->get(__DIR__ . '/Templates/settings.json');
        } else {
            $template = $this->file->get(__DIR__ . '/Templates/js_with_settings.js');
            $settingsJs = $this->file->get(__DIR__ . '/../lib/settings.min.js');
            $template = str_replace('\'{ settingsjs }\';', $settingsJs, $template);
        }

        $template = str_replace('\'{ settings }\'', json_encode($messages), $template);

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
     * @throws \Exception
     */
    protected function getSettings()
    {
        return \Settings::getAll();
    }
}

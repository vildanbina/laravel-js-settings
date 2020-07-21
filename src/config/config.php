<?php

return [
    /*
     * The default path to use for the generated javascript.
     */
    'path' => public_path('settings.js'),

    /*
     * Excluded settings keys to render on js.
     *
     * Accept string or array
     *
     * eg.  ['*_smtp']
     */
    'exclude_keys' => [
        'smtp_*',
        'doctors_per_page'
    ],

        /*
     * Extra keys we want to add on JS
     *
     * Accept array
     *
     * eg.  ['admin_path' => 'admin']
     */
    'extra_keys' => [],
];

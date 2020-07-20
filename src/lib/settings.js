(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        // AMD support.
        define([], factory);
    } else if (typeof exports === 'object') {
        // NodeJS support.
        module.exports = factory();
    } else {
        // Browser global support.
        root.Settings = factory();
    }

}(this, function () {
    'use strict';


    var Settings = function (options) {
        options = options || {};
        this.settings = options.settings;
    };

    /**
     * Set settings source.
     *
     * @param settings {object} The settings source.
     *
     * @return void
     */
    Settings.prototype.setSettings = function (settings) {
        this.settings = settings;
    };

    /**
     * This method act as an alias to get() method.
     *
     * @param key {string} The key of the setting.
     *
     * @return {boolean} true if the given key is defined on the settings source, otherwise false.
     */
    Settings.prototype.has = function (key) {
        if (typeof key !== 'string' || !this.settings) {
            return false;
        }

        return this.get(key) !== null;
    };

    /**
     * Get a translation setting.
     *
     * @param key {string} The key of the setting.
     * @param replacements {object} The replacements to be done in the setting.
     * @param locale {string} The locale to use, if not passed use the default locale.
     *
     * @return {string} The translation setting, if not found the given key.
     */
    Settings.prototype.get = function (key, defaultValue = null) {
        // console.log(this.settings);
        return this.settings[key] || defaultValue;
    };

    return Settings;

}));

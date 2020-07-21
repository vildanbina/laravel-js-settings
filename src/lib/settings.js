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
     * Parse a setting key into dots.
     *
     * @return {string} A key object with source and entries properties.
     * @param path
     * @param obj
     * @param seperator
     */
    Settings.prototype._parseKey = function (path, obj = this.settings, seperator = '.') {
        var properties = Array.isArray(path) ? path : path.split(seperator);
        return properties.reduce((prev, curr) => prev && prev[curr], obj);
    };

    /**
     * Get a translation setting.
     *
     * @param key {string} The key of the setting.
     * @param defaultValue
     *
     * @return {string} The translation setting, if not found the given key.
     */
    Settings.prototype.get = function (key, defaultValue = null) {
        return this._parseKey(key) || defaultValue;
    };

    Settings = new Settings();
    Settings.setSettings('{ settings }');

    return Settings;
}));

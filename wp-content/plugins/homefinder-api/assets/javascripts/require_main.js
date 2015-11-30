requirejs.config({
    baseUrl: "/wp-content/themes/cntheme/assets/js",

    paths: {
        jquery: "vendor/jquery/dist/jquery.min",
        underscore: "vendor/underscore/underscore-min",
        backbone: "vendor/backbone/backbone-min",
        json2: "vendor/json2/json2",
        marionette: "vendor/marionette/lib/backbone.marionette.min"
    },

    shim: {
        underscore: {
            exports: "_"
        },
        backbone: {
            deps: ["jquery", "underscore", "json2"],
            exports: "Backbone"
        },
        marionette: {
            deps: ["backbone"],
            exports: "Marionette"
        }
    }
});

require(["app"], function (HomeFinderManager) {
    HomeFinderManager.start();
});


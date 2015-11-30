define(['marionette'], function (Marionette) {
    var ItemView = Marionette.ItemView.extend({
        template: '#itemTemplate',
        // wrap the view with `tr` instead of `div`
        tagName: 'tr'
    });

    return ItemView;
});

define(['marionette', 'views/property_in_list_view'], function (Marionette, ItemView) {
    var CollectionView = Marionette.CollectionView.extend({
        // Specify the itemView to render each item
        itemView: ItemView,
        // wrap the view with `tbody` instead of `div`
        tagName: 'tbody'
    });

    return CollectionView;
});

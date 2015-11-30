define(['backbone'], function(Backbone) {
    var Property = Backbone.Model.extend({
        promptHere : function() {
            alert('here');
        }
    });

    var property = new Property();

    return property;
});
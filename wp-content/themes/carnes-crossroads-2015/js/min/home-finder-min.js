Number.prototype.formatMoney=function(e,i,t){var n=this,e=isNaN(e=Math.abs(e))?2:e,i=void 0==i?".":i,t=void 0==t?",":t,s=n<0?"-":"",o=parseInt(n=Math.abs(+n||0).toFixed(e))+"",r=(r=o.length)>3?r%3:0;return s+(r?o.substr(0,r)+t:"")+o.substr(r).replace(/(\d{3})(?=\d)/g,"$1"+t)+(e?i+Math.abs(n-o).toFixed(e).slice(2):"")},jQuery(function($){function e(){filters=document.getElementById("legend-items"),checkboxes=document.getElementsByClassName("squared-checkbox"),map=L.mapbox.map("map","mapbox.streets",{minZoom:15,maxZoom:17}),layer=L.mapbox.featureLayer().addTo(map);for(var e={type:"FeatureCollection",features:[]},t=L.latLng(32.83064187300698,-79.93316068560326),n=L.latLng(32.89325262945007,-79.88402077618287),s=L.latLngBounds(t,n),o=0;o<locations.length;o++)e.features.push({type:"Feature",geometry:{type:"Point",coordinates:[parseFloat(locations[o][2]),parseFloat(locations[o][1])]},properties:{"marker-color":"Home"===locations[o][6]?"#b06a6a":"Condominium"===locations[o][6]||"Townhome"===locations[o][6]?"#0a8c7c":"#c9c23d","pop-up":locations[o][5],"listing-type":"Home"===locations[o][6]?"available-homes":"Condominium"===locations[o][6]||"Townhome"===locations[o][6]?"available-townhomes":"available-homesites"}});map.fitBounds(s),layer.setGeoJSON(e);var r=L.tileLayer(DI.templateUri+"/img/imap/tiles/{z}/{x}/{y}.png").addTo(map);layer.on("click",function(e){if(e.layer)var i=L.popup().setLatLng(e.latlng).setContent(e.layer.feature.properties["pop-up"]).openOn(map)}),filters.onchange=i,i()}function i(){for(var e=[],i=0;i<checkboxes.length;i++)checkboxes[i].childNodes[1].checked&&e.push(checkboxes[i].childNodes[1].name);return layer.setFilter(function(i){return e.indexOf(i.properties["listing-type"])!==-1}),!1}function t(){return $("h2.listings-title").text().toLowerCase().replace(/ /g,"-").replace(/[^\w-]+/g,"")}function n(){if($("div.listings-wrapper").find("div.listing").length>0){var e=$("div.listings-wrapper").find("div.listing").first();if(e.hasClass("offering")||e.hasClass("floor-plan"))e.trigger("click");else{var i=e.attr("data-property-id");l("_",i)}}else l("_","_")}function s(){$("div.listings-wrapper").fadeTo("slow",.3),I.hide(),d(),$.get("/api/home-finder/saved-listings/page/1",{sort:q},function(e){var i=e.rsp,t=e.total;c(),$("h2.listings-title").text("Saved Listings"),$("div.results-count").text(pluralize("Result",t,!0)),$("div.listings-wrapper").html(i).fadeTo("slow",1),C(),$("#filter-searchAddress").val(""),$("div.listings-wrapper").find("div.listing").first().trigger("click")})}function o(){$("div.listings-wrapper").fadeTo("slow",.3),I.hide(),d(),$.get("/api/home-finder/recently-listed/page/1",{},function(e){var i=e.rsp,t=e.total;c(),$("h2.listings-title").text("Recently Listed"),$("div.results-count").text(pluralize("Result",t,!0)),$("div.listings-wrapper").html(i).fadeTo("slow",1),C(),$("#filter-searchAddress").val(""),n()})}function r(){$("div.listings-wrapper").fadeTo("slow",.3),I.hide(),d(),$.get("/api/home-finder/featured-properties/page/1",{sort:q},function(e){var i=e.rsp,t=e.total;c(),$("h2.listings-title").text("Featured Listings"),$("div.results-count").text(pluralize("Result",t,!0)),$("div.listings-wrapper").html(i).fadeTo("slow",1),C(),$("#filter-searchAddress").val(""),n()})}function a(){$("div.listings-wrapper").fadeTo("slow",.3),C(),f("default",!0)}function l(e,i){$("div.single-listing-col").fadeTo("slow",.3),$.get("/api/home-finder/properties/"+i,{},function(e){var i=e.rsp;$("div.single-listing-col").html(i).fadeTo("slow",1),$("div.single-listing-col .listing-images").slick({lazyLoad:"ondemand",dots:!1,infinite:!1,speed:300,slidesToShow:2,slidesToScroll:1,centerMode:!1,arrows:!0,variableWidth:!0,respondTo:"window",responsive:[{breakpoint:991,settings:{slidesToShow:1,slidesToScroll:1,infinite:!1,variableWidth:!0}}]}),F()})}function d(){$("div.listings-wrapper").append('<div class="loading"></div>')}function c(){$("div.listings-wrapper div.loading").remove()}function p(e){var i=!0;"undefined"!=typeof e&&(i=!1),(i===!1||D.val().length>2)&&(D.prop("readonly",!0),q="default",U=!0,$("div.home-finder-filters").find("select option:selected").removeProp("selected"),$("#filter-builders").trigger("chosen:updated"),$("#filter-bedrooms").trigger("chosen:updated"),$("#filter-bathrooms").trigger("chosen:updated"),$("#filter-homeFeatures").multipleSelect("uncheckAll"),U=!1,f())}function f(e,i){if(U!==!0){"undefined"==typeof e&&(e="default");var t={prices:b(),bedrooms:y(),bathrooms:k(),searchAddress:w(),includePlans:m(),includeHomes:v(),builders:g(),homeFeatures:S(),squareFootage:x()};$("div.results-sort").show(),d(),$("div.listings-wrapper").fadeTo("slow",.3),u()===!1?I.show():I.hide(),h(t,e,i)}}function u(){return""===g()&&"0-500000"===b()&&""===y()&&""===k(),""===S()&&""===x()}function h(e,i,t){"undefined"==typeof i&&(i="default"),"undefined"==typeof t&&(t=!1);var s={prices:e.prices,bedrooms:e.bedrooms,bathrooms:e.bathrooms,searchAddress:e.searchAddress,sort:i,includePlans:e.includePlans,includeHomes:e.includeHomes,builders:e.builders,homeFeatures:e.homeFeatures,squareFootage:e.squareFootage};t===!0&&I.hide(),B.navigate("home-finder/search-listings/?"+$.param(s),{trigger:!1}),$.ajax({url:"/api/home-finder/search",data:s,success:function(e){var i=e.rsp,t=e.total;locations=[],c(),$("h2.listings-title").text("Search Listings"),$("div.results-count").text(pluralize("Result",t,!0)),$("div.listings-wrapper").html(i).fadeTo("slow",1),D.prop("readonly",!1),n()},error:function(e){D.prop("readonly",!1),c(),$("div.listings-wrapper").html('<div class="end-results">End of Results</div>').fadeTo("slow",1),l("_","_")}})}function g(){return $("#filter-builders").find("option:selected").val()}function m(){var e=$("#filter-listings-type").find("option:selected").val();return"home-plans"===e||"available-homes-and-plans"===e}function v(){var e=$("#filter-listings-type").find("option:selected").val();return"available-homes"===e||"available-homes-and-plans"===e}function w(){return $("#filter-searchAddress").val()}function b(){return $("#minPriceFilter").val().replace(/\D/g,"")+"-"+$("#maxPriceFilter").val().replace(/\D/g,"")}function y(){var e,i=$("#filter-bedrooms");return i.find("option:selected").val()}function k(){var e,i=$("#filter-bathrooms");return i.find("option:selected").val()}function x(){return $("#filter-sqft").val()}function S(){return $("#filter-homeFeatures").multipleSelect("getSelects")}function T(e,i){$.post("/api/home-finder/properties/"+e+"/"+i)}function F(){$("a.color-box-group").colorbox({rel:"color-box-group",maxWidth:"75%"}),$("a.color-box-floor-plan").colorbox()}function C(){q="default",U=!0,$("#filter-searchAddress").val(""),O.noUiSlider.set([0,5e5]),$("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-sqft").find("option").removeProp("selected"),$("#filter-builders, #filter-bedrooms, #filter-bathrooms, #filter-sqft").trigger("chosen:updated"),$("#filter-homeFeatures").multipleSelect("uncheckAll"),U=!1}function P(e,i){$.post("/api/home-finder/properties/"+i+"/share",{network:e},function(){})}function A(e){q="default",f()}var I=$("#saveSearchSection"),q="default",H=Backbone.Router.extend({routes:{"home-finder/properties/:address/:id/":"showProperty","home-finder/featured-listings/":"showFeaturedListings","home-finder/recently-listed/":"showRecentlyListed","home-finder/saved-listings/":"showSavedListings","home-finder/all-listings/":"showAllListings"},showSavedListings:function(){s()},showRecentlyListed:function(){o()},showFeaturedListings:function(){r()},showProperty:function(e,i){l(e,i)},showAllListings:function(){a()}}),B=new H;Backbone.history.started||(Backbone.history.start({pushState:"pushState"in window.history,silent:!0}),Backbone.history.started=!0,"home-finder/"===Backbone.history.getFragment()&&B.navigate("home-finder/featured-listings/",{trigger:!1,replace:!0})),$("#filter-bedrooms,#filter-bathrooms,#filter-builders,#filter-sqft").on("change",function(){$("#filter-searchAddress").val(""),q="default",f()}),$("div.listings-type select").on("change",function(){var e=$(this).find("option:selected").val();"home-plans"===e?$("div.view-on-map").css("visibility","hidden"):$("div.view-on-map").css("visibility","visible"),$("#filter-searchAddress").val(""),f()});var N,R=2e3,D=$("#filter-searchAddress");D.on("keyup",function(e){clearTimeout(N),"13"==event.keyCode?p(!0):N=setTimeout(p,R)}),D.on("keydown",function(){clearTimeout(N),N=setTimeout(p,R)});var U=!1;$("div.all-listings-col").on("click","div.listings-wrapper div.listing:not(.offering,.floor-plan)",function(){var e=$(this).attr("data-property-id"),i=$(this).attr("data-property-address");$(this).parent().find("div.listing.active").removeClass("active"),$(this).addClass("active"),B.navigate("home-finder/properties/"+i+"/"+e+"/",{trigger:!0})}),$("div.all-listings-col").on("click","div.listings-wrapper div.floor-plan",function(){var e=$(this).attr("data-floor-plan-link"),i=$(this).attr("data-builder-title"),t=$(this).attr("data-floor-plan-title");$(this).parent().find("div.listing.active").removeClass("active"),$(this).addClass("active"),B.navigate(e,{trigger:!1}),$.get("/api/home-finder/floor-plans/"+i+"/"+t,{},function(e){var i=e.rsp;$("div.single-listing-col").html(i),$("div.single-listing-col .listing-images").slick({dots:!1,infinite:!1,speed:300,slidesToShow:2,centerMode:!1,arrows:!0,variableWidth:!0,respondTo:"window"})})}),$(".listing-images").slick({dots:!1,infinite:!1,speed:300,slidesToShow:2,centerMode:!1,arrows:!0,variableWidth:!0,respondTo:"window"});var M=_.throttle(function(){if($(this).scrollTop()+$(this).innerHeight()>=$(this)[0].scrollHeight-200){var e=$("div.infinite-check");if(e.length>0){e.removeClass("infinite-check");var i=e.attr("data-infinite-scroll-next-url");d(),$.get(i,{},function(e){c();var i=e.rsp;$("div.listings-wrapper").append(i)})}}},100);$("div.all-listings-col").on("scroll",M),$(".more-button").click(function(){$(".more-options").toggle("slow"),$(".home-finder-filters").toggleClass("more")}),$(".toggle-more").click(function(){$(".filter-options").toggle("slow")}),$("div.home-finder-main").on("click","div.save.action-link a",function(){if("undefined"==typeof DI||"undefined"==typeof DI.isLoggedIn||"true"!==DI.isLoggedIn)return $("a.showAccountPage").trigger("click"),!1;var e=$(this).attr("data-property-id"),i="save",t=$(this);return $(this).parent().hasClass("saved")&&(i="un-save"),"save"===i?(t.closest("div.action-link").find("div.not-saved").hide(),t.closest("div.action-link").find("div.saved").show()):(t.closest("div.action-link").find("div.not-saved").show(),t.closest("div.action-link").find("div.saved").hide()),T(e,i),!1}),F(),$("#filter-clearAll").on("click",function(){return C(),$("#filter-bathrooms").trigger("change"),!1}),$(document).on("click","a.sortByPriceHighToLow",function(){return f("price.desc"),!1}),$(document).on("click","a.sortByPriceLowToHigh",function(){return f("price.asc"),!1}),$(document).on("click","#requestShowing",function(){var e=$("#modal-request-showing");return e.modal("show"),e.find("button").prop("disabled",!1),e.find('input[name="name"]').val(""),e.find('input[name="email"]').val(""),e.find('textarea[name="message"]').val(""),e.find("span.success-message").hide(),e.find("span.error-message").hide(),e.find('input[name="createAccount"]').prop("checked",!1),e.find("form").show(),!1}),$(document).on("click","#modal-request-showing button",function(){var e=$(this).parent().find('input[name="propertyId"]').val(),i=$(this).parent().find('input[name="builderTitle"]').val(),t=$(this).parent().find('input[name="floorPlanTitle"]').val(),n=$(this).parent().find('input[name="name"]').val(),s=$(this).parent().find('input[name="email"]').val(),o=$(this).parent().find('input[name="link"]').val(),r=$(this).parent().find('textarea[name="message"]').val(),a=$(this).parent().find('input[name="createAccount"]').is(":checked"),l=$(this).parent().parent().find("span.success-message"),d=$(this).parent().find("span.error-message"),c=$(this);return c.prop("disabled",!0),d.hide(),l.hide(),c.text("Sending..."),$.ajax({type:"POST",url:"/api/home-finder/request-showing",data:{propertyId:e,builderTitle:i,floorPlanTitle:t,name:n,email:s,message:r,shouldCreateAccount:a},error:function(e){if(c.prop("disabled",!1),"undefined"!=typeof e.responseJSON){switch(e.responseJSON.status){case 404:c.parent().find('input[name="email"]').addClass("error"),d.text(e.responseJSON.rsp),d.show();break;case 500:default:d.text("Failed to save message. Please try again. If it still fails, go to the contact page and let us know. Thanks!"),d.show()}c.text("Send"),c.prop("disabled",!1)}},success:function(e){c.prop("disabled",!1),c.parent().hide(),l.show(),c.text("Send"),a===!0&&(window.location.href=o)}}),!1}),$(document).on("click","#accountSaveSearch",function(){if("undefined"==typeof DI||"undefined"==typeof DI.isLoggedIn||"true"!==DI.isLoggedIn)return $("a.showAccountPage").trigger("click"),!1;var e={prices:b(),bedrooms:y(),bathrooms:k(),squareFootage:x(),homeFeatures:S(),includePlans:m(),includeHomes:v(),builders:g()},i=$(this);return $.post("/api/home-finder/save-search",e,function(e){if("undefined"!=typeof e.savedSearchCount){var t=e.savedSearchCount;i.parent().find("a.showAccountPage").text("("+t+")"),i.parent().find("a.showAccountPage").show(),$("a.savedSearchesCount").text("Saved Searches "+t)}}),!1}),$(document).on("click","div.listing",function(){$(".single-listing-col").animate({scrollTop:"0px"})}),$(".row-grid-view").each(function(e,i){$(i).find(".grid-results-box").matchHeight({byRow:!1})}),$(".col-map-listings").each(function(e,i){$(i).find(".map-results-box").matchHeight({byRow:!1})}),$(document).on("click","div.listing",function(){$(".single-listing-col").addClass("move-up"),$(".home-finder-filters").addClass("hide-filters")}),$(document).on("click","div.back-to-listings",function(){$(".single-listing-col").removeClass("move-up"),$(".home-finder-filters").removeClass("hide-filters")}),$(".all-listings-col").scroll(function(){var e=$(".all-listings-col").scrollTop();e>=250?$(".home-finder-filters").addClass("hide-filters"):$(".home-finder-filters").removeClass("hide-filters")}),$(document).on("click",".open-share-widget",function(){$(".share-widget").toggleClass("open")}),$(document).on("click",".share-icon",function(){$(".share-widget").removeClass("open")}),$(document).on("click","div.share-icon.fb-icon",function(){var e=$(this).parent().parent().attr("data-property-id");P("facebook",e)}),$(document).on("click","div.share-icon.twitter-icon",function(){var e=$(this).parent().parent().attr("data-property-id");P("twitter",e)}),$(document).on("click","div.share-icon.email-icon",function(){var e=$(this).parent().parent().attr("data-property-id");P("email",e)}),$("#filter-builders, #filter-bedrooms, #filter-listings-type, #filter-listings-type-copy").chosen({disable_search:"true"}),$("#filter-sq-ft, #filter-bathrooms").chosen({disable_search:"true",width:"165px"}),$("#filter-homeFeatures").multipleSelect({placeholder:"Home Features",onClick:A,onCheckAll:A,onUncheckAll:A}),$("#showPriceFilter").on("click",function(e){$("#priceFilterSection").show(),e.stopPropagation()}),$("#priceFilterSection").on("clickoutside",function(){$(this).hide()});var O=document.getElementById("filter-price");noUiSlider.create(O,{start:[0,5e5],connect:!0,step:5e4,range:{min:0,max:5e5},format:wNumb({decimals:0,thousand:",",prefix:"$"})}),O.noUiSlider.on("set",function(){$("#filter-searchAddress").val(""),q="default",f()}),O.noUiSlider.on("update",function(e,i){var t=[$("#minPriceFilter"),$("#maxPriceFilter")];t[i].val(e[i])}),$("#minPriceFilter").on("change",function(e){O.noUiSlider.set([$(this).val(),$("#maxPriceFilter").val()])}),$("#maxPriceFilter").on("change",function(e){O.noUiSlider.set([$("#minPriceFilter").val(),$(this).val()])}),$("#filter-listings-type").on("change",function(){$("#filter-listings-type-copy").find('option[value="'+$(this).find("option:selected").val()+'"]').prop("selected",!0),$("#filter-listings-type-copy").trigger("chosen:updated")}),$("#filter-listings-type-copy").on("change",function(){$("#filter-listings-type").find('option[value="'+$(this).find("option:selected").val()+'"]').prop("selected",!0),$("#filter-listings-type").trigger("chosen:updated");var e=$(this).find("option:selected").val();"home-plans"===e?$("div.view-on-map").css("visibility","hidden"):$("div.view-on-map").css("visibility","visible"),$("#filter-searchAddress").val(""),f()}),$(document).on("click","#fullscreen-map",function(){$(".col-map").toggleClass("open")})});
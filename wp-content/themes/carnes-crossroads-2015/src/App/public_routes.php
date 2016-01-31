<?php

function redirectThisToThat($this_url, $that_url)
{
    Routes::map($this_url, function () use ($that_url) {
        wp_redirect(home_url() . $that_url);
        exit();
    });
}

Routes::map('/category/all', function () {
    Routes::load('category.php');
});

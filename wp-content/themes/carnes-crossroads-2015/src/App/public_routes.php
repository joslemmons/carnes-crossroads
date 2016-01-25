<?php

//Routes::map('/login', array('\App\Controller\Auth', 'routeLoginPage'));
//Routes::map('/api/auth', array('\App\Controller\Auth', 'routeLoginAuth'));

Routes::map('/category/all', function () {
    Routes::load('category.php');
});

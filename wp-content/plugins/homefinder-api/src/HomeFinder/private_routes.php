<?php

use HomeFinder\Model\User;

Routes::map('/send-test-update-email', function() {
    $user = get_user_by('email', 'admin@joslemmons.com');
    $user = new User($user->ID);
    $users = array(
        $user
    );

    \HomeFinder\Controller\User::updateUsersOnSavedSearches($users);

    exit('Email(s) sent to ' . $user->user_email);
});

Routes::map('/send-test-welcome-email', function () {
    $user = get_user_by('email', 'admin@joslemmons.com');
    $user = new User($user->ID);
    $user->sendWelcomEmail();

    exit('Welcome email sent to ' . $user->user_email);
});

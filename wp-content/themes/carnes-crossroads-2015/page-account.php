<?php

use App\Model\PrimaryAndSecondaryPage;

// user is authenticated
$context = Timber::get_context();

/* @var $page \App\Model\Page */
$page = Timber::get_post(false, '\App\Model\AccountPage');

$context['page'] = $page;

PrimaryAndSecondaryPage::enqueueAssets();

if (false === is_user_logged_in()) {
    Timber::render('page-user-register.twig', $context);
} else {
    Timber::render('page-user-account.twig', $context);
}



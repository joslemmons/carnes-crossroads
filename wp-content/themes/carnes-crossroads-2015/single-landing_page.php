<?php

use App\Model\LandingPage;

$context = Timber::get_context();
$post = Timber::get_post(false, '\App\Model\LandingPage');

/* @var LandingPage $post */
$footer = $post->getFooter();
$gravity_form_id = $footer['gravity_form_id'];
$context['post'] = $post;

add_filter( 'gform_field_input', 'tracker', 10, 5 );
function tracker( $input, $field, $value, $lead_id, $form_id ) {
    if ($field->id == 1411) {
        $input = '<input type="hidden" id="lp2pb" name="lp2pb" value="' . LandingPage::IS_LANDING_PAGE_FORM_SUBMIT . '">';
    }
    return $input;
}

add_filter('gform_pre_render_' . $gravity_form_id, 'populate_dropdown');
function populate_dropdown($form)
{
    if (LandingPage::doesFormWantToSubmitAccountToPropertyBase($form) === false) {
        // this means its not set manuall in the form by adding a hidden field with the default value lp2pb.
        // it does need to submit to property base... so force it
        $fields = $form['fields'];

        $hidden_field = new GF_Field_Hidden();
        $hidden_field->id = 1411;
        $fields[] = $hidden_field;

        $form['fields'] = $fields;
    }

    return $form;
}

Timber::render('single-landing_page.twig', $context);
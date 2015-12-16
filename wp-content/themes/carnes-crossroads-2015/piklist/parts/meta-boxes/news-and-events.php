<?php
/*
 * Title: News & Events Options (Carnes Crossroads 2015 Theme)
 * Id: 13
 */

use App\Model\Helper;
use App\Model\NewsAndEventsPage;

piklist('field', array(
    'type' => 'text',
    'field' => NewsAndEventsPage::$field_contact_form_title,
    'label' => 'Contact Form Title',
    'columns' => 12
));

piklist('field', array(
    'type' => 'select',
    'field' => NewsAndEventsPage::$field_contact_form_gravity_form_id,
    'label' => 'Choose Gravity Form',
    'columns' => 12,
    'choices' => Helper::getFormChoicesForPiklist()
));
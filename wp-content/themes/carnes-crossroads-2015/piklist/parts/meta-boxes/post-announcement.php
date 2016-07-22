<?php
/*
 * Title: Additional Announcement Information
 * Post Type: announcement
 */

use App\Model\Announcement;

piklist('field', array(
    'type' => 'datepicker',
    'field' => Announcement::$field_expire_date,
    'label' => 'Expire Date',
    'description' => 'Expire this announcement after the indicated date.',
    'options' => array(
            'dateFormat' => 'yy-mm-dd'
            ,'changeYear' => true
    ),
));
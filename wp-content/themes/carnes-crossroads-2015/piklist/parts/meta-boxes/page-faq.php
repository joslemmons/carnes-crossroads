<?php
/*
 * Title: FAQ Options (Carnes Crossroads 2015 Theme)
 * Id: 21
 */

use App\Model\FAQPage;

piklist('field', array(
    'type' => 'group',
    'field' => FAQPage::$field_faqs,
    'label' => 'FAQs',
    'add_more' => true,
    'fields' => array(
        array(
            'type' => 'text',
            'field' => FAQPage::$field_faq_question,
            'label' => 'Question',
            'columns' => 12
        ),
        array(
            'type' => 'textarea',
            'field' => FAQPage::$field_faq_answer,
            'label' => 'Answer',
            'columns' => 12,
            'attributes' => array(
                'rows' => 6
            )
        )
    )
));


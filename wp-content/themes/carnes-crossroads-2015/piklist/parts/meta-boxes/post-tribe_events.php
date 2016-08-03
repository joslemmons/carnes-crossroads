<?php
/*
 * Title: Event POA Image
 * Post Type: tribe_events
 */

piklist('field', array(
		    'type' => 'file',
		    'field' => 'poa_image',
		    'label' => 'POA Image',
		    'save' => 'url',
		    'options' => array(
		      'modal_title' => 'Select POA Image'
		      ,'button' => 'Select Image',
		      'save' => 'url'
		    )
));


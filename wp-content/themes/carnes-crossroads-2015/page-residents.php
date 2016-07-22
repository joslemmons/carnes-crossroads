<?php

use App\Model\Config;
use App\Model\Announcement;

function getDisplayDates ($start_date,$end_date,$month_format = 'F',$show_year = true,$show_times = false) {
	$display_date = '';
	$year_format = ", Y";
	$day_format = 'j';
	if (!$show_year) {
		$year_format = "";
	}
	$separator = '-';
	list ($start,$start_time) = explode(' ', $start_date);
	list ($end,$end_time) = explode(' ', $end_date); 
	
	$all_day = false;
	if ( ($start_time == '00:00:00') && ($end_time == '23:59:59') ) {
		$all_day = true;
	}
	if (!empty($end) && ($end != $start)) {
		list ($sy,$sm,$sd) = explode('-', $start);
		list ($ey,$em,$ed) = explode('-', $end);
		$month = date("$month_format", strtotime($start));
		if ($show_times && !$all_day) {
			$separator = ' - ';
			$display_start_time = date(" @ g:00 a ",strtotime($start_date));
			$display_end_time = date(" @ g:00 a ",strtotime($end_date));
			$month_show = " $month ";
			$day_format = 'jS';
		} else {
			$display_start_time = '';
			$display_end_time = '';
			$month_show = '';
		}
		
		if ($sy != $ey) { // diff years
			$display_date = date("$month_format j$year_format", strtotime($start)) . $display_start_time . $separator . date("$month_format $day_format$year_format", strtotime($end)) . $display_end_time;
		} elseif ($sm != $em) { // diff months
			$year = date("$year_format", strtotime($start));
			$display_date = date("$month_format $day_format", strtotime($start)) . $display_start_time . $separator .date("$month_format $day_format", strtotime($end)) . $display_end_time . $year;
		} else { // same month, year
			if ($show_year) {
				$year = date("$year_format", strtotime($start));
			} else {
				$year = '';
			}
			$display_date = $month . ' ' . date("$day_format", strtotime($start)) . $display_start_time . $separator . $month_show . date("$day_format", strtotime($end)) . $display_end_time . $year;
		}
	} else {
		// just return formatted start date
		if ($show_year) {
			$year = date("$year_format", strtotime($start));
		} else {
			$year = '';
		}
		if ($show_times && !$all_day) {
			$display_time = ' @ '.date("g:00 a",strtotime($start_date)) . ' - ' . date("g:00 a",strtotime($end_date));
			$day_format = 'jS';
		} else {
			$display_time = '';
		}
		
		$display_date = date("$month_format $day_format", strtotime($start)).$display_time.$year;
	}
	
	return $display_date;
}

wp_enqueue_script('match-height-js', get_template_directory_uri() . '/js/lib/jquery.matchHeight-min.js', array('jquery'), false, false);
wp_enqueue_script('poa-js', get_template_directory_uri() . '/js/poa.js', array('jquery', 'slick-js', 'match-height-js'), Config::getAppVersion(), true);

$context = Timber::get_context();

$events = tribe_get_events( array(
    'posts_per_page' => 10,
    'eventDisplay' => 'list'
) );

$featured = tribe_get_events( array(
    'posts_per_page' => 10,
    'eventDisplay' => 'list',
    'tag' => 'featured',
) );

if (count($featured) < 1) {
	$featured = array($events[0]);
}

for ($i = 0 ; $i < count($featured) ; $i++) {
	$id = $featured[$i]->ID;
	if (has_post_thumbnail( $id ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' );
		$event_image_url = $image[0];
	} else {
		$event_image_url = false;
	}
	$featured[$i]->event_image_url = $event_image_url;  
}

$post_meta = get_post_meta($post->ID);

$sliders = unserialize($post_meta['page_sliders'][0]);
$announcements = Announcement::get(true,10);
$callout = unserialize($post_meta['page_callout'][0]);

$context['announcements'] = $announcements;
$context['sliders'] = $sliders;
$context['callout'] = $callout;
$context['events'] = $events;
$context['featured'] = $featured;

Timber::render('poa/front-page.twig', $context);

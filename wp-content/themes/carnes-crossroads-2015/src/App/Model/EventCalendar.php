<?php namespace App\Model;

require_once get_template_directory().'/src/App/simple_html_dom.php';

class EventCalendar
{
  	
  	public $events_url = '/calendar-of-events';
  	public $plugin_path = '/wp-content/plugins/the-events-calendar';
  	public $calendar_html = '';
  	public $date = '';
  	public $category = '';
  	public $eslug = '';
  	
  	function __construct($date = '',$category = '',$eslug = '') {
	  	if (!empty($date) && !empty($category)) {
		  	$this->events_url .= '/category/'.$category.'/'.$date;
		  	$this->category = $category;
		  	$this->date = $date;
	  	} elseif (!empty($date)) {
		    $this->events_url .= '/'.$date;
		  	$this->date = $date;
	    } elseif (!empty($category)) {
		    $this->events_url .= '/category/'.$category;
		    $this->category = $category;
	    } elseif (!empty($eslug)) {
		    $this->events_url = '/event/'.$eslug;
		    $this->eslug = $eslug;
	    }
  	}
  	
    public function enqueue_assets () {
	    
	    $template_dir = get_site_url();
	    
	    wp_enqueue_style('tribe-events-bootstrap-datepicker-css-css', $this->plugin_path . '/vendor/bootstrap-datepicker/css/datepicker.css?ver=4.5.2');
	    wp_enqueue_style('tribe-events-custom-jquery-styles-css', $this->plugin_path . '/vendor/jquery/smoothness/jquery-ui-1.8.23.custom.css?ver=4.5.2');
	    wp_enqueue_style('tribe-events-calendar-style-css', $this->plugin_path . '/src/resources/css/tribe-events-full.min.css?ver=4.1.0.1');
	    wp_enqueue_style('tribe-events-calendar-mobile-style-css', $this->plugin_path . '/src/resources/css/tribe-events-full-mobile.min.css?ver=4.1.0.1', array(), false, 'only screen and (max-width: 768px)');
	  
    }
    
    public function fetchCalendarHtml() {
	    	  
	    if ($html = file_get_html(get_site_url().$this->events_url)) {
		     $this->calendar_html = $html->find('div[id=tribe-events]',0);
	 	}
    }
    
    public function cleanEventHtml () {
	    
	    $this->calendar_html = str_replace('/calendar-of-events/', '/residents/events-activities/', $this->calendar_html);
	    $this->calendar_html = str_replace('/event/', '/residents/events-activities/event/', $this->calendar_html);
	    
	    if (!$html = str_get_html($this->calendar_html)) {
		    return false;
	    }
	  
	    $this->calendar_html = $html;
    }
    
    public function cleanCalendarHtml () {
	    
	    if (!empty($this->eslug)) {
		    $this->cleanEventHtml ();
		    return;
	    }
	    
	    if (!$html = str_get_html($this->calendar_html)) {
		    return false;
	    }
	    
	    $html->find('form[id=tribe-bar-form]',0)->outertext = ''; // get rid of month/day js filter
	    
	    // fix month links
	    
	    foreach ($html->find('ul.tribe-events-sub-nav a') as $mnav) {
		    $data = $mnav->{"data-month"};
		    if (!$data) {
			    $data = $mnav->{"data-day"};
		    }	
		    $catpart = '';	  
		    if (!empty($this->category)) {
			    $catpart = 'category/'.$this->category.'/';
		    }  
		    $newhref = get_site_url().'/residents/events-activities/'.$catpart.$data;
		    $mnav->href = $newhref;		    
	    }
	    
	    // fix date links
	    	    	    
	    foreach ($html->find('div.tribe-events-viewmore a') as $dnav) {
		    $href = $dnav->href;		    
		    $newhref = str_replace('/calendar-of-events', '/residents/events-activities', $href);
		    $dnav->href = $newhref;	
	    }
	    
	    // fix event links
	    
	    foreach ($html->find('a.url') as $eurl) {
		    $href = $eurl->href;		    
		    $newhref = str_replace('/event/', '/residents/events-activities/event/', $href);
		    $eurl->href = $newhref;	
	    }
	    
	    $this->calendar_html = $html;
	    
    }
    
    public function getEventCategories () {
	    return get_categories(array('taxonomy'=>'tribe_events_cat'));
    }
    
    public function getCalendar () {
	    $this->fetchCalendarHtml();
	    $this->cleanCalendarHtml ();
	    return $this->calendar_html;
    }
}

<?php namespace App\Model;

require_once get_template_directory().'/src/App/simple_html_dom.php';

class EventCalendar
{
  	
  	public $events_url = '/events';
  	public $plugin_path = '/wp-content/plugins/the-events-calendar';
  	public $calendar_html = '';
  	
  	function __construct($date = '') {
		if (!empty($date)) {
		    $this->events_url .= '/'.$date;
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
	    	  
	    $html = file_get_html(get_site_url().$this->events_url);
	     
	    $this->calendar_html = $html->find('div[id=tribe-events-pg-template]',0);
	 
    }
    
    public function cleanCalendarHtml () {
	    
	    $html = str_get_html($this->calendar_html);
	    $html->find('a.news-link',0)->outertext = '';  // get rid of 'back to news' link
	    $html->find('form[id=tribe-bar-form]',0)->outertext = ''; // get rid of month/day js filter
	    
	    // fix month links
	    
	    foreach ($html->find('ul.tribe-events-sub-nav a') as $mnav) {
		    $data = $mnav->{"data-month"};
		    if (!$data) {
			    $data = $mnav->{"data-day"};
		    }		    
		    $newhref = get_site_url().'/community/events-activities/'.$data;
		    $mnav->href = $newhref;		    
	    }
	    
	    // fix date links
	    	    	    
	    foreach ($html->find('div.tribe-events-viewmore a') as $dnav) {
		    $href = $dnav->href;		    
		    $newhref = str_replace('/events', '/community/events-activities', $href);
		    $dnav->href = $newhref;	
	    }
	    
	    $this->calendar_html = $html;
	    
    }
    
    public function getCalendar () {
	    $this->fetchCalendarHtml();
	    $this->cleanCalendarHtml ();
	    return $this->calendar_html;
    }
}

<?php

class MY_Controller extends CI_Controller {

	/*
	 * List of controllers that can be accessed
	 * without the need to be authenticated
	 */
    protected $_open_controllers = array('auth');
    
    /*
     * Default layout view
     */
    protected $layout_view = 'template';
    
    /*
     * Default content view
     */
	protected $content_view = '';
    
    /*
     * Master data variable passed in view
     */
    protected $data = array();

    public function __construct()
    {
        parent::__construct();
        
       	//$this->output->enable_profiler(TRUE);

        // Check auth
        $this->_check_auth();
        
        //Loads Modules and Sub-Modules
        $this->_load_modules();      	   
    }

    private function _check_auth()
    {
        if (!$this->session->userdata('logged_in'))
        {
            if (!in_array($this->router->class, $this->_open_controllers))
            {
                // Save the page we are on now to redirect if the user 
                // successfully authenticates
                $this->session->set_userdata('next', $this->uri->uri_string);

                // Redirect to LOGIN (auth/login)
                redirect('login');
            }
        }
    }
    
    private function _load_modules()
    {
        /*
         * If master controller has been called,
         * retrevies sub-modules menu based on that,
         * else,
         * finds parent module_id, an calls all sub_modules
         * with that ID 
         */    
        if(in_array($this->router->class,$this->session->userdata('open_modules')))
            $this->data['nav_smodules'] = $this->Sub_modules_model->select_by_module($this->router->class);
        else
        	$this->data['nav_smodules'] = $this->Sub_modules_model->select_by_controller($this->router->class);  	  	
    }
    
	public function _output($output)
	{
		
		//set default content view
		if($this->content_view !== false && empty($this->content_view))
			$this->content_view = $this->router->class . '/' . $this->router->method;
		
		//render content view
		$content = file_exists(APPPATH . 'views/'. $this->content_view . EXT) ?
			$this->load->view($this->content_view,$this->data,true) : false;

		//render the layout
		if($this->layout_view)
			echo $this->load->view('layouts/'. $this->layout_view, array('content'=>$content), true);
		else
			echo $content;			
	}
}
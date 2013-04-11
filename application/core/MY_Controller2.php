<?php

class MY_Controller extends CI_Controller {

	/*
	 * List of controllers that can be accessed
	 * without the need to be authenticated
	 */
    
    protected $open_controllers = array('auth');
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

        /**
         * Checks authentication
         */
        $this->check_authentication();
        
        /**
         * Loads sub-modules navigation based on resource
         * permission in SESSION['resources'] variable.
         * 
         * AJAX Request bypass this process, because no
         * page loading is done trough AJAX
         */
        if(!$this->input->is_ajax_request())
            $this->load_sub_modules(); 
    }

    private function check_authentication()
    {
        if(!$this->session->userdata('logged_in'))
        {
            if (!in_array($this->router->class, $this->open_controllers))
            {
                // Save the desired page to redirect if the user 
                // successfully authenticates
                $this->session->set_userdata('next', $this->uri->uri_string);

                // Redirect back to LOGIN (auth/login)
                redirect('login');
            }
        }
    }
    
    private function load_sub_modules()
    {
        $reference = $this->input->get('ref');
        $id = $this->input->get('id');

        if($reference AND $id)
        {
            $this->data['sub_modules'] = $this->Resources_model->get_sub_modules_by_parent($id);

            if(!$this->data['sub_modules'])
                $this->utilities->flash('deny',$this->session->userdata('default_module'));
        }
        else
        {
            $this->data['sub_modules'] = $this->Resources_model->get_sub_modules_by_class($this->router->class,$this->router->method);

            if(!$this->data['sub_modules'])
                $this->utilities->flash('deny',$this->session->userdata('default_module'));    
        }    	
    }
    
	public function _output($output)
	{
		//set default content view
		if($this->content_view !== false AND empty($this->content_view))
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
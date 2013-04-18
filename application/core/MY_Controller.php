<?php
/**
 * A base controller for CodeIgniter with view autoloading, layout support,
 * model, helper, and library loading, asides/partials and per-controller 404
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-controller
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class MY_Controller extends CI_Controller
{

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * Open Controller (not require authenticated user)
     */
    protected $open_controllers = array('auth');

    /**
     * The current request's view. Automatically guessed
     * from the name of the controller and action
     */
    protected $view = '';

    /**
     * An array of variables to be passed through to the
     * view, layout and any asides
     */
    protected $data = array();

    /**
     * The name of the layout to wrap around the view.
     */
    protected $layout;

    /**
     * An arbitrary list of asides/partials to be loaded into
     * the layout. The key is the declared name, the value the file
     */
    protected $asides = array();

    /**
     * A list of models to be autoloaded
     */
    protected $models = array();

    /**
     * A formatting string for the model autoloading feature.
     * The percent symbol (%) will be replaced with the model name.
     */
    protected $model_string = '%_model';

    /**
     * A list of helpers to be autoloaded
     */
    protected $helpers = array();

    /**
     * A list of libraries to be autoloaded
     */
    protected $libraries = array();

    /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the controller, tie into the CodeIgniter superobject
     * and try to autoload the models, helpers, and libraries
     */
    public function __construct()
    {
        parent::__construct();

        $this->_check_authentication();

        //Loads sub-modules if request are non-Ajax
        if(!$this->input->is_ajax_request())
        {
            $this->_load_sub_modules();
        }

        $this->_load_models();
        $this->_load_helpers();
        $this->_load_libraries();
    }

    /* --------------------------------------------------------------
     * VIEW RENDERING
     * ------------------------------------------------------------ */

    /**
     * Override CodeIgniter's despatch mechanism and route the request
     * through to the appropriate action. Support custom 404 methods and
     * autoload the view into the layout.
     */
    public function _remap($method)
    {
        if (method_exists($this, $method))
        {
            call_user_func_array(array($this, $method), array_slice($this->uri->rsegments, 2));
        }
        else
        {
            if (method_exists($this, '_404'))
            {
                call_user_func_array(array($this, '_404'), array($method));
            }
            else
            {
                show_404(strtolower(get_class($this)).'/'.$method);
            }
        }

        $this->_load_view();
    }

    /**
     * Automatically load the view, allowing the developer to override if
     * he or she wishes, otherwise being conventional.
     */
    protected function _load_view()
    {
        // If $this->view == FALSE, we don't want to load anything
        if ($this->view !== FALSE)
        {
            // If $this->view isn't empty, load it. If it isn't, try and guess based on the controller and action name
            $view = (!empty($this->view)) ? $this->view : $this->router->directory . $this->router->class . '/' . $this->router->method;

            // Load the view into $yield
            $data['yield'] = $this->load->view($view, $this->data, TRUE);

            // Do we have any asides? Load them.
            if (!empty($this->asides))
            {
                foreach ($this->asides as $name => $file)
                {
                    $data['yield_'.$name] = $this->load->view($file, $this->data, TRUE);
                }
            }

            // Load in our existing data with the asides and view
            $data = array_merge($this->data, $data);
            $layout = FALSE;

            // If we didn't specify the layout, try to guess it
            if (!isset($this->layout))
            {
                if (file_exists(APPPATH . 'views/layouts/' . $this->router->class . '.php'))
                {
                    $layout = 'layouts/' . $this->router->class;
                }
                else
                {
                    $layout = 'layouts/application';
                }
            }

            // If we did, use it
            else if ($this->layout !== FALSE)
            {
                $layout = $this->layout;
            }

            // If $layout is FALSE, we're not interested in loading a layout, so output the view directly
            if ($layout == FALSE)
            {
                $this->output->set_output($data['yield']);
            }

            // Otherwise? Load away :)
            else
            {
                $this->load->view($layout, $data);
            }
        }
    }

    /* --------------------------------------------------------------
     * CHECK AUTHENTICATION
     * ------------------------------------------------------------ */
    /**
     * Check if user is NOT logged in, and redirects to login
     */
    private function _check_authentication()
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

    /* --------------------------------------------------------------
     * SUB MODULES LOADING
     * ------------------------------------------------------------*/
    /**
     * Loads sub-modules for aside navigation
     */
    private function _load_sub_modules()
    {
        $reference = $this->input->get('ref');
        $id = $this->input->get('id');

        if($reference AND $id)
        {
            $this->data['sub_modules'] = $this->Resources_model->get_sub_modules_by_parent($id);

            if(!$this->data['sub_modules'])
            {
                $this->utilities->flash('deny',$this->session->userdata('default_module'));
            }
        }
        else
        {
            $this->data['sub_modules'] = $this->Resources_model->get_sub_modules_by_class($this->router->class,$this->router->method);

            if(!$this->data['sub_modules'])
            {
                $this->utilities->flash('deny',$this->session->userdata('default_module'));    
            }
        }       
    }

    /* --------------------------------------------------------------
     * MODEL LOADING
     * ------------------------------------------------------------ */

    /**
     * Load models based on the $this->models array
     */
    private function _load_models()
    {
        foreach ($this->models as $model)
        {
            $this->load->model($this->_model_name($model), $model);
        }
    }

    /**
     * Returns the loadable model name based on
     * the model formatting string
     */
    protected function _model_name($model)
    {
        return str_replace('%', $model, $this->model_string);
    }

    /* --------------------------------------------------------------
     * HELPER LOADING
     * ------------------------------------------------------------ */

    /**
     * Load helpers based on the $this->helpers array
     */
    private function _load_helpers()
    {
        foreach ($this->helpers as $helper)
        {
            $this->load->helper($helper);
        }
    }

    /* --------------------------------------------------------------
     * LIBRARY LOADING
     * ------------------------------------------------------------ */

    /**
     * Load libraries based on the $this->libraries array
     */
    private function _load_libraries()
    {
        foreach ($this->libraries as $library)
        {
            $this->load->library($library);
        }
    }
}
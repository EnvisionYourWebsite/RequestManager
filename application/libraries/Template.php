<?php defined('BASEPATH') or exit('No direct script access allowed');
/** 
 *
 *      Template Class to generate web pages 
 * @author DevPlus31  http://codecanyon.net/user/devplus31
 * @category Libraries  
 *
*/
class CI_Template {

    /**
     * Set the debug mode on the template to output messages
     *
     * @access public
     * 
     *
     * @var bool

     */
    public $debug = FALSE;

    /**
     *  Default CSS Files see template.php in config folder
     *  @access private
     *
     *  @var array
     */
    private $css_default_files = NULL;

    /**
     *  Default JS Files see template.php in config folder
     *  @access private
     *  
     *  @var array
     */
    private $js_default_files  = NULL;

    /**
     *  Additional JS Files Use add_js function 
     *  @access private
     *  
     *  @var array
     */
    private $additional_js_files  = array(); 

    /**
     *  Additional CSS Files Use add_css function 
     *  @access private
     *  
     *  @var array
     */
    private $additional_css_files = array(); 



    /**
     *
     *  @access private 
     *
     *  @var array
     *
    */
    private $additional_data    = array();

    /**
     *  meta Data
     *  @access private
     *  
     *  @var array
     */
    private $meta_data = array(); 

    /**
     *  Use Default CSS if TRUE 
     *  @access private
     *  
     *  @var bool
     */
    private $useDefault_css = FALSE;

    /**
     *  Use Default JS if TRUE 
     *  @access private
     *  
     *  @var bool
     */
    private $useDefault_js  = FALSE; 

    /**
     *  Title of page
     *  @access private
     *  
     *  @var bool
     */
    private $title = NULL;

    /**
     *  Use jQuery if TRUE 
     *  @access private
     *  
     *  @var bool
     */
    private $jquery = FALSE; 

    /**
     *  Set jquery Version to use 
     *  @access private
     *  
     *  @var string
     */
    private $jquery_ver = '1.8.2';
    
    /**
     *  Add Js Code to the page from PHP
     *  @access private
     *  
     *  @var bool
     */
    private $js_code = NULL;

    /**
     *  Add CSS Code to the page from PHP
     *  @access private
     *  
     *  @var string
     */
    private $css_code = NULL;

    /**
     *  View 
     *  @access private
     *  
     *  @var string
     */
    private $view = NULL;

    /**
     *  layout
     *  @access private
     *  
     *  @var string
     */
    public $layout = NULL; 

    /**
     *  Content 
     *  @access private
     *  
     *  @var string
     */
    private $content = NULL; 

    /**
     *  A CodeIgniter instance 
     *  @access private
     *  
     *  @var string
     */
    private $CI = NULL;

    /**
     *  Default Theme to use
     *  @access private
     *  
     *  @var string
     */
    protected $default_theme = ''; 

    /**
     *  Curent theme 
     *  @access private
     *  
     *  @var string
     */
    protected $current_theme = '';


    protected $assetsDir = 'assets';




    public function __construct()
    {   
        $this->CI =& get_instance(); 
        
        $this->loadSettings();

    }


    /**
     *  Load Settings
     *  @access private
     *  
     *  @return void
     */
    public function loadSettings()
    {   

        if (!$this->CI->config->item('default_css_files'))
        {
            $this->CI->config->load('template');
        }   


        if($this->CI->config->item('use_jquery')) 
        {
            $this->jquery = $this->CI->config->item('use_jquery'); 
        }   

        if($this->CI->config->item('use_default_css_files'))
        {
            $this->useDefault_css = $this->CI->config->item('use_default_css_files');
        }

        if($this->CI->config->item('use_default_js_files'))
        {
            $this->useDefault_js  = $this->CI->config->item('use_default_js_files'); 
        }

        if($this->CI->config->item('jquery_verison'))
        {
            $this->jquery_ver = $this->CI->config->item('jquery_verison');
        }

        if($this->CI->config->item('layout'))
        {
            $this->layout = $this->CI->config->item('layout');
        }

        if($this->CI->config->item('meta_data'))
        {
            $this->metadata = $this->CI->config->item('meta_data'); 
        }

        if($this->CI->config->item('layout'))
        {
            $this->layout = $this->CI->config->item('layout'); 
        }

        
    }




    public function add_part($part)
    {

        if ($part != NULL)
        {
            $this->load->view($part);
        }
        
        
        return $this;
    }

    public function add_parts($parts)
    {
        foreach ( $parts as $part )
        {
            $this->add_part($part);
        }

        return $this;
    }



    /**
     *  Renders out 
     *  @access public
     *
     *  @param $layout Name of the a lyout to use.
     *  @return void
    */
    public function render()
    {


        $data = array(); 

        $params = '';

    
        if ($params == '')
        {
            
            $data['Title'] = $this->title;

            if ($this->content == '')
            {
                $data['view'] = $this->view;
            }
            else
            {   
                $data['Content'] = $this->content;
            }

            $data['includes'] = $this->generate_includes_files();   

        }
        else
        {

            //$this->loadSettings($params);

         $data['Title'] = $params['Title']; 
         

        if(isset($params['content'])) { $this->content = $params['content']; } else { $this->view = $params['view']; }
         
            if (!isset($params['use_default_includes_files']))
            {               
                    if(isset($params['use_default_css_files']))
                    {
                        $this->useDefault_css = $params['use_default_css_files'];
                    }

                    if(isset($params['use_default_js_files']))
                    {
                        $this->useDefault_js = $params['use_default_js_files'];
                    }
            }
            else
            {
                $this->useDefault_css = $params['use_default_includes_files'];
                $this->use_default_js = $params['use_default_includes_files'];
            }


            if(isset($params['css']))
            {
                $this->css($params['css']);
            }

            if(isset($params['js']))
            {
                $this->js($params['js']);
            }

            if(isset($params['use_jquery'])) {
                $this->use_jquery($params['use_jquery']);
            }

            $data['includes']   = $this->generate_includes_files();
        }
        
        if ($this->additional_data != '') {
            $data = array_merge($data,$this->additional_data);
        }

        $this->CI->load->view('layout/layout',$data);   

    }


    public function set_layout($layout)
    {
        $this->layout = $layout; 
        return $this; 
    }

    public function set_view($view)
    {
        $this->view = $view;
        return $this; 
    }

    public function set_content_body($content)
    {
        $this->content = $content;
        return $this;
    }


    public function use_default_css_files($bool=TRUE)
    {
        $this->useDefault_css = $bool; 
        return $this; 
    }

    public function use_default_js_files($bool=TRUE)
    {
        $this->useDefault_js = $bool; 
        return $this; 
    }

    public function use_jquery($bool=TRUE,$version='1.8.2')
    {
        $this->jquery     = $bool; 
        $this->jquery_ver = $version;
        return $this; 
    }

    public function use_default_includes_files($bool=TRUE)
    {
        $this->use_default_js_files($bool);
        $this->use_default_css_files($bool); 
        return $this;
    }

    public function add_css_file($url)
    {
        $this->CI->load->helper('url'); 
        $this->additional_css_files[] =  $url;
        return $this;  
    }

    public function add_js_file($url)
    {
        $this->CI->load->helper('url'); 
        $this->additional_js_files[] =  $url; 
        return $this;
    }

    public function add_meta_data($meta)
    {
        $this->metadata[] = $meta;
        return $this;
    }

    public function js($js_code)
    {
        $this->js_code = '<script>'."\n\t\t". $js_code ."\n\t". '</script>'."\n\t";
        return $this; 
    }

    public function css($css_code)
    {
        $this->css_code = '<style>'."\n\t\t". $css_code ."\n\t".'</style>'."\n\t"; 
        return $this;
    }

    public function set_page_title($title)
    {
        $this->title = $title; 
        return $this; 
    }

    public function get_jquery_cdn_url($version='1.8.2')
    {
        $out = '<script src="//ajax.googleapis.com/ajax/libs/jquery/'.$version.'/jquery.min.js"></script>'."\n";
        return $out;
    }

    public function print_array($array)
    {
        foreach ($array as $str)
        {
            echo $str . "\n\t"; 
        }

        return $this;
    }

    public function generate_js_includes_files()
    {
        $includes = array(); 

        if ($this->jquery  == TRUE)
        {
            $includes[] = $this->get_jquery_cdn_url($this->jquery_ver); 
        }

        if ($this->useDefault_js == TRUE) { 

            foreach ($this->CI->config->item('default_js_files') as $js_include)
            {
                $includes[] = '<script type="text/javascript" src="'.  base_url() . $this->assetsDir . '/js/' . htmlspecialchars(strip_tags($js_include)) .'"></script>';
            }

        }

        foreach ($this->additional_js_files as $js_additional)
        {
            $includes[]  = '<script type="text/javascript" src="'. base_url()  . $this->assetsDir . '/js/' . htmlspecialchars(strip_tags($js_additional)) .'"></script>'; 
        }

        return $includes; 
    }

    public function message($msg='',$type='info')
    {
        if(empty($msg) && class_exists('CI_Session'))
        {
            $msg = $this->CI->session->flashdata('message');

            if(!empty($msg))
            {

            }
        }

        if (empty($msg))
        {
            if(empty($this->$message['message']))
            {
                return '';
            }

            $msg  = $this->message['message'];
            $type = $this->message['type'];
        }



        if (class_exists('CI_Session'))
        {
            $this->CI->session->set_flashdata('message','');
        }


    }
    
    public function generate_css_includes_files()
    {
        $includes = array();    

        $this->CI->load->helper('url');

        if ($this->useDefault_css == TRUE) {

            foreach ($this->CI->config->item('default_css_files') as $css_include)
            {
                $includes[] = '<link href="'. base_url()   . $this->assetsDir . '/css/' .  htmlspecialchars(strip_tags($css_include)).  '" rel="stylesheet"/>'; 
            }

        }

        foreach ($this->additional_css_files as $css_additional)
        {
            $includes[] = '<link href="'. base_url()   . $this->assetsDir . '/css/' .  htmlspecialchars(strip_tags($css_additional)) .'" rel="stylesheet"/>'; 
        }

        return $includes;
    }

    public function generate_includes_files()
    {
        $includes = $this->generate_css_includes_files(); 
        return array_merge($includes,$this->generate_js_includes_files());
    }

    public function generate_in_file_code()
    {
        return $this->js_code ."\n\t". $this->css_code; 
    }

    public function generate_body()
    {
            if ($this->content == '')
                return $this->CI->load->view($this->view); 

        echo $this->content;
    }

    public function Title()
    {
        return $this->title;
    }


    public function set_additional_data($data) {
        $this->additional_data = $data;
    }

}
?>
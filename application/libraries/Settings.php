<?php defined('BASEPATH') or exit('No direct script access allowed');
class CI_Settings {

	protected $CI; 

	private static $cache = array(); 


	public function __construct()
	{

		$this->CI =& get_instance(); 
		
		$this->CI->load->model('settings/setting_model');
		
		$this->get_all();
	}

	public function __get($name)
	{
		return self::get($name);
	}

	public function __set($name,$value)
	{
		return self::set($name,$value);
	}

	public static function item($name)
	{
		$CI =& get_instance(); 

		if (isset(self::$cache[$name]))
		{
			return self::$cache[$name];
		}

		$setting = $CI->settings_model->find_by('name',$name);

		$value = $setting ? $setting->row()->OptionValue : config_item($name);

		self::$cache[$name] = $value;

		return $value;
	}

	public static function CI() {
		return get_instance();
	}

	public static function get($name)
	{
	
		if(isset(self::$cache[$name]))
		{
			return self::$cache[$name];
		}
		CI_Settings::CI()->load->model('settings/setting_model');
	
		$setting = CI_Settings::CI()->setting_model->get_where(array('OptionName' => $name));
		if ($setting->num_rows() > 0) {
			$value = $setting->row()->OptionValue;
		} else {
			$value = NULL;
		}
		

		if ($value == 'TRUE') {
			$value = TRUE; 
		} else if ($value == 'FALSE') {
			$value = FALSE; 
		}

		self::$cache[$name] = $value;

		return $value;

	}


	/**
	 * Sets a config item
	 *
	 */
	public static function set($name,$value)
	{
		$CI =& get_instance();
		$CI->load->model('settings/setting_model');

		if (is_bool($value) && $value == TRUE) {
				$value = 'TRUE'; 
			} elseif (is_bool($value) && $value == FALSE) {
				$value = 'FALSE';
			}

		if(isset(self::$cache[$name]))
		{
			$data = array(
				'OptionValue' => $value
			);
			$setting = $CI->setting_model->update($name,$data);
		}
		else
		{
			$data = array(
				'OptionValue' => $value
			);

			$setting = $CI->setting_model->update($name,$data);

		}

		self::$cache[$name] = $value;

		return TRUE;

	}

	public static function insert($option_name,$option_value) {
		$CI =& get_instance();
		$CI->load->model('settings/setting_model');
		return $CI->setting_model->insert($option_name,$option_value);
	}


	/**
	 * Delete config item
	 *
	 * @access public
	 * @static
	 *
	 * @return bool
	 */
	public static function delete($name)
	{
		$CI =& get_instance();
		if (isset($cache[$name]))
		{
			$data = array(
					'OptionName'   => $name
				);

			if($CI->setting_model->delete_where($data))
			{
				unset(self::$cache[$name]);
				return TRUE;
			}
		}

		return FALSE;
	}


	/**
	 *  Gets all the settings
	 *	@access public
	 *
	 *	@return array
	 */
	public function get_all()
	{
		if(self::$cache)
		{
			return self::$cache;
		}

		$settings = $this->CI->setting_model->get_all();

		foreach($settings as $setting)
		{
			if ($setting->OptionValue == 'TRUE') {
				$setting->OptionValue = TRUE;
			}
			else if ($setting->OptionValue == 'FALSE') {
				$setting->OptionValue = FALSE;
			}
			
			self::$cache[$setting->OptionName] = $setting->OptionValue;
		}

		return self::$cache;
	}



}

if( !function_exists('settings_item')) {

	function settings_item($option_name= NULL)
	{
		if ($option_name == NULL)
		{
			return FALSE;
		}

		return CI_Settings::item($option_name);
	}
}





?>
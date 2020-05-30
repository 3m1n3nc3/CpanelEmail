<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

$CI = & get_instance();

// --------------------------------------------------------------------

if ( ! function_exists('int_bool'))
{
	/**
	 * Elements
	 *
	 * Returns a boolen in place of integers 1 / 0, returns 1 for anything greater than 1.
	 *
	 * @param	integer 
	 * @return	boolen
	 */
	function int_bool($int = 1)
	{
		if ($int === 1 || $int >= 1) 
		{
			return TRUE;
		} 
		else 
		{
			return FALSE;
		} 
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('set_value_switch')) 
{
	function set_value_switch($key = null, $default = null) 
	{	  
		global $CI;
 
		if ($CI->input->post($key)) 
		{
			return set_value($key);
		} 
		else 
		{
			return $default;
		}
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('safelink'))
{
	/**
	 * Elements
	 *
	 * Checks if the generated safelink is used as a username or on 
	 * another contests, then appends a random four digit number
	 *
	 * @param	string 
	 * @return	string	
	 */
	function safelink($safelink = '')
	{
		global $CI;

		$safelink = url_title($safelink, '_', TRUE);  

		$contest_safelink = $CI->contest_model->get($safelink);
		$user_safelink = $CI->user_model->get($safelink);

		if ($contest_safelink || $user_safelink) 
		{
			$safelink = ($safelink . '_' . rand(1500, 9500));
		}
		return $safelink;
	}
}

if ( ! function_exists('variable_switch')) 
{
	function variable_switch($var_1 = null, $var_2 = null, $reverse = FALSE) 
	{	
		$variable = NULL;
		
		if ($reverse) 
		{
			if (isset($var_2)) 
			{
				$variable = $var_2;
			} 
			elseif (isset($var_1)) 
			{
				$variable = $var_1;
			}			
		} 
		else 
		{
			if (isset($var_1)) 
			{
				$variable = $var_1;
			} 
			elseif (isset($var_2)) 
			{
				$variable = $var_2;
			}
		}
		return $variable;
	}
} 


if ( ! function_exists('my_config')) 
{
	/**
	 * An alias to my_config->item() Obj
	 * @param  string $index [the index of the config item]
	 * @return [variable]        [value of the config item]
	 */
	function my_config($index = '') 
	{	
		global $CI;

		return $CI->my_config->item($index); 
	}
}


if ( ! function_exists('imploder')) 
{
    /**
     * [imploder 		Takes an array and implodes the data of a selected row]
     * @param  array 	$array 		this is the array to implode 
     * @param  string 	$index 		this is the index key of the row to implode
     * @param  string 	$delimiter 	the string to put between data
     * @return string    
     */
    function imploder($array = array(), $index = '', $delimiter = ',')
    {
        if ($index === NULL) 
        {
            $index = 'id';
        }

        if (is_array($array)) 
        {
            if (is_object($array)) 
            {
                $array = json_decode(json_encode($array), TRUE);
            } 

            $new_array = [];

            foreach ($array as $key => $value) 
            {
                $new_array[] .= $index ? $value[$index] : $value;
            }
            return implode($delimiter, $new_array);
        }
        return $array;
    }
}


if ( ! function_exists('generate_token')) 
{
    function generate_token($numeric = FALSE, $num_segments = 4, $segment_chars = 5, $license_string_sep = '-', $suffix = null) {
        // Default tokens contain no "ambiguous" characters: 1,i,0,o
        if(isset($suffix))
        {
            // Fewer segments if appending suffix
            $num_segments = 3;
            $segment_chars = 6;
        } 
        // else
        // {
        //     $num_segments = 4;
        //     $segment_chars = 5;
        // }

        if ($numeric) 
        {
            $tokens = '12345678909876543212345678909876';
        }
        else
        {
            $tokens = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        }
        
        $license_string = '';
        // Build Default License String
        for ($i = 0; $i < $num_segments; $i++) 
        {
            $segment = '';
            for ($j = 0; $j < $segment_chars; $j++) 
            {
                $segment .= $tokens[rand(0, strlen($tokens)-1)];
            }
            $license_string .= $segment;
            if ($i < ($num_segments - 1)) 
            {
                $license_string .= $license_string_sep;//'-';
            }
        }
        // If provided, convert Suffix
        if(isset($suffix))
        {
            if(is_numeric($suffix)) 
            {   // Userid provided
                $license_string .= '-'.strtoupper(base_convert($suffix,10,36));
            }
            else
            {
                $long = sprintf("%u\n", ip2long($suffix),true);
                if($suffix === long2ip($long) ) 
                {
                    $license_string .= '-'.strtoupper(base_convert($long,10,36));
                }
                else
                {
                    $license_string .= '-'.strtoupper(str_ireplace(' ','-',$suffix));
                }
            }
        }
        return $license_string;
    }
}


if ( ! function_exists('dateDifference')) 
{

    function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' ) {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);
        
        $interval = date_diff($datetime1, $datetime2);
        
        return $interval->format($differenceFormat);
    }

}


if ( ! function_exists('timeDifference')) 
{
    function timeDifference($time_1, $time_2)
    {
        $datetime1 = strtotime($time_1);
        $datetime2 = strtotime($time_2);
        $interval = abs($datetime2 - $datetime1) / 3600;

        return $interval;
    }
}


if ( ! function_exists('error_redirect')) 
{
    function error_redirect($condition = TRUE, $type = '404')
    {
        if (empty($condition)) 
        {
            redirect('errors/page' . $type);
        }
        return TRUE;
    }
}


if ( ! function_exists('db_inc')) 
{
    /**
     * Method generates incremental function call for database input
     * 
     * @param string    $Prop The database property to increment
     * @param int       $num increment by int or float. 1 by default
     *  
     * @return string
     */
    function db_inc($Prop = '', $num = 1, $protect = TRUE)
    {
        global $CI;

        $tags = ($protect === TRUE ? '`' : '');

        if (!is_numeric($num)) {
            $num = 1;
        }
        $data = $tags . $Prop . $tags . "+" . $num;
        return $CI->db->set($tags . $Prop . $tags, $data, FALSE);
    }
}


if ( ! function_exists('db_dec')) 
{
    /**
     * Method generates decremental function call for database input
     * 
     * @param string    $Prop The database property to decrement
     * @param int       $num decrement by int or float. 1 by default
     *  
     * @return string
     */
    function db_dec($Prop = '', $num = 1, $protect = TRUE)
    {
        global $CI;

        $tags = ($protect === TRUE ? '`' : '');

        if (!is_numeric($num)) {
            $num = 1;
        }
        $data = $tags . $Prop . $tags . "-" . $num;

        return $CI->db->set($tags . $Prop . $tags, $data, FALSE);
    }
}


if ( ! function_exists('encode_html') ) 
{
    function encode_html($html = "")
    {
        return htmlspecialchars($html);
    }
}



if ( ! function_exists('decode_html') ) 
{
    function decode_html($html = "")
    {
        return htmlspecialchars_decode($html);
    }
}

if (! function_exists('show_money')) 
{
    /** 
     *
     * Appends the site currency to a number
     * Prepends the decimal placing for the number
     *
     * @param   string      $amount
     * @param   string      $decimal
     * @return  string     
     */
    function show_money($amount=0, $decimal = 2)
    {
        global $CI; 

        $format = $CI->cr_symbol.number_format($amount, $decimal);
        return $format;
    }
}

if (! function_exists('show_percent')) 
{
    /** 
     *
     * Appends the site currency to a number
     * Prepends the decimal placing for the number
     *
     * @param   string      $amount
     * @param   string      $decimal
     * @return  string     
     */
    function show_percent($v1=0, $v2=0, $show_sym = '%')
    {
        global $CI; 

        $format = number_format(($v1*100)/$v2); 
  
        return $format . $show_sym;
    }
}

if (! function_exists('percentage_color')) 
{
    /** 
     *
     * Sets a bootstrap color based on the
     * supplied percentage
     *
     * @param   string      $amount
     * @param   string      $decimal
     * @return  string     
     */
    function percent_color($percentage = 0, $show_caret = FALSE)
    { 
        $percentage = str_ireplace('%', '', $percentage);
        $color = 'text-danger';
        $caret = 'fa-caret-down';
        if ($percentage <= 25 && $percentage >= 1) 
        {
            $color = 'text-warning';
            $caret = 'fa-caret-left';
        }
        elseif ($percentage >= 25) 
        {
            $color = 'text-success';
            $caret = 'fa-caret-up';
        }
        
        if ($show_caret) {
            return $caret;
        }
        return $color;
    }
}

// --------------------------------------------------------------------

if ( ! function_exists('c_card_state'))
{
    /**
     * Elements
     *
     * Checks the current state of a card and returns and adds a corresponding class
     *
     * @param   string 
     * @return  string  
     */
    function c_card_state($card_id = '', $page = '', $uid = '')
    {
        global $CI; 

        $state = $CI->db->select('state')
            ->where('page', $page)->where('uid', $uid)->where('card_id', $card_id)
            ->get('card_state')->row_array()['state']; 
 
        return ' data-card-state="' . $state . '"';
    }
}

// --------------------------------------------------------------------

if ( ! function_exists('hide_c_state'))
{
    /**
     * Elements
     *
     * Checks the current state of a card and returns and adds a corresponding class
     *
     * @param   string 
     * @return  string  
     */
    function hide_c_state($uid = '', $page = '')
    {
        global $CI; 

        $state = $CI->db->where('uid', $uid)
            ->where('page', $page)
            ->where('state !=', '')
            ->count_all_results('card_state');  
        
        if ($state<=0) {
            return ' style="display: none;"';
        }
    }
}


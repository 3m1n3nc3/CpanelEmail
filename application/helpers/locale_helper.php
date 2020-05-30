<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

// --------------------------------------------------------------------

if ( ! function_exists('select_countries'))
{
    /**
     * Select Countries
     *
     * Lets you fetch a list of countries available from the countries database
     *
     * @param   mixed
     * @param   integer 
     * @return  mixed   depends on what the type specifies
     */
    function select_countries($value = '', $type = 0, $optional = FALSE) {

        $CI = & get_instance();  

        $locale = $CI->locale_model->fetch_countries(); 
        
        if($type == 0) 
        {
            $rows = '
            <option disabled>Please Select Country</option>';
            $rows .= 
            ($optional === TRUE) ? 
            '<option value="0"'.set_select('customer_nationality', TRUE).'>Not Applicable</option>' : '';

            foreach($locale AS $country) 
            {
                if(mb_strtolower($value) == mb_strtolower($country['name'])) 
                {
                    $selected = ' selected="selected"';
                } 
                else 
                {

                    $selected = '';
                }
                $rows .= '
                <option value="'.$country['name'].'" id="'.$country['id'].'"'.$selected.'>'.$country['name'].'</option>';
            }
            return $rows;
        } 
        else 
        {
            foreach($locale as $code) 
            {
                if($value == $code['name']) 
                { 
                    return $code['sortname'];
                }
            }
        }   
        return $locale;
    }
}


// --------------------------------------------------------------------


if ( ! function_exists('select_states'))
{
    /**
     * Select States
     *
     * Lets you fetch a list of states for the country 
     * specified in the id parameter from the states database
     *
     * @param   mixed 
     * @return  string   html select option of containing a list of states
     */
    function select_states($country_id = '', $value = '') 
    {

        $CI = & get_instance();  

        $locale = $CI->locale_model->fetch_states(['country_id' => $country_id]); 
         
        $rows = '
        <option disabled>Please Select State</option>';

        foreach($locale AS $state) 
        {
            if(mb_strtolower($value) == mb_strtolower($state['name'])) 
            {
                $selected = ' selected="selected"';
            } 
            else 
            {

                $selected = '';
            }
            $rows .= '
            <option value="'.$state['name'].'" id="'.$state['id'].'"'.$selected.'>'.$state['name'].'</option>';
        }
        return $rows;    
    }
}


// --------------------------------------------------------------------


if ( ! function_exists('select_cities'))
{
    /**
     * Select Cites
     *
     * Lets you fetch a list of Cites for the state 
     * specified in the id parameter from the cities database
     *
     * @param   mixed 
     * @return  string   html select option of containing a list of states
     */
    function select_cities($state_id = '', $value = '') 
    {

        $CI = & get_instance();  

        $locale = $CI->locale_model->fetch_cities(['state_id' => $state_id]); 
         
        $rows = '
        <option disabled>Please Select a City</option>';

        foreach($locale AS $city) 
        {
            if(mb_strtolower($value) == mb_strtolower($city['name'])) 
            {
                $selected = ' selected="selected"';
            } 
            else 
            {

                $selected = '';
            }
            $rows .= '
            <option value="'.$city['name'].'" id="'.$city['id'].'"'.$selected.'>'.$city['name'].'</option>';
        }
        return $rows;    
    }
}

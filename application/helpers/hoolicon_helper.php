<?php   

if ( ! function_exists('alert_notice'))
{
    /** 
     *
     * Returns a bootstrap alert
     *
     * @param   string      $msg            The message string
     * @param   string      $type           [info,danger,error,warning,success]   
     * @param   string      $echo           whether to echo the notice or return it
     * @param   string      $dismissible    whether the notice can be dismissed
     * @return  string
     */
    function alert_notice($msg = '', $type = 'info', $echo = FALSE, $dismissible = TRUE, $header = '')
    {   
        $icon = $dismissible_alert = $dismiss_btn = '';
        if ($type == 'danger' || $type == 'error') 
        {
            $title = 'Error!';
            $icon = 'ban';
            $type = 'danger';
        } 
        elseif ($type == 'warning') 
        {
            $title = 'Warning!';
            $icon = 'exclamation-triangle';
        } 
        elseif ($type == 'info') 
        {
            $title = 'Notice';
            $icon = 'info';
        } 
        elseif ($type == 'success') 
        {
            $title = 'Success';
            $icon = 'check';
        }

        if ($dismissible === TRUE) 
        {
            $dismissible_alert = ' alert-dismissible';
            $dismiss_btn = ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        }

        if ($msg != '') 
        {
            if ($header) 
            {
                $title = $header;
            }
            
            if ($dismissible !== 'FLAT') 
            {
                $alert = 
                '<div class="alert alert-'.$type.$dismissible_alert.'">
                    '.$dismiss_btn.'
                    <h5><i class="icon fa fa-'.$icon.'"></i> '.$title.'</h5>
                    '.str_ireplace('.', '.', $msg).'
                </div>';
            } else {
                $alert  = 
                '<div class="alert alert-'.$type.$dismissible_alert.'">
                    '.$dismiss_btn.'
                    <i class="icon fa fa-'.$icon.'"></i>
                    '.$msg.'.
                </div>';
            }
            if ($echo) 
            {
                echo $alert;
                return;
            }
            return $alert;
        }
        return;
    }
} 

if ( ! function_exists('supr_replace'))
{
    function supr_replace($string = '', $caps = '', $predef = '')
    {
        if (!$predef) 
        {
            $string = str_ireplace('_', ' ', $string);
            $string = str_ireplace('-', ' ', $string);
        }
        if ($caps) 
        {
            return ucwords($string);   
        }
        return $string;
    } 
}

if ( ! function_exists('service_point_access'))
{
    /** 
     *
     * Checks if a user has the specified privilege
     *
     * @param   string      $role   
     * @return  boolen
     */
    function service_point_access($department_id = '')
    {
        $CI = & get_instance(); 
 
        if ($CI->logged_user['role'] == 2)
        {
           return true;
        }

        if ($CI->logged_user['department_id'] === $department_id) 
        {   
            return true; 
        } 

        return false; 
    } 
}

    
if ( ! function_exists('service_point_access_session'))
{ 
    /** 
     *
     * Checks if a user has any privileges
     *
     * @param   mixed      $check   TRUE: Check, FALSE: Set Session, Anything Else: Clear Session   
     * @return  boolen
     */
    function service_point_access_session($check = FALSE)
    {
        $CI = & get_instance(); 

        if ($check === TRUE) 
        { 
            if (isset($_SESSION['service_point_access'])) 
            {
                return TRUE;
            } 
        }
        elseif ($check === FALSE) 
        { 
            $list_services = $CI->services_model->get_service();
            if ($list_services)
            {              
                $service_point_access = [];
                foreach ($list_services AS $service)
                { 
                    if (service_point_access($service->id))
                    {
                        $service_point_access[] .= $service->id; 
                    }
                }

                if ($service_point_access && !isset($_SESSION['service_point_access'])) 
                    $CI->session->set_userdata('service_point_access', $service_point_access);
            }
        }
        else
        {   
            if (isset($_SESSION['service_point_access'])) 
            {
                $CI->session->unset_userdata('service_point_access');
            }
        }
    }
}


if ( ! function_exists('showBBcodes'))
{ 

    /** 
    * A simple PHP BBCode Parser function
    *
    * @author Afsal Rahim
    * @link http://digitcodes.com/create-simple-php-bbcode-parser-function/
    * Extended by passtech
    * 
    **/

    //BBCode Parser function

    function showBBcodes($text, $class = '') {
        // BBcode array
        $find = array(
            '~\[b\](.*?)\[/b\]~s',
            '~\[i\](.*?)\[/i\]~s',
            '~\[u\](.*?)\[/u\]~s',
            '~\[quote\](.*?)\[/quote\]~s',
            '~\[size=(.*?)\](.*?)\[/size\]~s',
            '~\[color=(.*?)\](.*?)\[/color\]~s',
            '~\[link=(.*?)\](.*?)\[/link\]~s',
            '~\[url\]((?:ftp|https?)://.*?)\[/url\]~s',
            '~\[img\](https?://.*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
        );

        $class = ($class ? ' class="' . $class .'" ' : '');

        // HTML tags to replace BBcode
        $replace = array(
            '<'.$class.'b>$1</b>',
            '<'.$class.'i>$1</i>',
            '<'.$class.'span style="text-decoration:underline;">$1</span>',
            '<'.$class.'pre>$1</'.'pre>',
            '<span'.$class.' style="font-size:$1px;">$2</span>',
            '<span'.$class.' style="color:$1;">$2</span>',
            '<a'.$class.' href="$1">$2</a>',
            '<a'.$class.' href="$1">$1</a>',
            '<img'.$class.' src="$1" alt="" />'
        );

        // Replacing the BBcodes with corresponding HTML tags
        return preg_replace($find,$replace,$text);
    }

    // How to use the above function:

    // $bbtext = "This is [b]bold[/b] and this is [u]underlined[/u] and this is in [i]italics[/i] with a [color=red] red color[/color]";
    // $htmltext = showBBcodes($bbtext);
    // echo $htmltext; 
}

if ( ! function_exists('social_link_fix') ) 
{
    function social_link_fix($link = '')
    {
        $match = preg_match(
            '|(https?://)?(www\.)?([a-z0-9]*.[a-z0-9]*)?\/(#!/)?@?([^/]*)|', $link, $matches
        );

        if ($matches) 
        {
            return ($matches[1] ?? '') . ($matches[3] ?? '') . '/'. ($matches[5] ?? '');
        }

        return $link;
    }
}


if ( ! function_exists('social_link') ) 
{
    function social_link($link = '', $site = '')
    {
        $link = social_link_fix($link);

        if ( ! $link ) 
        { 
            return 'https://' . $site . '.com/' . $link; 
        }

        return $link;
    }
}


if ( ! function_exists('o2array') ) 
{
    /** 
     *
     * Converts an Object of standard type to Array
     *
     * @param   object      $obj   
     * @return  array
     */
    function o2array($obj) {
        
        if (is_object($obj))
            $obj = (array) $obj;

        if (is_array($obj)) {
            $new = array();
            foreach ($obj as $key => $val) {
                $new[$key] = o2array($val);
            }
        } 

        else {
            $new = $obj;
        }

        return $new;
    }
}


if ( ! function_exists('sprintlang') ) 
{
    /** 
     *
     * Converts an Object of standard type to Array
     *
     * @param   object      $obj   
     * @return  array
     */
    function sprintlang($line, $sprint_f = '') {
        
        $line = get_instance()->lang->line($line);

        if ($sprint_f) 
        {
            if (is_array($sprint_f)) 
            {
                $line = vsprintf($line, $sprint_f); 
            }
            else
            {
                $line = sprintf($line, $sprint_f); 
            }
        }

        return $line;
    }
}


if ( ! function_exists('timeAgo') ) 
{
    /**
     * Time Difference function
     */     
    function timeAgo($time, $x=0)
    {
        // Use strtotime() function to convert your time stamps before sending to the plugin

        $time_difference = time() - $time;

        if($time_difference < 1 && $x==0) { return 'less than 1 second ago'; }
        $seconds = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second', 
                   -1                       =>  'millisecond' 
        );

        foreach( $seconds as $secs => $ret )
        {
            $diff = $time_difference / $secs;

            if( $diff >= 1 )
            {
                $t = round( $diff );
                $y = $ret == 'hour' || $ret == 'minute' || $ret == 'second' || $ret == 'millisecond' ? true : false;
                // Check the request type
                if ($x == 1) {
                    if ($ret == 'day' && $t==1) {
                        // If the time is been more than a day but less than two show yesterday
                        return date('h:i A', $time).' | Yesterday'; 
                    } elseif ($ret == 'year') {
                        // If the time is been up to a year show full year
                        return date('h:i A', $time).' | '.date('F j Y', $time); 
                    } elseif ($y) {
                        // If the time is been less than or equal to a day show today
                        return date('h:i A', $time).' | Today'; 
                    } else {
                        // If the time is been more than two days show the date
                        return date('h:i A', $time).' | '.date('F j', $time); 
                    }                   
                } elseif ($x == 2) {
                    // Show only date
                    if ($ret == 'year' && $t==1) {
                        // If the time is been more than a day but less than two show yesterday
                        return date('M j Y', $time);
                    } else {
                        return date('M j', $time);
                    }
                } else {
                    return 'About ' . $t . ' ' . $ret . ( $t > 1 ? 's' : '' ) . ' ago';
                }
            }
        }
    }
}

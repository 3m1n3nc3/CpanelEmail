<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Creative_lib {

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('date');
    }

    public function resize_image($src = '', $_config = '')
	{	

		$config['image_library'] 	= isset($_config['image_library']) ? $_config['image_library'] : 'gd2';
		$config['maintain_ratio'] 	= isset($_config['maintain_ratio']) ? $_config['maintain_ratio'] : TRUE;
		$config['width'] 			= isset($_config['width']) ? $_config['width'] : 450;
		$config['height'] 			= isset($_config['height'])? $_config['height'] : 450;
		$config['source_image'] 	= $src;  

		$this->CI->load->library('image_lib', $config);

		if ( ! $this->CI->image_lib->resize())
		{
		    return $this->CI->image_lib->display_errors();
		} 
		else 
		{	
			chmod($config['source_image'],0777);
			return TRUE;
		}

	} 

	public function create_dir($path = '', $error = null) 
	{ 
		if (file_exists($path)) {
			
			if(is_writable($path)) 
			{	
				return TRUE;
			} 
			else 
			{
				return chmod($path, 0777) OR FALSE;
			}  
		}
		elseif (!$error) 
		{	
			return mkdir($path, 0777, TRUE);  
		}
		
		return FALSE;
	}

	public function delete_file($path = '')
	{
		if (file_exists($path) && is_file($path)) 
        {
			chmod($path, 0777);
			return unlink($path);
		}
		return FALSE;
	}

	public function fetch_image($src = '', $type = 1)
	{	  
		if ($src && file_exists('./'.$src)) 
        {
			return base_url().$src;
		} 
        else 
        {
			return base_url().'backend/img/default'.$type.'.png';
		}
	}
   
    public function upload($index, $previous = NULL, $new_name = NULL, $folder = NULL, $resize = array(), $set_post = FALSE)
    {
        if (isset($_FILES[$index]) && $_FILES[$index]['name']) 
        { 
            $upload_data = array();
            $upload_errors = FALSE;
            $fileInfo    = pathinfo($_FILES[$index]["name"]);

            // Set the new name for the upload
            if (!$new_name) 
            {
                $new_name = 'img_'.rand().'_'.rand();
            }

            $new_name   = $new_name.'.' . $fileInfo['extension'];
            $new_folder = ($folder) ? $folder . '/' : 'sites/';
            $directory  = 'uploads/' . $new_folder;

            // Set the parameters for this upload
            $_config['upload_path']      = './' . $directory;
            $_config['allowed_types']    = 'jpg|png|jpeg';
            $_config['max_size']         = '1500'; 
            $_config['file_name']        = $new_name;
            $_config['overwrite']        = TRUE;
            $_config['remove_spaces']    = TRUE;
            $_config['file_ext_tolower'] = TRUE; 

            // Initialize the upload
            $this->CI->upload->initialize($_config);

            // Create a directory for this upload, if it doesn't exist
            if ( ! $this->create_dir($_config['upload_path']) ) 
            {
                $this->CI->upload->set_error('upload_unable_to_write_file', 'debug');
                $upload_errors = $this->CI->upload->display_errors(); 
            } 
            else 
            {
                // Delete any previous file if set
                $this->delete_file('./' . $previous);
            }

            // Do the upload
            if ( ! $this->CI->upload->do_upload($index) )
            {
                $upload_errors = $this->CI->upload->display_errors(); 
            }
            else
            {
                // Fetch the upload data
                $upload_data             = $this->CI->upload->data(); 
                $upload_data['new_path'] = $directory . $new_name;  
                $upload_data['errors']   = $upload_errors;  
                chmod($_config['upload_path'] . $new_name, 0777);

                // Set post data for this upload (important if you do not 
                // intend to retrieve upload file directory from upload_data)
                if ($set_post) 
                {
                    if ($set_post === TRUE) 
                    {
                        $_POST[$index]    = $upload_data['new_path'];
                    }
                    else
                    {
                        if (is_array($set_post))
                        { 
                            foreach ($set_post as $key => $value) 
                            { 
                                $param = array($value => $upload_data['new_path']);
                                $_NEWPOST[$key] = $param;
                            }
                            if (isset($_POST)) 
                            {
                                $_POST = array_merge($_POST, $_NEWPOST);
                            }
                            else
                            {
                                $_POST = $_NEWPOST;
                            }
                        }
                        else
                        {
                            $_POST[$set_post] = $upload_data['new_path'];
                        } 
                    }
                }

                 // Array ( [value] => Array ( [site_name] => Hayatt Regency Suited ) [site_logo] => uploads/sites/site_logo.png )
                // Resize this image file
                if ($resize) 
                {
                    // $resize['width'], $resize['height']
                    $this->resize_image($_config['upload_path'].$new_name, $resize); 
                }
            }
            
            $this->upload_errors = array();
            if ($upload_errors) 
            {
                $this->upload_errors[$index] = $upload_errors;
            }

            // Return the upload data array
            if (isset($upload_data)) 
            {
                return $upload_data;    
            }

            return FALSE;
        }
    }

    public function upload_errors($index = NULL, $prefix = '<p>', $suffix = '</p>')
    {
        if (isset($this->upload_errors[$index])) 
        {
            return $prefix . $this->upload_errors[$index] . $suffix;
        } 

        return FALSE;
    } 

	public function month_rearray($months = array(), $lt = "'", $rt = "'")
	{	
		$keys = array_values($months);

		if ( in_array( $lt . 'January' . $rt, $keys) or in_array( $lt . 'Jan' . $rt, $keys) ) {
			$key_data[1] 	= $lt . 'January' . $rt;
		}
		if ( in_array( $lt . 'February' . $rt, $keys) or in_array( $lt . 'Feb' . $rt, $keys)  ) {
			$key_data[2] 	= $lt . 'February'.$rt;
		}
		if ( in_array( $lt . 'March' . $rt, $keys)  or in_array( $lt . 'Mar' . $rt, $keys) ) {
			$key_data[3] 	= $lt . 'March'.$rt;
		}
		if ( in_array( $lt . 'April' . $rt, $keys)  or in_array( $lt . 'Apr' . $rt, $keys) ) {
			$key_data[4] 	= $lt . 'April'.$rt;
		}
		if ( in_array( $lt . 'May' . $rt, $keys)  or in_array( $lt . 'May' . $rt, $keys) ) {
			$key_data[5] 	= $lt . 'May'.$rt;
		}
		if ( in_array( $lt . 'June' . $rt, $keys)  or in_array( $lt . 'Jun' . $rt, $keys) ) {
			$key_data[6] 	= $lt . 'June'.$rt;
		}
		if ( in_array( $lt . 'July' . $rt, $keys)  or in_array( $lt . 'Jul' . $rt, $keys) ) {
			$key_data[7] 	= $lt . 'July'.$rt;
		}
		if ( in_array( $lt . 'August' . $rt, $keys)  or in_array( $lt . 'Aug' . $rt, $keys) ) {
			$key_data[8] 	= $lt . 'August'.$rt;
		}
		if ( in_array( $lt . 'September' . $rt, $keys)  or in_array( $lt . 'Sept' . $rt, $keys) ) {
			$key_data[9] 	= $lt . 'September'.$rt;
		}
		if ( in_array( $lt . 'October' . $rt, $keys)  or in_array( $lt . 'Oct' . $rt, $keys) ) {
			$key_data[10] 	= $lt . 'October'.$rt;
		}
		if ( in_array( $lt . 'November' . $rt, $keys)  or in_array( $lt . 'Nov' . $rt, $keys) ) {
			$key_data[11] 	= $lt . 'November'.$rt;
		}
		if ( in_array( $lt . 'December' . $rt, $keys)  or in_array( $lt . 'Dec' . $rt, $keys) ) {
			$key_data[12] 	= $lt . 'December'.$rt;
		}

    	return array_values($key_data);
	}

}

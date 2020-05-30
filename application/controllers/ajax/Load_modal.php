<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Load_modal extends MY_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	} 
    

    /**
     * Loads the view dynamically for the upload modal 
     * @return null     Does not return anything but echoes a JSON Object with the response
     */
	public function upload_image()
	{
		$data = $this->input->post(NULL, TRUE); 
		
		$content = $this->load->view($this->h_theme.'/extra_layout/upload_resize_image', $data, TRUE);

		echo json_encode(['content' => $content], JSON_FORCE_OBJECT);
		return;
	} 
}

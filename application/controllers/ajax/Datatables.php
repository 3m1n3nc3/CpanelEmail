<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sends response with data records to jquery datatable
 */ 
class Datatables extends MY_Controller 
{ 
    
    public function index()
    {
        // $this->load->view('datatable');
    } 
     

    /**
     * Get the room sales report
     * @param  string   $show_btn 
     * @return null     Does not return anything but echoes a JSON Object with the response
     */
    public function room_sales_report($show_btn = TRUE)
    {
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $order  = $this->input->post("order");
        $search = $this->input->post("search");
        $search = $search['value'];
        $col    = 0;
        $dir    = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }

        $valid_columns = array(  
            0=>'customer_id', 
            2=>'room_id', 
            1=>'room_sales_price', 
            3=>'reservation_ref',  
            4=>'reservation_date',  
        );

        $get_query = $this->input->get(NULL, TRUE);
        if ($get_query) 
        {
            if (isset($get_query['from'])) 
            {
                $this->db->where('reservation_date >=', $get_query['from']);  
            } 
            
            if (isset($get_query['to'])) 
            {
                $this->db->where('reservation_date <=', $get_query['to']);  
            } 
        }

        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }

        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }

        $this->db->limit($length,$start);   
        $this->db->select('room_sales.customer_id, room_sales.reservation_id, room_sales.room_id, room_sales_price, reservation_ref, reservation_date');
        $content = $this->db->join('reservation', 'room_sales.reservation_id = reservation.reservation_id', 'LEFT')->get("room_sales");
        $data = array();
        foreach($content->result() as $rows)
        {   
            $customer = $this->account_data->fetch($rows->customer_id, 1); 
            $room = $this->room_model->getRoom(['id' => $rows->room_id]);

            $data[]= array(   
                '<a href="'.site_url('customer/data/'.$rows->customer_id.'').'">
                    '.$customer['name'].'
                </a>', 
                '<a href="'.site_url('room/reserved_room/'.$rows->room_id.'/'.$rows->customer_id.'').'">
                    '.$room['room_type'] . ' ' . lang('room') .' ' . $rows->room_id.'
                </a>',  
                $this->cr_symbol.number_format($rows->room_sales_price, 2), 
                $rows->reservation_ref,  
                date("d M Y h:i A", strtotime($rows->reservation_date)),  
                $show_btn ?  '
                    <a href="javascript:void(0)" onclick="return confirmDelete(\''.site_url('accounting/cashier/delete_room_sale/'.$rows->reservation_id).'\', 1)" class="btn btn-danger btn-sm m-1" data-toggle="tooltip" title="Delete">
                        <i class="btn-icon-only fa fa-trash fa-fw text-white"></i>
                    </a>'
                : '',
                20 => 'tr_'.$rows->reservation_id
            );     
        }
        $total_content = $this->total_room_sales_report($get_query);
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_content,
            "recordsFiltered" => $total_content,
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }
    

    /**
     * Get count of the total the room sales
     * @param  string   $get_query 
     * @return Object
     */
    public function total_room_sales_report($get_query)
    {       
        if ($get_query) 
        {
            if (isset($get_query['from'])) 
            {
                $this->db->where('reservation_date >=', $get_query['from']);  
            } 
            
            if (isset($get_query['to'])) 
            {
                $this->db->where('reservation_date <=', $get_query['to']);  
            } 
        }

        $this->db->select("COUNT(room_sales.reservation_id) as num");
        $query = $this->db->join('reservation', 'room_sales.reservation_id = reservation.reservation_id', 'LEFT')->get("room_sales");
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }
}

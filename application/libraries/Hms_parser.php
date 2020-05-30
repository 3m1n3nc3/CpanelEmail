<?php
    
if (!defined('BASEPATH'))
    exit('No direct script access allowed'); 

class Hms_parser 
{ 

    public function __construct() 
    { 
        $this->CI = & get_instance(); 
    }

    public function reservations()
    {
        $this->CI->load->model('reservation_model');
        $data = $this->CI->reservation_model->fetch_reservation();//print_r($data);

        $reserved = [];
        foreach ($data as $reservations) 
        {
            $customer = $this->CI->customer_model->get_customer(['id' => $reservations['customer_id']]); 
            $room = $this->CI->room_model->getRoom(['id' => $reservations['room_id']]); 

            $checkin_date_string  = strtotime($reservations['checkin_date']);
            $checkout_date_string = strtotime($reservations['checkout_date']);
 
            $start_date = date('Y-m-d H:i:s', $checkin_date_string); 
            $end_date = date('Y-m-d H:i:s', $checkout_date_string); 

            $title = $room['room_type'].' Room '.$room['room_id'].' ('.$customer['customer_firstname']. ' ' .$customer['customer_lastname'].')';

            $reserved[] = 
            '{
                title          : \''.$title.'\',
                start          : Date.createFromPHP("'.$start_date.'"), 
                end            : Date.createFromPHP("'.$end_date.'"), 
                url            : \''.site_url('room/reserved_room/'.$room['room_id'].'/'.$customer['customer_id']. '').'\',
                backgroundColor: \'#00a65a\', //#f39c12-yellow
                borderColor    : \'#00a65a\', //yellow
                textColor      : \'#fff\' //yellow
            }';
        }
        $reservation = implode(', ', $reserved);
        return $reservation;
    }

    public function payment_stats($year = '')
    {
        $year = ($year) ? $year : date('Y');

        $this->CI->load->model('accounting_model');
        $statistics = $this->CI->accounting_model->site_statistics(['monthly' => true, 'year' => $year]);
// print_r($statistics);
        $data = $label = [];
        foreach ($statistics as $key => $stats) 
        { 
            $month   = DateTime::createFromFormat('!m', $stats['month'])->format('F');
            $data[]  = $stats['amount'];
            $label[] = '\''.$month.'\'';
        }
        $stats  = '[' . implode(', ', $data) . ']';
        $labels = '[' . implode(', ', $label) . ']';

        $datasets = 
        "{
            labels  : $labels,
            datasets: [
              {
                label               : 'Payments',
                backgroundColor     : 'rgba(60,141,188,0.9)',
                borderColor         : 'rgba(60,141,188,0.8)',
                pointRadius         : false,
                pointColor          : '#3b8bba',
                pointStrokeColor    : 'rgba(60,141,188,1)',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data                : $stats
              } 
            ]
        }";
        return $datasets;
    }


    public function update_stock($items = array(), $quantity = array())
    {
        foreach ($items as $key => $sid) 
        {
            $res = $this->CI->services_model->get_stock(array('item_id' => $sid));

            $items['item_id'] = $sid;

            if ($res['item_quantity'] > 0) 
            {
                $sub_quantity = isset($quantity[$key]) ? $quantity[$key] : 1;
                $new_quantity = ($res['item_quantity']-$sub_quantity);
                $items['item_quantity'] = $new_quantity;

                $this->CI->services_model->update_stock($items); 
            } 
        }
    }


    public function show_rooms($show = TRUE, $page = '')
    {
        $room_types = $this->CI->room_model->get_room_types(); 

        $room_mate = '
        <section class="accomodation_area'.($page !== 'rooms' ? ' section_gap' : '').'">
            <div class="container">'.
                ($page !== 'rooms' ? '
                <div class="section_title text-center">
                    <h2 class="title_color">'.lang('show_rooms_title').'</h2>
                    <p>'.lang('show_rooms_description').'</p>
                </div>' : '').
                '<div class="row mb_30">';
            
                $rooms_format = [];
                foreach ($room_types as $type) 
                {
                    $rooms_format[] .= '
                    <div class="col-lg-3 col-sm-6">
                        <div class="accomodation_item text-center">
                            <div class="hotel_img">
                                <img src="'.$this->CI->creative_lib->fetch_image($type->image).'" alt="" style="max-height:250px;">
                                <a href="'.site_url('page/rooms/book/'.$type->id).'" class="btn theme_btn button_hover">Book Now</a>
                            </div>
                            <a href="'.site_url('page/rooms/'.$type->id).'"><h4 class="sec_h4">'.$type->room_type.'</h4></a> 
                            <h5>'.$this->CI->cr_symbol.number_format($type->room_price, 2).'<small>/night</small></h5>
                        </div>
                    </div>';
                }
                $room_mate .= implode(' ', $rooms_format);
                $room_mate .= '
                </div>
            </div>
        </section>'; 
 
        if ($room_types && $show) {
            return $room_mate;
        }
        return;
    }


    public function show_facilities($show = TRUE, $page = '')
    {
        $facilities = $this->CI->content_model->get_facilities(); 

        $facilitate = '
        <section class="facilities_area section_gap">
            <div class="overlay bg-parallax" data-stellar-ratio="0.8" data-stellar-vertical-offset="0" data-background="">  
            </div>
            <div class="container">
                '.(
                    $page !== 'facilities' && my_config('facilities_title') ? '
                    <div class="section_title text-center">
                        <h2 class="title_w">'.my_config('facilities_title').'</h2>
                        <p>'.my_config('facilities_content').'</p>
                    </div> ' : ''
                ).'
                <div class="row mb_30">';
            
                $facility_format = [];
                foreach ($facilities as $facility) 
                {
                    $facility_details = ($page !== 'facilities' ? strip_tags(decode_html($facility['details'])) : decode_html($facility['details']));

                    $facility_format[] .= '
                    <div class="col-lg-4 col-md-6">
                        '.(
                            $page === 'facilities' ? '
                            <img class="img-fluid rounded mb-2" src="'.$this->CI->creative_lib->fetch_image($facility['image']).'" alt="'.$facility['title'].' Img">' : ''
                        ).'
                        <div class="facilities_item">
                            <h4 class="sec_h4"><i class="fa '.pass_icon(3, $facility['icon']).'"></i>'.$facility['title'].'</h4>
                            <p>'.decode_html($facility_details).'</p>
                        </div>
                    </div> ';
                }
                $facilitate .= implode(' ', $facility_format);
                $facilitate .= '
                </div>
            </div>
        </section>'; 
 
        if ($facilities && $show) {
            return $facilitate;
        }
        return;
    }


    public function show_booking_area($show = TRUE, $room_id = '')
    {   
        $room_type      = $this->CI->room_model->getRoomType($room_id, TRUE);
        $rand_room_type = o2Array($this->CI->room_model->get_room_types(NULL, TRUE));
        shuffle($rand_room_type); 

        if (!$room_type) 
        {   
            $room_type = $rand_room_type[0];
        }  
        
        $booking_area = $this->CI->load->view($this->CI->h_theme.'/homepage/booking_area', array('room_type' => $room_type), TRUE);
        if ($show && $room_type) 
        {
            return $booking_area;
        }
        return;
    } 


    public function show_contact_area($show = TRUE)
    {
        $contact_area = $this->CI->load->view($this->CI->h_theme.'/homepage/contact_area', array(), TRUE);
        if ($show) {
            return $contact_area;
        }
        return;
    } 


    public function show_invoice($data = array(), $show = TRUE)
    {
        $invoice = $this->CI->load->view($this->CI->h_theme.'/homepage/invoice_inline', $data, TRUE);
        if ($show) {
            return $invoice;
        }
        return;
    } 


    public function navbar_links($page = '', $subpage = '', $show = TRUE)
    {   
        $links = [];
        foreach($this->CI->content_model->get(['parent' => 'non', 'in' => 'header', 'order_field' => ['name' => 'safelink', 'id' => 'homepage']]) AS $navbar_link)
        {
            $link_title = ($navbar_link['safelink'] == 'homepage' ? lang('home') : $navbar_link['title']);
            $links[] .= '
            <li class="nav-item'.($navbar_link['safelink'] === $page ? ' active' : '').'">
                <a class="nav-link" href="'.site_url('page/'.$navbar_link['safelink']).'">'.$link_title.'</a>
            </li>';
        }
        if ($this->CI->account_data->customer_logged_in()) 
        {
            $links[] .= ' 
            <li class="nav-item submenu dropdown'.('account' === $page ? ' active' : '').'">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.lang('my_account').'</a>
                <ul class="dropdown-menu">
                    <li class="nav-item"><a class="nav-link" href="'.site_url('account').'">'.lang('account').'</a></li>
                    <li class="nav-item"><a class="nav-link active" href="'.site_url('reservations').'">'.lang('reservations').'</a></li>
                    <li class="nav-item"><a class="nav-link active" href="'.site_url('my-payments').'">'.lang('my_payments').'</a></li>
                    <li class="nav-item"><a class="nav-link" href="'.site_url('account/login/logout').'">'.lang('signout').'</a></li>
                </ul>
            </li> ';
        }
        else
        {
            $links[] .= '
            <li class="nav-item'.('login' === $page ? ' active' : '').'">
                <a class="nav-link" href="'.site_url('account/login').'">'.lang('signin').'</a>
            </li>';
        }

        $show_links = implode(' ', $links);

        if ($show) {
            return $show_links;
        }
        return;
    } 
}

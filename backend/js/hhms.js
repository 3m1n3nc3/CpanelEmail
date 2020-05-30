function error_message(xhr, status, error, mid) {
    var errorMessage = 'An Error Occurred - ' + xhr.status + ': ' + xhr.statusText + '<br> ' + error;  
    
    $(mid).html( 
        '<div class="card m-2 text-center">'+
            '<div class="card-header p-2">Server Response: </div>'+
            '<div class="card-body p-2 text-info">'+
                '<div class="card-text font-weight-bold text-danger">'+errorMessage+'</div>'
                +xhr.responseText+
            '</div>'+
        '</div>'
    );
}

function confirmDelete(data, inline) { 
    var button_ajax   = '<a href="javascript:void(0)" class="p-0 mx-1 text-white btn btn-md btn-block btn-danger" data-delete="true" onclick="'+data+'" id="delete_confirm"> Yes Delete </a>';
    var button_inline = '<a href="'+data+'" class="p-0 mx-1 text-white btn btn-md btn-block btn-danger" data-delete="true" id="delete_confirm"> Yes Delete </a>';
    var button_list   = 
        '<div class="font-weight-bold">'+ (inline ? button_inline : button_ajax) +
            '<button type="button" class="p-0 mx-1 text-white btn btn-md btn-block btn-info" data-dismiss="modal">No</button>'+
        '</div>';

    $('#actionModal .modal-body').html('<p>Are you sure you want to delete this item?</p>');
    $('#actionModal .modal-body').append(button_list);
    $('#actionModal .modal-title').html('Confirmation');
    $("#actionModal").modal('show'); 
}

$('#actionModal').on('hide.bs.modal', function(e) { 
    $('#actionModal .modal-body, #actionModal .modal-title').html('');
    $('#actionModal .modal-body').html('<div class="loader"><div class="spinner-grow text-warning"></div></div>');
})


/**
 * Delete A contest
 * @param  {[string]} data [json string representing the data to be sent along with the request]
 * @return {[string]}      [null]
 */
function deleteItem(data) {
    console.log(data);

    if (data.init === 'dt') {
        var tr_id = '#tr_'+data.id;
    } else if (data.init === 'table') {
        var tr_id = '#table_row_'+data.id;
    } else {
        var tr_id = '#item-'+data.id;
    } 

    var m_id = '#msg_box';

    $(m_id).html('');

    var confirm = $('#delete_confirm').data('delete'); 

    if (confirm === true) {
        
        $("#actionModal").modal('hide'); 
        $(tr_id+' .btn').removeAttr('onclick');

        $.ajax({
            type: 'POST',
            url: siteUrl+'ajax/connect/deleteItem',
            data: data,  
            dataType: 'JSON',
            success: function(resps) {  console.log(resps);
                if (resps.response === true) { 
                    if (data.action === 1) {
                        $(tr_id).fadeOut('slow'); 
                    }
                }
            },
            error: function(xhr, status, error) {
                error_message(xhr, status, error, m_id);
            } 
        })
    } else {
        var onclick = $(tr_id+' .deleter').attr('onclick');
        confirmDelete(onclick);
    }
}


// $("*", document.body).click( function(event) {
//     event.stopPropagation();
//     var domElement = $(this);$(this).toggleClass('border border-info');
//     console.log($(this));
//     console.log(domElement.get(0));
//     console.log('Clicked on ' + domElement.get(0).nodeName);
// })   

function safeLinker(e) { 
    var title = $.trim($(e).val()); 
    title = title.replace(/[^a-zA-Z0-9-]+/g, '-');

    var safelink = 'input[name="safelink"]';
    $(safelink).val(title.toLowerCase());
}   


var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

function redirect(path){
    window.location.href = site_url(path);
}


function get_notifications(){
    if (!is_logged()) {
        redirect('login');
        return false;
    }
    var notfi_set = $("div#notifications__list");
    var newnotif  = $("#new__notif");
    $.ajax({
        url: site_url('ajax/connect/fetch_notifications'),
        type: 'GET',
        dataType: 'json'
    })
    .done(function(data) {
        if (data.status == 200) {
            newnotif.text('');
            notfi_set.html(data.html);
            $("#notification_bell").removeClass('text-danger');
        }

        else if(data.status == 304){
            var cont = $('<div>').append($('<span>',{
                text:data.message
            }));

            notfi_set.html($("<div>",{
                class:'no__notifications',
                html: cont.prepend('<svg xmlns="http://www.w3.org/2000/svg" class="confetti" viewBox="0 0 1081 601"><path class="st0" d="M711.8 91.5c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7C695.2 84 702.7 91.5 711.8 91.5zM711.8 64.1c5.9 0 10.7 4.8 10.7 10.7s-4.8 10.7-10.7 10.7 -10.7-4.8-10.7-10.7S705.9 64.1 711.8 64.1z"/><path class="st0" d="M74.5 108.3c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7C57.9 100.9 65.3 108.3 74.5 108.3zM74.5 81c5.9 0 10.7 4.8 10.7 10.7 0 5.9-4.8 10.7-10.7 10.7s-10.7-4.8-10.7-10.7S68.6 81 74.5 81z"/><path class="st1" d="M303 146.1c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7C286.4 138.6 293.8 146.1 303 146.1zM303 118.7c5.9 0 10.7 4.8 10.7 10.7 0 5.9-4.8 10.7-10.7 10.7s-10.7-4.8-10.7-10.7C292.3 123.5 297.1 118.7 303 118.7z"/><path class="st2" d="M243.4 347.4c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7S234.2 347.4 243.4 347.4zM243.4 320c5.9 0 10.7 4.8 10.7 10.7 0 5.9-4.8 10.7-10.7 10.7s-10.7-4.8-10.7-10.7S237.5 320 243.4 320z"/><path class="st1" d="M809.8 542.3c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7C793.2 534.8 800.7 542.3 809.8 542.3zM809.8 514.9c5.9 0 10.7 4.8 10.7 10.7s-4.8 10.7-10.7 10.7 -10.7-4.8-10.7-10.7S803.9 514.9 809.8 514.9z"/><path class="st3" d="M1060.5 548.3c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7 -16.7 7.5-16.7 16.7C1043.9 540.8 1051.4 548.3 1060.5 548.3zM1060.5 520.9c5.9 0 10.7 4.8 10.7 10.7s-4.8 10.7-10.7 10.7 -10.7-4.8-10.7-10.7S1054.6 520.9 1060.5 520.9z"/><path class="st3" d="M387.9 25.2l7.4-7.4c1.1-1.1 1.1-3 0-4.1s-3-1.1-4.1 0l-7.4 7.4 -7.4-7.4c-1.1-1.1-3-1.1-4.1 0s-1.1 3 0 4.1l7.4 7.4 -7.4 7.4c-1.1 1.1-1.1 3 0 4.1s3 1.1 4.1 0l7.4-7.4 7.4 7.4c1.1 1.1 3 1.1 4.1 0s1.1-3 0-4.1L387.9 25.2z"/><path class="st3" d="M368.3 498.6l7.4-7.4c1.1-1.1 1.1-3 0-4.1s-3-1.1-4.1 0l-7.4 7.4 -7.4-7.4c-1.1-1.1-3-1.1-4.1 0s-1.1 3 0 4.1l7.4 7.4 -7.4 7.4c-1.1 1.1-1.1 3 0 4.1s3 1.1 4.1 0l7.4-7.4 7.4 7.4c1.1 1.1 3 1.1 4.1 0s1.1-3 0-4.1L368.3 498.6z"/><path class="st3" d="M16.4 270.2l7.4-7.4c1.1-1.1 1.1-3 0-4.1s-3-1.1-4.1 0l-7.4 7.4 -7.4-7.4c-1.1-1.1-3-1.1-4.1 0s-1.1 3 0 4.1l7.4 7.4 -7.4 7.4c-1.1 1.1-1.1 3 0 4.1s3 1.1 4.1 0l7.4-7.4 7.4 7.4c1.1 1.1 3 1.1 4.1 0s1.1-3 0-4.1L16.4 270.2z"/><path class="st2" d="M824.7 351.1l7.4-7.4c1.1-1.1 1.1-3 0-4.1s-3-1.1-4.1 0l-7.4 7.4 -7.4-7.4c-1.1-1.1-3-1.1-4.1 0s-1.1 3 0 4.1l7.4 7.4 -7.4 7.4c-1.1 1.1-1.1 3 0 4.1s3 1.1 4.1 0l7.4-7.4 7.4 7.4c1.1 1.1 3 1.1 4.1 0s1.1-3 0-4.1L824.7 351.1z"/><path class="st1" d="M146.3 573.6H138v-8.3c0-1.3-1-2.3-2.3-2.3s-2.3 1-2.3 2.3v8.3h-8.3c-1.3 0-2.3 1-2.3 2.3s1 2.3 2.3 2.3h8.3v8.3c0 1.3 1 2.3 2.3 2.3s2.3-1 2.3-2.3v-8.3h8.3c1.3 0 2.3-1 2.3-2.3S147.6 573.6 146.3 573.6z"/><path class="st1" d="M1005.6 76.3h-8.3V68c0-1.3-1-2.3-2.3-2.3s-2.3 1-2.3 2.3v8.3h-8.3c-1.3 0-2.3 1-2.3 2.3s1 2.3 2.3 2.3h8.3v8.3c0 1.3 1 2.3 2.3 2.3s2.3-1 2.3-2.3v-8.3h8.3c1.3 0 2.3-1 2.3-2.3S1006.8 76.3 1005.6 76.3z"/><path class="st1" d="M95.5 251.6c-3.5 0-6.3 2.8-6.3 6.3 0 3.5 2.8 6.3 6.3 6.3s6.3-2.8 6.3-6.3S99 251.6 95.5 251.6z"/><path class="st0" d="M1032 281.8c-3.5 0-6.3 2.8-6.3 6.3s2.8 6.3 6.3 6.3 6.3-2.8 6.3-6.3S1035.5 281.8 1032 281.8z"/><path class="st2" d="M741.6 139.3c-3.5 0-6.3 2.8-6.3 6.3s2.8 6.3 6.3 6.3 6.3-2.8 6.3-6.3S745 139.3 741.6 139.3z"/><path class="st3" d="M890.7 43.5c3.3 0 6-2.7 6-6s-2.7-6-6-6 -6 2.7-6 6C884.8 40.8 887.4 43.5 890.7 43.5z"/><path class="st0" d="M164.3 537.6c3.3 0 6-2.7 6-6s-2.7-6-6-6 -6 2.7-6 6C158.4 535 161 537.6 164.3 537.6z"/></svg><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell-off"><path d="M8.56 2.9A7 7 0 0 1 19 9v4m-2 4H2a3 3 0 0 0 3-3V9a7 7 0 0 1 .78-3.22M13.73 21a2 2 0 0 1-3.46 0"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>')
            }));
        }
    });
}

function update_data(){
    var app_page = $("body").data('page');
    var features = {
        'notifications':1,
        'new_messages':1, 
        'chats':0
    };

    if (app_page == 'messages') {
        features['chats'] = 1;
    }

    $.ajax({
        url: site_url('ajax/connect/update_data'),
        type: 'GET',
        dataType: 'json',
        data: features,
    })
    .done(function(data) {
        if (data.notif && $.isNumeric(data.notif)) {
            var newnotif = $("#new__notif");
            newnotif.text(data.notif);
            $("#notification_bell").addClass('text-danger');
        }

        if (data.requests && $.isNumeric(data.requests)) {
            var requests = $("#new__notif_follow");
            requests.text(data.requests);
        }

        if (data.new_messages && $.isNumeric(data.new_messages)) {
            var new_messages = $("#new__messages");
            var new_messages_sec = $("#new__messages_sec");
            new_messages.text(data.new_messages);
            new_messages_sec.text(data.new_messages);
        }
    });

    setTimeout(function(){
        update_data();
    },(1000 * 10))
}
 

function modalImageViewer(identifier) {   
    var image_src = $('img' + identifier).attr('src'); 
    if (typeof image_src == 'undefined') {
        var image_src = $(identifier).data('src'); 
    } 
    var image     = '<img class="img-fluid border-gray" src="'+image_src+'" style="max-height:70vh;" alt="View Image">';
    bootbox.dialog({ 
        title: 'Image Viewer',
        message: '<div class="container text-center" id="bootbox-message"> ' + image + '</div>',
        size: 'large',
        onEscape: true,
        backdrop: true,
        scrollable: true,
        buttons: { 
            close: { label: 'Close', className: 'btn-danger', callback: function(d){  } }
        }
    })
}

/**
 * fetch the image upload modal content and attach to the modal body
 * @param  {String} ){                                 var m_id [id or class of a container to append content]
 * @param  {[type]} error: function(xhr, status, error) {
 *                             error_message(xhr, status, error, m_id);        
 * }      })} [if there is an error on the page run the error function]
 * @return {[type]}        [null]
 */
$('.upload_resize_image').click(function(){

    var m_id        = '.modal-content';
    var endpoint_id = $(this).data('endpoint_id');
    var endpoint    = $(this).data('endpoint');
    var type        = $(this).data('type'); 
    var set_elid    = $(this).prev('div').attr('id'); 
 
    $.ajax({
        async: true,
        type: 'POST',
        url: siteUrl+'ajax/load_modal/upload_image',
        data: {endpoint_id:endpoint_id, endpoint:endpoint, type:type},  
        dataType: 'JSON',
        success: function(resps) { 
            $.getScript(siteUrl+"backend/js/plugins/upload-handler.js?time=1211");
            $(m_id).html(resps.content);
            $('#button_identifiers').val(set_elid);
        },
        error: function(xhr, status, error) {
            error_message(xhr, status, error, m_id);
        }  
    })
})
    
// jQuery UI sortable for the todo list 
$('.todo-list').sortable({
    placeholder          : 'sort-highlight',
    handle               : '.handle',
    forcePlaceholderSize : true,
    zIndex               : 999999,
    update: function (event, ui) {
        var sort_order = $(this).sortable('serialize');
        $.ajax({
            async: true,
            type: 'POST',
            url: siteUrl+'ajax/connect/sortable',
            data: sort_order,  
            dataType: 'JSON',
            success: function(resps) { 
                console.log(resps);
            },
            error: function(xhr, status, error) { 
                error_message(xhr, status, error, '#sort_message');
            }  
        })
    }
}) 

function open_form()
{
    console.log("Opening Form...");
    $('#form').slideToggle();
}

jQuery(document).ready(function($) {

    // Update and show notifications, requests and message status
    delay(function(){
        if (is_logged()) {
            update_data();
        }
    },100); 

    $('#datetimepicker').datetimepicker({
        useCurrent: false, 
        format: 'Y-m-d H:i:s',
        defaultDate: $('#datetimepicker').val()
    });  

    // Tooltips and toggle Initialization
    $('[data-toggle="tooltip"]').tooltip(); 
 
    $('[data-toggle="popover"]').popover();

    $('div .card').each(function(data) {
        var pos        = data+1;
        var _target    = '#' + $(this).attr('id');
        var card_state = $(_target).data('card-state');

        if (card_state && card_state !== 'remove') {
            $(_target).addClass(card_state + 'd-card');

            var button = $(_target).find('button[data-card-widget="'+card_state+'"] i');

            if (card_state == 'collapse') {
                var icon  = 'fa-minus';
                var _icon = 'fa-plus';
            } else if (card_state == 'maximize') {
                var icon  = 'fa-window-maximize';
                var _icon = 'fa-window-minimize';
            }

            button.toggleClass(icon).toggleClass(_icon); 
            
        } else if (card_state && card_state === 'remove') { 
            $(_target).css('display', 'none');
        }
    });

    $('.card-tools button').click(function(event) {
        var state         = $(this).data('card-widget');
        var page          = $('body').data('page');
        var card_selector = $(this).closest('.card');
        var card_id       = card_selector.attr('id');
        if (state === 'maximize') {
            $(this).children('i').toggleClass('fa-window-maximize').toggleClass('fa-window-minimize'); 
        } else if (state === 'remove') {
            $('#view_closed_views').parent('.nav-item').show(); 
        }
        $.post(site_url('ajax/connect/change_card_state'), {page:page, id:card_id, state:state});
    });

    $('#view_closed_views').click(function() {
        var modal_id  = $(this).data('target');
        var page      = $('body').data('page');
        var _trigger  = $(this);
        var _all_good =
            '<div class="text-center">'
                +'<i class="fa fa-check-circle fa-5x text-success"></i>'
            +'</div>';

        var hidden_cards = $('div[style="display: none;"].card');

        $('.modal' + modal_id + ' .modal-body').html(_all_good);
        $('.modal' + modal_id + ' .modal-title').text('Closed Views');

        $(hidden_cards).each(function(data) {
            var _title  = $(this).children('.card-header').children('.card-title').html();
            var card_id = $(this).attr('id');
            $('.modal' + modal_id + ' .modal-body').append(''
                +'<div class="mb-1">'
                    +'<button data-card-id="'+card_id+'" class="btn btn-light border btn-block text-left font-weight-bold reopen-view">'
                        +_title
                    +' </button>'
                +'</div>'
            );
            $('.reopen-view').click(function() { 
                var hidden_cards = $('div[style="display: none;"].card');

                $('div.card#'+$(this).data('card-id')).slideDown();
                $(this).slideDown().remove();
                if (hidden_cards.length <= 1) {
                    $('.modal' + modal_id + ' .modal-body').html(_all_good);
                    _trigger.parent('.nav-item').hide();
                }
                $.post(site_url('ajax/connect/change_card_state'), {page:page, id:card_id, state:'remove'});
            });
        });
    });

    var generate_user_form = $('#generate_user_form'); 
    $("form#generate_user_form :input").keyup(function() {
        if (generate_user_form.data('submit') === 0) { 
            var this_parent = $(this).parent('div').parent('div').parent('div.form-block');
            var _email    = $(this_parent).find('input[name="email[]"]');
            var _emailx   = $(this_parent).find('input[name="emailx[]"]');
            var _password = $(this_parent).find('input[name="password[]"]'); 
            var _phone    = $(this_parent).find('input[name="phone[]"]'); 


            if (typeof _email !== undefined && typeof _password !== undefined && typeof _phone !== undefined) {
                var email    = _email.val()+_emailx.val();
                var password = _password.val(); 
                var phone    = _phone.val(); 
 
                if (!multiple_duplicate_check(this_parent, _email, _phone)) {
                    $.post(site_url('ajax/connect/validate_mutiple_accounts'), {email,password,phone}, function(data) { 
                        if (data.status == 200) { 
                            this_parent.find('.error_message').html('');
                        } else {
                            _email.next('.error_message').html(data.error.email);
                            _phone.next('.error_message').html(data.error.phone);
                            _password.next('.error_message').html(data.error.password);
                            generate_user_form.attr('data-submit', 1);
                        }
                        if ($(".error_message:not(:empty)").length === 0) {
                            $('button.submit').removeClass('disabled').removeAttr('disabled');
                        } else {
                            $('button.submit').addClass('disabled').attr('disabled');
                        }
                    }); 
                } 
            }
        }
    });  

    $('.form-block#1').find('input[name="password[]"]').val($.passGen());
    if (generate_user_form.children('div.form-block').length <= 1) {
        generate_user_form.find('button.remove_last').hide();
    }

    generate_user_form.find('button.more_fields').on("click", clone);
    generate_user_form.find('button.remove_last').on("click", function() {
        _last = $(this).data('last'); 
        $('div.form-block#'+_last).remove(); 
    });
})
 
var cloneIndex = $("div.form-block").length;

function clone() {
        // .on('click', 'button.remove', remove);
    var pre_cloneIndex = cloneIndex;
    var previous_block = $('#generate_user_form').children().last('button').prev('div').prev('div.form-block');
    var emailx         = previous_block.find('input[name="emailx[]"').val();
    cloneIndex++; 
    var new_index = Number(previous_block.attr('id'))+1;

    $('#generate_user_form').children('div.form-block#1').clone(true)
        .attr('id', new_index).insertAfter(previous_block).find('input').val('');

    $('.form-block#'+new_index).find('div.col-sm-6.col-xs-6').attr('class', 'col-sm-5 col-xs-5');
    $('.form-block#'+new_index).find('button.remove_last').attr('data-last', new_index); 
    $('.form-block#'+new_index).find('input[name="password[]"]').val($.passGen()); 
    $('.form-block#'+new_index).find('input[name="emailx[]"]').val(emailx); 
    $('button[data-last="'+new_index+'"].remove_last').show();  
}

function multiple_duplicate_check(parent, email, phone) {
    var state; 
    $('#generate_user_form').children('div.form-block').not(parent).each(function() {
        var __email    = $(this).find('input[name="email[]"]'); 
        var __phone    = $(this).find('input[name="phone[]"]'); 

        if (__email.val() == email.val()) {
            email.next('.error_message').html('<div class="text-danger">Duplicate Email Address</div>');
            state = true;
        } else if (__phone.val() == phone.val()) {
            phone.next('.error_message').html('<div class="text-danger">Duplicate Phone Number</div>');
            state = true;
        } else {
            email.next('.error_message').html('');
            phone.next('.error_message').html('');
            state = false;
        }
    })
    return state;
}

Date.createFromPHP = function(mysql_string)
{ 
    var t, result = null;

    if( typeof mysql_string === 'string' )
    {
        t = mysql_string.split(/[- :]/);

        //when t[3], t[4] and t[5] are missing they defaults to zero
        result = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3] || 0, t[4] || 0, t[5] || 0));          
    }

   return result;   
} 

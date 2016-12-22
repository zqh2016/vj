var show = ''
function shows_form(id, icon)
{
    disp = jQuery(id).css('display');
    if (disp == 'block') {
        jQuery(id).hide('slow');
        if (icon != 'undefined' && jQuery(icon).length > 0) {
            jQuery(icon).removeClass( "dashicons-arrow-up" ).addClass( "dashicons-arrow-down" );
        }
    } else {
        jQuery(id).show('slow');
        if (icon != 'undefined' && jQuery(icon).length > 0) {
            jQuery(icon).removeClass( "dashicons-arrow-down" ).addClass( "dashicons-arrow-up" );
        }
    }                
}

function showModal(id)
{
    jQuery('#' + id).arcticmodal({
        beforeOpen: function(data, el) {
            jQuery('#' + id).css('display','block');
        },
        afterClose: function(data, el) {
            jQuery('#' + id).css('display','none');
        }
    });
}
function sendMessageSupport(button)
{

    var data = {};
    data['action'] = 'cl_support';
    data['message'] = jQuery('#message').html();
    jQuery('#loading-field').css('display', 'table-row');
    jQuery('#message-result').css('display', 'none');
    jQuery('#button-ok').css('display', 'none');
    jQuery('#button-sent').css('display', 'none');
    jQuery('#message-field').css('display', 'none');
    jQuery.ajax({
        url: ajaxurl,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(data_res) {
            jQuery('#message-result').css('display', 'table-row');
            jQuery('#button-ok').css('display', 'table-row');
            jQuery('#button-sent').css('display', 'none');
            jQuery('#loading-field').css('display', 'none');
            jQuery('#message-field').css('display', 'none');
            td = jQuery('#message-result').find('td');
            jQuery(td).css('color', '#624444');
            jQuery(td).html(data_res.msg);
            if (data_res.error) {
                jQuery(td).css('color', 'red');
            } else {
                jQuery(td).css('color', 'green');
            }
        }, 
    }); 
}
function closeSupport()
{
    jQuery.arcticmodal('close');
    jQuery('#message-result').css('display', 'none');
    jQuery('#button-ok').css('display', 'none');
    jQuery('#message-field').css('display', 'table-row');
    jQuery('#button-sent').css('display', 'table-row');
}
function findAndReplaceInArray(array, find, replace)
{
    for(i in array) {
        if (array[i] == find) {
            array[i] = replace;
        }
    }
    return array;
}

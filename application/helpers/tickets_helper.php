<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Render admin tickets table
 * @param string  $name        table name
 * @param boolean $bulk_action include checkboxes on the left side for bulk actions
 */
function AdminTicketsTableStructure($name = '', $bulk_action = false)
{
    $table = '<table class="table customizable-table dt-table-loading ' . ($name == '' ? 'tickets-table' : $name) . ' table-tickets" id="table-tickets" data-last-order-identifier="tickets" data-default-order="' . get_table_last_order('tickets') . '">';
    $table .= '<thead>';
    $table .= '<tr>';

    $table .= '<th class="' . ($bulk_action == true ? '' : 'not_visible') . '">';
    $table .= '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="tickets"><label></label></div>';
    $table .= '</th>';

    $table .= '<th class="toggleable" id="th-number">' . _l('the_number_sign') . '</th>';
    $table .= '<th class="toggleable" id="th-subject">' . _l('ticket_dt_subject') . '</th>';
    $table .= '<th class="toggleable" id="th-tags">' . _l('tags') . '</th>';
    $table .= '<th class="toggleable" id="th-department">' . _l('ticket_dt_department') . '</th>';
    $services_th_attrs = '';
    if (get_option('services') == 0) {
        $services_th_attrs = ' class="not_visible"';
    }
    $table .= '<th' . $services_th_attrs . '>' . _l('ticket_dt_service') . '</th>';
    $table .= '<th class="toggleable" id="th-submitter">' . _l('ticket_dt_submitter') . '</th>';
    $table .= '<th class="toggleable" id="th-customer">' . _l('client') . '</th>';
    $table .= '<th class="toggleable" id="th-status">' . _l('ticket_dt_status') . '</th>';
    $table .= '<th class="toggleable" id="th-priority">' . _l('ticket_dt_priority') . '</th>';
    $table .= '<th class="toggleable" id="th-last-reply">' . _l('ticket_dt_last_reply') . '</th>';
    $table .= '<th class="toggleable ticket_created_column" id="th-created">' . _l('ticket_date_created') . '</th>';

    $custom_fields = get_table_custom_fields('tickets');

    foreach ($custom_fields as $field) {
        $table .= '<th>' . $field['name'] . '</th>';
    }

    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '</table>';

    $table .= '<script id="hidden-columns-table-tickets" type="text/json">';
    $table .= get_staff_meta(get_staff_user_id(), 'hidden-columns-table-tickets');
    $table .= '</script>';

    return $table;
}

function AdminTicketsReportsTableStructure($name = '', $bulk_action = false)
{
    $table = '<table class="table customizable-table dt-table-loading ' . ($name == '' ? 'tickets-reports-table' : $name) . ' table-tickets-reports" id="table-tickets-reports" data-last-order-identifier="tickets-reports" data-default-order="' . get_table_last_order('tickets-reports') . '">';
    $table .= '<thead>';
    $table .= '<tr>';

    $table .= '<th class="' . ($bulk_action == true ? '' : 'not_visible') . '">';
    $table .= '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="tickets-reports"><label></label></div>';
    $table .= '</th>';

    $table .= '<th class="toggleable" id="th-number">' . _l('the_number_sign') . '</th>';
    $table .= '<th class="toggleable" id="th-customer">' . _l('client') . '</th>';
    $table .= '<th class="toggleable" id="th-subject">' . _l('ticket_dt_subject') . '</th>';
    $table .= '<th class="toggleable" id="th-tags">' . _l('tags') . '</th>';
    $table .= '<th class="toggleable" id="th-department">' . _l('ticket_dt_department') . '</th>';
    $services_th_attrs = '';
    if (get_option('services') == 0) {
        $services_th_attrs = ' class="not_visible"';
    }
    $table .= '<th' . $services_th_attrs . '>' . _l('ticket_dt_service') . '</th>';
    $table .= '<th class="toggleable" id="th-submitter">' . _l('ticket_dt_submitter') . '</th>';
    $table .= '<th class="toggleable" id="th-assgin">' . _l('ticket_report_assigned') . '</th>';
    $table .= '<th class="toggleable" id="th-status">' . _l('ticket_dt_status') . '</th>';
    $table .= '<th class="toggleable" id="th-priority">' . _l('ticket_dt_priority') . '</th>';
    $table .= '<th class="toggleable" id="th-last-reply">' . _l('ticket_dt_last_reply') . '</th>';
    $table .= '<th class="toggleable ticket_created_column" id="th-created">' . _l('ticket_date_created') . '</th>';

    $custom_fields = get_table_custom_fields('tickets-reports');

    foreach ($custom_fields as $field) {
        $table .= '<th>' . $field['name'] . '</th>';
    }

    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '</table>';

    $table .= '<script id="hidden-columns-table-tickets-reports" type="text/json">';
    $table .= get_staff_meta(get_staff_user_id(), 'hidden-columns-table-tickets-reports');
    $table .= '</script>';
    // print_r($table); exit();
    return $table;
}

function get_sql_select_ticket_department_name()
{
    return '(SELECT name FROM ' . db_prefix() . 'departments WHERE departmentid=' . db_prefix() . 'tickets.department)';
}

function get_sql_select_ticket_customer_name()
{
    return '(SELECT company FROM ' . db_prefix() . 'clients WHERE userid=' . db_prefix() . 'tickets.userid)';
}
function get_sql_select_ticket_tags()
{
    return '(SELECT GROUP_CONCAT(name SEPARATOR ",") FROM ' . db_prefix() . 'taggables JOIN ' . db_prefix() . 'tags ON ' . db_prefix() . 'taggables.tag_id = ' . db_prefix() . 'tags.id WHERE rel_id = ' . db_prefix() . 'tickets.ticketid and rel_type="ticket" ORDER by tag_order ASC)';
}
function get_sql_select_contact()
{
    return '(SELECT CONCAT(firstname, \' \', lastname) FROM ' . db_prefix() . 'contacts WHERE id=' . db_prefix() . 'tickets.contactid)';
}
function get_sql_select_assignees()
{
    return '(SELECT GROUP_CONCAT(CONCAT(firstname, \' \', lastname) SEPARATOR ",") FROM ' . db_prefix() . 'staff WHERE staffid=' . db_prefix() . 'tickets.assigned)';
}
function get_sql_select_ticket_service_name()
{
    return '(SELECT name FROM ' . db_prefix() . 'services WHERE serviceid=' . db_prefix() . 'tickets.service)';
}
function get_sql_select_ticket_status_name()
{
    return '(SELECT name FROM ' . db_prefix() . 'tickets_status WHERE ticketstatusid=' . db_prefix() . 'tickets.status)';
}
function get_sql_select_ticket_status_color()
{
    return '(SELECT statuscolor FROM ' . db_prefix() . 'tickets_status WHERE ticketstatusid=' . db_prefix() . 'tickets.status)';
}
function get_sql_select_ticket_priority_name()
{
    return '(SELECT name FROM ' . db_prefix() . 'tickets_priorities WHERE priorityid=' . db_prefix() . 'tickets.priority)';
}
/**
 * Function to translate ticket status
 * The app offers ability to translate ticket status no matter if they are stored in database
 * @param  mixed $id
 * @return string
 */
function ticket_status_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('ticket_status_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = & get_instance();
        $CI->db->where('ticketstatusid', $id);
        $status = $CI->db->get(db_prefix() . 'tickets_status')->row();

        return !$status ? '' : $status->name;
    }

    return $line;
}

/**
 * Function to translate ticket priority
 * The apps offers ability to translate ticket priority no matter if they are stored in database
 * @param  mixed $id
 * @return string
 */
function ticket_priority_translate($id)
{
    if ($id == '' || is_null($id)) {
        return '';
    }

    $line = _l('ticket_priority_db_' . $id, '', false);

    if ($line == 'db_translate_not_found') {
        $CI = & get_instance();
        $CI->db->where('priorityid', $id);
        $priority = $CI->db->get(db_prefix() . 'tickets_priorities')->row();

        return !$priority ? '' : $priority->name;
    }

    return $line;
}

/**
 * When ticket will be opened automatically set to open
 * @param integer  $current Current status
 * @param integer  $id      ticketid
 * @param boolean $admin   Admin opened or client opened
 */
function set_ticket_open($current, $id, $admin = true)
{
    if ($current == 1) {
        return;
    }

    $field = ($admin == false ? 'clientread' : 'adminread');

    $CI = & get_instance();
    $CI->db->where('ticketid', $id);

    $CI->db->update(db_prefix() . 'tickets', [
        $field => 1,
    ]);
}

/**
 * Check whether to show ticket submitter on clients area table based on applied settings and contact
 * @since  2.3.2
 * @return boolean
 */
function show_ticket_submitter_on_clients_area_table()
{
    $show_submitter_on_table = true;
    if (!can_logged_in_contact_view_all_tickets()) {
        $show_submitter_on_table = false;
    }

    return hooks()->apply_filters('show_ticket_submitter_on_clients_area_table', $show_submitter_on_table);
}

/**
 * Check whether the logged in contact can view all tickets in customers area
 * @since  2.3.2
 * @return boolean
 */
function can_logged_in_contact_view_all_tickets()
{
    return !(!is_primary_contact() && get_option('only_show_contact_tickets') == 1);
}

/**
 * Get clients area ticket summary statuses data
 * @since  2.3.2
 * @param  array $statuses  current statuses
 * @return array
 */
function get_clients_area_tickets_summary($statuses)
{
    foreach ($statuses as $key => $status) {
        $where = ['userid' => get_client_user_id(), 'status' => $status['ticketstatusid']];
        if (!can_logged_in_contact_view_all_tickets()) {
            $where[db_prefix() . 'tickets.contactid'] = get_contact_user_id();
        }
        $statuses[$key]['total_tickets']   = total_rows(db_prefix() . 'tickets', $where);
        $statuses[$key]['translated_name'] = ticket_status_translate($status['ticketstatusid']);
        $statuses[$key]['url']             = site_url('clients/tickets/' . $status['ticketstatusid']);
    }

    return hooks()->apply_filters('clients_area_tickets_summary', $statuses);
}

/**
 * Check whether contact can change the ticket status (single ticket) in clients area
 * @since  2.3.2
 * @param  mixed $status  the status id, if not passed, will first check from settings
 * @return boolean
 */
function can_change_ticket_status_in_clients_area($status = null)
{
    $option = get_option('allow_customer_to_change_ticket_status');

    if (is_null($status)) {
        return $option == 1;
    }

    /*
    *   For all cases check the option too again, because if the option is set to No, no status changes on any status is allowed
     */
    if ($option == 0) {
        return false;
    }

    $forbidden = hooks()->apply_filters('forbidden_ticket_statuses_to_change_in_clients_area', [3, 4]);

    if (in_array($status, $forbidden)) {
        return false;
    }

    return true;
}

/**
 * For html5 form accepted attributes
 * This function is used for the tickets form attachments
 * @return string
 */
function get_ticket_form_accepted_mimes()
{
    $ticket_allowed_extensions  = get_option('ticket_attachments_file_extensions');
    $_ticket_allowed_extensions = explode(',', $ticket_allowed_extensions);
    $all_form_ext               = $ticket_allowed_extensions;

    if (is_array($_ticket_allowed_extensions)) {
        foreach ($_ticket_allowed_extensions as $ext) {
            $all_form_ext .= ',' . get_mime_by_extension($ext);
        }
    }

    return $all_form_ext;
}

function ticket_message_save_as_predefined_reply_javascript()
{
    if (!is_admin() && get_option('staff_members_save_tickets_predefined_replies') == '0') {
        return false;
    } ?>
    <div class="modal fade" id="savePredefinedReplyFromMessageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('predefined_replies_dt_name'); ?></h4>
      </div>
      <div class="modal-body">
        <?php echo render_input('name', 'predefined_reply_add_edit_name'); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="button" class="btn btn-info" id="saveTicketMessagePredefinedReply"><?php echo _l('submit'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
    <script>
        $(function(){
            var editorMessage = tinymce.get('message');
            if(typeof(editorMessage) != 'undefined') {
                editorMessage.on('change',function(){
                    if(editorMessage.getContent().trim() != '') {
                        if($('#savePredefinedReplyFromMessage').length == 0){
                            $('[app-field-wrapper="message"] [role="menubar"] .mce-container-body:first').append("<a id=\"savePredefinedReplyFromMessage\" data-toggle=\"modal\" data-target=\"#savePredefinedReplyFromMessageModal\" class=\"save_predefined_reply_from_message pointer\" href=\"#\"><?php echo _l('save_message_as_predefined_reply'); ?></a>");
                            }
                            // For open is handled on contact select
                            if($('#single-ticket-form').length > 0) {
                                var contactIDSelect = $('#contactid');
                                if(contactIDSelect.data('no-contact') == undefined && contactIDSelect.data('ticket-emails') == '0') {
                                   show_ticket_no_contact_email_warning($('input[name="userid"]').val(), contactIDSelect.val());
                                } else {
                                   clear_ticket_no_contact_email_warning();
                                }
                            }
                    } else {
                        $('#savePredefinedReplyFromMessage').remove();
                        clear_ticket_no_contact_email_warning();
                    }
                });
            }
            $('body').on('click','#saveTicketMessagePredefinedReply',function(e){
                e.preventDefault();
                var data = {}
                data.message = editorMessage.getContent();
                data.name = $('#savePredefinedReplyFromMessageModal #name').val();
                data.ticket_area = true;
                $.post(admin_url+'tickets/predefined_reply',data).done(function(response){
                    response = JSON.parse(response);
                    if(response.success == true) {
                        var predefined_reply_select = $('#insert_predefined_reply');
                        predefined_reply_select.find('option:first').after('<option value="'+response.id+'">'+data.name+'</option>');
                        predefined_reply_select.selectpicker('refresh');
                    }
                    $('#savePredefinedReplyFromMessageModal').modal('hide');
                });
            });
        });
    </script>
    <?php
}

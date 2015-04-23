<?php
    /**
     * Plugin Name: ICSManager
     * Description: Create and Manage ICS Files
     * Author: Andres Escobar
     * Version: 0.1
     * Requires at least: 3.3
     * Tested up to: 4.1.1
     **/

    $wp_plugin_name = new ics_manager();

    class ics_manager {
        /*
         * class construct
        */
        public function __construct() {

            add_action('init', array($this, 'ICS_type_func'), 0);

            add_shortcode('createmeeting', array($this, 'createmeetingForm'));
            add_shortcode('mymeetings', array($this, 'mymeetingsFiles'));
            add_action('add_meta_boxes', array($this, 'add_custom_meta_box'));
            add_action('save_post', array($this, 'save_custom_meta'));
            add_action('generateICS', array($this, 'generateICS'));
            add_action('clearfieldsicss', array($this, 'clearfieldsics'));

            add_action('wp_enqueue_scripts', array($this, 'load_admin_scripts')); # load admin js
        }

        public function load_admin_scripts() {
            wp_enqueue_script('icsjs', plugin_dir_url(__FILE__) . '/js/icsmanager.js', array('jquery', 'jquery-ui-core'), '20120208', true);
            wp_enqueue_style('icscss', plugins_url('css/icsmanager2.css', __FILE__));
        }

        function ICS_type_func() {

            $labels = array(
                'name' => _x('ICSTypes', 'Post Type General Name', 'text_domain'),
                'singular_name' => _x('ICS Type', 'Post Type Singular Name', 'text_domain'),
                'menu_name' => __('Meetings', 'text_domain'),
                'parent_item_colon' => __('Parent Item:', 'text_domain'),
                'all_items' => __('All Items', 'text_domain'),
                'view_item' => __('View Item', 'text_domain'),
                'add_new_item' => __('Add New Item', 'text_domain'),
                'add_new' => __('Add New', 'text_domain'),
                'edit_item' => __('Edit Item', 'text_domain'),
                'update_item' => __('Update Item', 'text_domain'),
                'search_items' => __('Search Item', 'text_domain'),
                'not_found' => __('Not found', 'text_domain'),
                'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
            );
            $args = array(
                'label' => __('ics_type', 'text_domain'),
                'description' => __('ICS Type Description', 'text_domain'),
                'labels' => $labels,
                'supports' => array('title', 'author', 'custom-fields',),
                'taxonomies' => array('category', 'post_tag'),
                'hierarchical' => false,
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => true,
                'show_in_admin_bar' => true,
                'menu_position' => 5,
                'can_export' => true,
                'has_archive' => true,
                'exclude_from_search' => false,
                'publicly_queryable' => false,
                'capability_type' => 'page',
            );
            register_post_type('ics_type', $args);

        }

        // Add the Meta Box
        function add_custom_meta_box() {
            add_meta_box(
                'meetingFields', // $id
                'Meeting Details', // $title
                array($this, 'show_custom_meta_box'), // $callback
                'ics_type', // $page
                'normal', // $context
                'high'); // $priority
        }

        // The Callback
        function show_custom_meta_box() {
            // Field Array

            $prefix = 'meeting_';
            $custom_meta_fields = array(
                array(
                    'label' => 'Sequence',
                    'desc' => 'Update #',
                    'id' => $prefix . 'sequence',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Type of ICS:',
                    'desc' => 'REQUEST OR CANCEL',
                    'id' => $prefix . 'method',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Status:',
                    'desc' => 'CONFIRMED Or CANCELLED',
                    'id' => $prefix . 'status',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Subject:',
                    'desc' => 'Subject of Email',
                    'id' => $prefix . 'subject',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Message:',
                    'desc' => 'Email Body',
                    'id' => $prefix . 'message',
                    'type' => 'textarea'
                ),
                array(
                    'label' => 'Start Date and Time',
                    'desc' => 'Date of Meeting',
                    'id' => $prefix . 'startDate',
                    'type' => 'text'
                ), array(
                    'label' => 'Start Time',
                    'desc' => 'Date of Meeting',
                    'id' => $prefix . 'startTime',
                    'type' => 'text'
                ),
                array(
                    'label' => 'End Date',
                    'desc' => 'End of Meeting',
                    'id' => $prefix . 'endDate',
                    'type' => 'text'
                ),
                array(
                    'label' => 'End Time',
                    'desc' => 'End of Meeting',
                    'id' => $prefix . 'endTime',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Location',
                    'desc' => 'Location of Meeting',
                    'id' => $prefix . 'location',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Organizer',
                    'desc' => 'Meeting Organizer',
                    'id' => $prefix . 'organizer',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Organizer Email',
                    'desc' => 'Organizer Email',
                    'id' => $prefix . 'organizeremail',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Intervalsel',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalselday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Intervalsel',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalseldayweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervaldayweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'daysweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalselmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bymonthday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bysetposmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bydaysmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalmonthorder',
                    'type' => 'text'
                )


            );

            global $post;
            // Use nonce for verification
            echo '<input type="hidden" name="custom_meta_box_nonce" value="' . wp_create_nonce(basename(__FILE__)) . '" />';

            // Begin the field table and loop
            echo '<table class="form-table">';
            foreach ($custom_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row with
                echo '<tr>
                <th><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
                <td>';
                switch ($field['type']) {
                    // case items will go here
                    // text
                    case 'text':
                        echo '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="30" />
        <br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    // textarea
                    case 'textarea':
                        echo '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" cols="40" rows="4">' . $meta . '</textarea>
        <br /><span class="description">' . $field['desc'] . '</span>';
                        break;
                    // checkbox
                    case 'checkbox':
                        echo '<input type="checkbox" name="' . $field['id'] . '" id="' . $field['id'] . '" ', $meta ? ' checked="checked"' : '', '/>
        <label for="' . $field['id'] . '">' . $field['desc'] . '</label>';
                        break;
                    // select
                    case 'select':
                        echo '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="' . $option['value'] . '">' . $option['label'] . '</option>';
                        }
                        echo '</select><br /><span class="description">' . $field['desc'] . '</span>';
                        break;

                } //end switch
                echo '</td></tr>';
            } // end foreach
            echo '</table>'; // end table
        }

        // Save the Data
        function save_custom_meta($post_id) {
            $prefix = 'meeting_';
            $custom_meta_fields = array(
                array(
                    'label' => 'Sequence',
                    'desc' => 'Update #',
                    'id' => $prefix . 'sequence',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Type of ICS:',
                    'desc' => 'REQUEST OR CANCEL',
                    'id' => $prefix . 'method',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Status:',
                    'desc' => 'CONFIRMED Or CANCELLED',
                    'id' => $prefix . 'status',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Subject:',
                    'desc' => 'Subject of Email',
                    'id' => $prefix . 'subject',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Message:',
                    'desc' => 'Email Body',
                    'id' => $prefix . 'message',
                    'type' => 'textarea'
                ),
                array(
                    'label' => 'Start Date and Time',
                    'desc' => 'Date of Meeting',
                    'id' => $prefix . 'startDate',
                    'type' => 'text'
                ), array(
                    'label' => 'Start Time',
                    'desc' => 'Date of Meeting',
                    'id' => $prefix . 'startTime',
                    'type' => 'text'
                ),
                array(
                    'label' => 'End Date',
                    'desc' => 'End of Meeting',
                    'id' => $prefix . 'endDate',
                    'type' => 'text'
                ),
                array(
                    'label' => 'End Time',
                    'desc' => 'End of Meeting',
                    'id' => $prefix . 'endTime',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Location',
                    'desc' => 'Location of Meeting',
                    'id' => $prefix . 'location',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Organizer',
                    'desc' => 'Meeting Organizer',
                    'id' => $prefix . 'organizer',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Organizer Email',
                    'desc' => 'Organizer Email',
                    'id' => $prefix . 'organizeremail',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Timezone',
                    'desc' => 'Timezone of Meeting',
                    'id' => $prefix . 'timezone',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Recurrence ',
                    'desc' => 'Recurrence',
                    'id' => $prefix . 'recurrence',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Intervalsel',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalselday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Intervalsel',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalseldayweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervaldayweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'daysweek',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalselmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bymonthday',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bysetposmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'bydaysmonth',
                    'type' => 'text'
                ),
                array(
                    'label' => 'Meeting Interval',
                    'desc' => 'Interval Select',
                    'id' => $prefix . 'intervalmonthorder',
                    'type' => 'text'
                )


            );

            // verify nonce
            if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
                return $post_id;
            // check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;
            // check permissions
            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id))
                    return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            // loop through fields and save the data
            foreach ($custom_meta_fields as $field) {
                $old = get_post_meta($post_id, $field['id'], true);
                $new = $_POST[$field['id']];
                if ($new && $new != $old) {
                    update_post_meta($post_id, $field['id'], $new);
                } elseif ('' == $new && $old) {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            } // end foreach
        }

        function createmeetingForm() {
            if (!is_user_logged_in()) {
                wp_login_form(array('redirect' => site_url($_SERVER['REQUEST_URI'])));
            } else {
                $current_user = wp_get_current_user();
                # spits out form
                if (!is_user_logged_in()) {
                    wp_login_form(array('redirect' => site_url($_SERVER['REQUEST_URI'])));
                } else {
                    include('icsform.php');
                }
            }
        }

        function mymeetingsFiles() {
            if (!is_user_logged_in()) {
                wp_login_form(array('redirect' => site_url($_SERVER['REQUEST_URI'])));
            } else {
                if (isset($_GET['genics'])) {
                    $current_post = $_GET['genics'];
                    $summary = get_post_meta($current_post, 'meeting_subject', true);
                    $meeting_startDate = get_post_meta($current_post, 'meeting_startDate', true);
                    $meeting_startTime = get_post_meta($current_post, 'meeting_startTime', true);

                    $filename = $summary . " - " . date('M d Y', strtotime($meeting_startDate)) . " - " . date('HiA', strtotime($meeting_startTime)) . '.ics';

                    //Collect output
                    ob_end_clean();
                    ob_start();

                    // File header
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    header('Content-type: text/calendar');
                    header("Pragma: 0");
                    header("Expires: 0");

                    $description = get_post_meta($current_post, 'meeting_message', true);

                    $meeting_endDate = get_post_meta($current_post, 'meeting_endDate', true);
                    $meeting_endTime = get_post_meta($current_post, 'meeting_endTime', true);

                    $sequence = get_post_meta($current_post, 'meeting_sequence', true);
                    $status = get_post_meta($current_post, 'meeting_status', true);
                    $method = get_post_meta($current_post, 'meeting_method', true);
                    if ($method == 'REQUEST') {
                        $status = 'CONFIRMED';
                    } elseif ($method == 'CANCEL') {
                        $status = 'CANCELLED';
                    }
                    $location = get_post_meta($current_post, 'meeting_location', true);
                    $meeting_organizer = get_post_meta($current_post, 'meeting_organizer', true);
                    $meeting_organizeremail = get_post_meta($current_post, 'meeting_organizeremail', true);
                    $meeting_timezone = get_post_meta($current_post, 'meeting_timezone', true);
                    $meeting_recurrence = get_post_meta($current_post, 'meeting_recurrence', true);
                    $meeeting_range = get_post_meta($current_post, 'meeeting_range', true);

                    if ($meeeting_range != '') {
                        if ($meeeting_range == 'endafter') {
                            $meeeting_occurences = get_post_meta($current_post, 'meeeting_occurences', true);
                            $count = ';COUNT=' . $meeeting_occurences . '';
                        }
                        if ($meeeting_range == 'endby') {
                            $meeeting_occurences = get_post_meta($current_post, 'meeeting_occurences', true);
                            $count = ';COUNT=' . $meeeting_occurences . '';
                        }
//                    $meeeting_endby = get_post_meta($current_post, 'meeeting_endby', true);
                    }



                    if ($meeting_recurrence == 'DAILY') {
                        $meeting_intervalselday = get_post_meta($current_post, 'meeting_intervalselday', true);

                        if ($meeting_intervalselday == 'numdays') {
                            $meeting_intervalsel = get_post_meta($current_post, 'meeting_intervalday', true);
                            $interval = ';INTERVAL=' . $meeting_intervalsel . '';
                        } elseif ($meeting_intervalselday == 'BYDAY=MO,TU,WE,TH,FR') {
                            $meeting_recurrence = 'WEEKLY';
                            $interval = ';BYDAY=MO,TU,WE,TH,FR';
                        }
                    }
                    if ($meeting_recurrence == 'WEEKLY') {
                        $meeting_intervalweek = get_post_meta($current_post, 'meeting_intervalweek', true);
                        $meeting_daysweek = get_post_meta($current_post, 'meeting_daysweek', true);

                        $interval = ';INTERVAL=' . $meeting_intervalweek . '';
                        $days = ";BYDAY=" . implode(",", $meeting_daysweek) . ";WKST=SU";
                    }
                    if ($meeting_recurrence == 'MONTHLY') {

                        $meeting_intervalselmonth = get_post_meta($current_post, 'meeting_intervalselmonth', true);
                        if ($meeting_intervalselmonth == 'monthbyday') {
                            $meeting_intervalmonth = get_post_meta($current_post, 'meeting_intervalmonth', true);
                            $meeting_bymonthday = get_post_meta($current_post, 'meeting_bymonthday', true);

                            $interval = ';INTERVAL=' . $meeting_bymonthday . '';
                            $days = ';BYMONTHDAY=' . $meeting_intervalmonth . '';
                        } elseif ($meeting_intervalselmonth == 'monthorder') {
                            $meeting_bysetposmonth = get_post_meta($current_post, 'meeting_bysetposmonth', true);
                            $meeting_bydaysmonth = get_post_meta($current_post, 'meeting_bydaysmonth', true);
                            $meeting_intervalmonthorder = get_post_meta($current_post, 'meeting_intervalmonthorder', true);

                            $interval = ';INTERVAL=' . $meeting_intervalmonthorder . '';
                            $days = ';BYDAY=' . $meeting_bydaysmonth . '';
                            $order = ';BYSETPOS=' . $meeting_bysetposmonth . '';
                        }
                    }
                    if ($meeting_recurrence == 'YEARLY') {
                        $meeting_intervalyear = get_post_meta($current_post, 'meeting_intervalyear', true);
                        $interval = ';INTERVAL=' . $meeting_intervalyear . '';

                        $meeting_intervalselyear = get_post_meta($current_post, 'meeting_intervalselyear', true);

                        if ($meeting_intervalselyear == 'mdyear') {
                            $meeting_monthdyear = get_post_meta($current_post, 'meeting_monthdyear', true);
                            $meeting_mdayyear = get_post_meta($current_post, 'meeting_mdayyear', true);

                            $days = ';BYMONTHDAY=' . $meeting_mdayyear . '';
                            $order = ';BYMONTH=' . $meeting_monthdyear . '';

                        } elseif ($meeting_intervalselyear == 'odmyear') {
                            $meeting_orderdmyear = get_post_meta($current_post, 'meeting_orderdmyear', true);
                            $meeting_odaymyear = get_post_meta($current_post, 'meeting_odaymyear', true);
                            $meeting_odmonthyear = get_post_meta($current_post, 'meeting_odmonthyear', true);

                            $days = ';BYDAY=' . $meeting_odaymyear . '';
                            $order = ';BYMONTH=' . $meeting_odmonthyear . '';
                            $position = ';BYSETPOS=' . $meeting_orderdmyear . '';

                        }

                    }


                    date_default_timezone_set($meeting_timezone);

                    $ics_contents = "BEGIN:VCALENDAR\n";
                    $ics_contents .= "METHOD:" . $method . "\n";
                    $ics_contents .= "PRODID:Data::ICal 0.13\n";
                    $ics_contents .= "VERSION:2.0\n";
                    $ics_contents .= "BEGIN:VEVENT\n";
                    $ics_contents .= "ATTENDEE;PARTSTAT=ACCEPTED;RSVP=TRUE:MAILTO:\n";
                    $ics_contents .= "DESCRIPTION:" . $description = str_replace("\r\n", "\\n", $description) . "\n";
                    $ics_contents .= "DTSTAMP:" . date('Ymd\THis\Z', time()) . "\n";
                    $ics_contents .= "DTSTART:" . gmdate('Ymd\THis\Z', strtotime($meeting_startDate . $meeting_startTime)) . "\n";
                    $ics_contents .= "DTEND:" . gmdate('Ymd\THis\Z', strtotime($meeting_endDate . $meeting_endTime)) . "\n";
                    $ics_contents .= "LOCATION:" . $location . "\n";
                    $ics_contents .= "ORGANIZER;CN=" . $meeting_organizer . ":MAILTO:" . $meeting_organizeremail . "\n";
                    $ics_contents .= "STATUS:" . $status . "\n";
                    $ics_contents .= "SUMMARY:" . $summary . "\n";
                    $ics_contents .= "SEQUENCE:" . $sequence . "\n";
                    $ics_contents .= "UID:" . $current_post . $meeting_organizeremail . "\n";
                    if ($meeting_recurrence != 'None') {
                        $ics_contents .= "RRULE:FREQ=" . $meeting_recurrence . "" . $interval . "" . $count . "" . $days . "" . $order . "" . $position . "\n";
                    }
                    $ics_contents .= "END:VEVENT\n";
                    $ics_contents .= "END:VCALENDAR\n";
                    echo $ics_contents;
                    exit();
                }

                global $current_user;
                global $post;

                $author_query = array('posts_per_page' => '-1', 'author' => $current_user->ID, 'post_type' => 'ics_type');
                $author_posts = new WP_Query($author_query); ?>

                <?php if ($author_posts->have_posts()) : while ($author_posts->have_posts()) : $author_posts->the_post(); ?>
                    <table id="icslist">
                        <thead>
                        <tr>
                            <th>Meeting Subject</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="<?php echo $post->ID; ?>">
                            <td class="tbl_cat"><?php echo get_post_meta($post->ID, 'meeting_subject', true); ?></td>
                            <td class="tbl_cat"><?php echo get_post_meta($post->ID, 'meeting_startDate', true); ?></td>
                            <td class="tbl_cat"><?php echo get_post_meta($post->ID, 'meeting_endDate', true); ?></td>
                        </tr>
                        <tr>
                            <?php $edit_post = add_query_arg('post', get_the_ID(), '/create-meeting/'); ?>
                            <?php $generateics = add_query_arg('genics', get_the_ID(), '/my-meetings/'); ?>

                            <td>
                                <a href="<?php echo $edit_post; ?>">Manage Meeting</a>
                            </td>
                            <td>
                                <a href="<?php echo $generateics; ?>">Download</a>
                            </td>

                            <td>

                            </td>


                        </tr>
                        </tbody>
                    </table>
                <?php endwhile;
                    wp_reset_postdata();
                else: ?>
                    <table id="icslist">
                        <thead>
                        <tr>
                            <th>Meeting Subject</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td></td>
                            <td>
                                You have not created any meetings
                            </td>

                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php
            }
        }

        function clearfieldsics() {
            echo 'heello!';

        }

    }
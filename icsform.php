<?php

if (isset($_GET['post'])) {

    $successedit = false;
    $current_post = $_GET['post'];

    $title = get_post_meta($current_post, 'meeting_subject', true);
    $meeting_message = get_post_meta($current_post, 'meeting_message', true);
    $meeting_startDate = get_post_meta($current_post, 'meeting_startDate', true);
    $meeting_startTime = get_post_meta($current_post, 'meeting_startTime', true);
    $meeting_endDate = get_post_meta($current_post, 'meeting_endDate', true);
    $meeting_endTime = get_post_meta($current_post, 'meeting_endTime', true);

    $sequence = intval(get_post_meta($current_post, 'meeting_sequence', true));
    $sequence = ++$sequence;
    $method = get_post_meta($current_post, 'meeting_method', true);
    $meeting_organizer = get_post_meta($current_post, 'meeting_organizer', true);
    $meeting_organizeremail = get_post_meta($current_post, 'meeting_organizeremail', true);
    $meeting_timezone = get_post_meta($current_post, 'meeting_timezone', true);
    $location = get_post_meta($current_post, 'meeting_location', true);
    $meeting_recurrence = get_post_meta($current_post, 'meeting_recurrence', true);

    $meeting_intervalselday = get_post_meta($current_post, 'meeting_intervalselday', true);
    $meeting_intervalday = get_post_meta($current_post, 'meeting_intervalday', true);


    $meeting_intervalweek = get_post_meta($current_post, 'meeting_intervalweek', true);
    $meeting_daysweek = get_post_meta($current_post, 'meeting_daysweek', true);

    $meeting_intervalselmonth = get_post_meta($current_post, 'meeting_intervalselmonth', true);
    $meeting_intervalmonth = get_post_meta($current_post, 'meeting_intervalmonth', true);
    $meeting_bymonthday = get_post_meta($current_post, 'meeting_bymonthday', true);
    $meeting_bysetposmonth = get_post_meta($current_post, 'meeting_bysetposmonth', true);
    $meeting_bydaysmonth = get_post_meta($current_post, 'meeting_bydaysmonth', true);
    $meeting_intervalmonthorder = get_post_meta($current_post, 'meeting_intervalmonthorder', true);

    $meeting_intervalyear = get_post_meta($current_post, 'meeting_intervalyear', true);
    $meeting_intervalselyear = get_post_meta($current_post, 'meeting_intervalselyear', true);
    $meeting_monthdyear = get_post_meta($current_post, 'meeting_monthdyear', true);
    $meeting_mdayyear = get_post_meta($current_post, 'meeting_mdayyear', true);

    $meeting_orderdmyear = get_post_meta($current_post, 'meeting_orderdmyear', true);
    $meeting_odaymyear = get_post_meta($current_post, 'meeting_odaymyear', true);
    $meeting_odmonthyear = get_post_meta($current_post, 'meeting_odmonthyear', true);

    $meeeting_range = get_post_meta($current_post, 'meeeting_range', true);
    $meeeting_occurences = get_post_meta($current_post, 'meeeting_occurences', true);
    $meeeting_endby = get_post_meta($current_post, 'meeeting_endby', true);


    if ($method == 'REQUEST') {
        $status = 'CONFIRMED';
    } elseif ($method == 'CANCEL') {
        $status = 'CANCELLED';
    }

    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == "icsForm") {

        if ($_POST['meeting_method'] != '') {
            $method = $_POST['meeting_method'];
            if ($method == 'REQUEST') {
                $status = 'CONFIRMED';
            } elseif ($method == 'CANCEL') {
                $status = 'CANCELLED';
            }
        } else {
            $methodvld = true;
            $hasError = true;
        }
        if ($_POST['meeting_timezone'] != '') {
            $meeting_timezone = $_POST['meeting_timezone'];
        } else {
            $meeting_timezonevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_subject'] != '') {
            $title = $_POST['meeting_subject'];
        } else {
            $titlevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_message'] != '') {
            $meeting_message = $_POST['meeting_message'];
        } else {
            $meeting_messagevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_startDate'] != '') {
            $meeting_startDate = $_POST['meeting_startDate'];
        } else {
            $meeting_startDatevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_startTime'] != '') {
            $meeting_startTime = $_POST['meeting_startTime'];
        } else {
            $meeting_startTimevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_endDate'] != '') {
            $meeting_endDate = $_POST['meeting_endDate'];
        } else {
            $meeting_endDatevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_endTime'] != '') {
            $meeting_endTime = $_POST['meeting_endTime'];
        } else {

            $meeting_endDatevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_location'] != '') {
            $location = $_POST['meeting_location'];
        } else {
            $locationvld = true;
            $hasError = true;
        }
        if ($_POST['meeting_recurrence'] != '') {

            $meeting_recurrence = $_POST['meeting_recurrence'];

            //daily
            if ($meeting_recurrence == 'DAILY') {
                if ($_POST['meeting_intervalselday'] != '') {

                    $meeting_intervalselday = $_POST['meeting_intervalselday'];

                    if ($_POST['meeting_intervalselday'] == 'numdays') {
                        $meeting_intervalday = $_POST['meeting_intervalday'];
                        if ($meeting_intervalday == '') {
                            $meeting_intervaldayvld = true;
                            $hasError = true;
                        }

                    } else {
                        $meeting_intervalday = false;
                    }
                } else {
                    $meeting_intervalseldayvld = true;
                    $hasError = true;
                }
            } else {
                $meeting_intervalselday = false;
                $meeting_intervalday = false;
            }

            //weekly
            if ($meeting_recurrence == 'WEEKLY') {

                $meeting_intervalweek = $_POST['meeting_intervalweek'];
                $meeting_daysweek = $_POST['meeting_daysweek'];

                if ($meeting_intervalweek == '') {
                    $meeting_intervalweekvld = true;
                    $hasError = true;
                }
                if ($meeting_daysweek == '') {
                    $meeting_daysweekvld = true;
                    $hasError = true;
                }

            } else {

                $meeting_intervalweek = false;
                $meeting_daysweek = false;
            }
            //monthly
            if ($meeting_recurrence == 'MONTHLY') {
                $meeting_intervalselmonth = $_POST['meeting_intervalselmonth'];

                if ($meeting_intervalselmonth == '') {
                    $meeting_intervalselmonthvld = true;
                    $hasError = true;
                }

                if ($_POST['meeting_intervalselmonth'] == 'monthbyday') {
                    $meeting_intervalmonth = $_POST['meeting_intervalmonth'];
                    $meeting_bymonthday = $_POST['meeting_bymonthday'];
                    $meeting_bysetposmonth = false;
                    $meeting_bydaysmonth = false;
                    $meeting_intervalmonthorder = false;

                    if ($meeting_intervalmonth == '') {
                        $meeting_intervalmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_bymonthday == '') {
                        $meeting_bymonthdayvld = true;
                        $hasError = true;
                    }
                }

                if ($_POST['meeting_intervalselmonth'] == 'monthorder') {

                    $meeting_bysetposmonth = $_POST['meeting_bysetposmonth'];
                    $meeting_bydaysmonth = $_POST['meeting_bydaysmonth'];
                    $meeting_intervalmonthorder = $_POST['meeting_intervalmonthorder'];
                    $meeting_intervalmonth = false;
                    $meeting_bymonthday = false;

                    if ($meeting_bysetposmonth == '') {
                        $meeting_bysetposmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_bydaysmonth == '') {
                        $meeting_bydaysmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_intervalmonthorder == '') {
                        $meeting_intervalmonthordervld = true;
                        $hasError = true;
                    }
                }

            } else {
                $meeting_intervalselmonth = false;
                $meeting_intervalmonth = false;
                $meeting_bymonthday = false;
                $meeting_bysetposmonth = false;
                $meeting_bydaysmonth = false;
                $meeting_intervalmonthorder = false;
            }
            //yearly
            if ($meeting_recurrence == 'YEARLY') {
                $meeting_intervalyear = $_POST['meeting_intervalyear'];
                if ($meeting_intervalyear == '') {
                    $meeting_intervalyearvld = true;
                    $hasError = true;
                }

                $meeting_intervalselyear = $_POST['meeting_intervalselyear'];

                if ($meeting_intervalselyear == '') {
                    $meeting_intervalselyearvld = true;
                    $hasError = true;
                }

                if ($_POST['meeting_intervalselyear'] == 'mdyear') {
                    $meeting_monthdyear = $_POST['meeting_monthdyear'];
                    $meeting_mdayyear = $_POST['meeting_mdayyear'];

                    $meeting_orderdmyear = false;
                    $meeting_odaymyear = false;
                    $meeting_odmonthyear = false;

                    if ($meeting_monthdyear == '') {
                        $meeting_monthdyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_mdayyear == '') {
                        $meeting_mdayyearvld = true;
                        $hasError = true;
                    }

                }

                if ($_POST['meeting_intervalselyear'] == 'odmyear') {
                    $meeting_orderdmyear = $_POST['meeting_orderdmyear'];
                    $meeting_odaymyear = $_POST['meeting_odaymyear'];
                    $meeting_odmonthyear = $_POST['meeting_odmonthyear'];

                    $meeting_monthdyear = false;
                    $meeting_mdayyear = false;

                    if ($meeting_orderdmyear == '') {
                        $meeting_orderdmyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_odaymyear == '') {
                        $meeting_odaymyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_odmonthyear == '') {
                        $meeting_odmonthyearvld = true;
                        $hasError = true;
                    }
                }
            } else {
                $meeting_intervalyear = false;
                $meeting_intervalselyear = false;
                $meeting_monthdyear = false;
                $meeting_mdayyear = false;
                $meeting_orderdmyear = false;
                $meeting_odaymyear = false;
                $meeting_odmonthyear = false;
                $meeeting_range = false;
            }

            if ($meeting_recurrence != 'None') {
                //range
                $meeeting_range = $_POST['meeeting_range'];

                if ($meeeting_range == '') {
                    $meeeting_rangevld = true;
                    $hasError = true;
                }

                if ($meeeting_range == 'endafter') {
                    $meeeting_occurences = $_POST['meeeting_occurences'];

                    if ($meeeting_occurences == '') {
                        $meeeting_occurencesvld = true;
                        $hasError = true;
                    }
                } else {
                    $meeeting_occurences = false;
                }



                if ($meeeting_range == 'endby') {
                    $meeeting_endby = $_POST['meeeting_endby'];
                } else {
                    $meeeting_endby = false;
                }
            } else {
                if($meeting_recurrence == ''){
                    $meeeting_range = false;
                }
                $meeeting_range = false;
            }


        } else {

            $meeting_recurrencevld = true;
            $hasError = true;
        }


        $post_information = array(
            'ID' => $current_post,
            'post_title' => $title,
            'post-type' => 'ics_type',
            'post_status' => 'publish'

        );

        $post_id = wp_update_post($post_information);

        if (!isset($hasError)) {
            if ($post_id) {
                update_post_meta($post_id, 'meeting_subject', $title);
                update_post_meta($post_id, 'meeting_message', $meeting_message);
                update_post_meta($post_id, 'meeting_startDate', $meeting_startDate);
                update_post_meta($post_id, 'meeting_startTime', $meeting_startTime);
                update_post_meta($post_id, 'meeting_endDate', $meeting_endDate);
                update_post_meta($post_id, 'meeting_endTime', $meeting_endTime);

                update_post_meta($post_id, 'meeting_sequence', $sequence);
                update_post_meta($post_id, 'meeting_method', $method);
                update_post_meta($post_id, 'meeting_status', $status);
                update_post_meta($post_id, 'meeting_location', $location);
                update_post_meta($post_id, 'meeting_organizer', $meeting_organizer);
                update_post_meta($post_id, 'meeting_organizeremail', $meeting_organizeremail);
                update_post_meta($post_id, 'meeting_timezone', $meeting_timezone);
                update_post_meta($post_id, 'meeting_recurrence', $meeting_recurrence);

                update_post_meta($post_id, 'meeting_intervalselday', $meeting_intervalselday);
                update_post_meta($post_id, 'meeting_intervalday', $meeting_intervalday);


                update_post_meta($post_id, 'meeting_intervalweek', $meeting_intervalweek);
                update_post_meta($post_id, 'meeting_daysweek', $meeting_daysweek);

                update_post_meta($post_id, 'meeting_intervalselmonth', $meeting_intervalselmonth);
                update_post_meta($post_id, 'meeting_intervalmonth', $meeting_intervalmonth);
                update_post_meta($post_id, 'meeting_bymonthday', $meeting_bymonthday);
                update_post_meta($post_id, 'meeting_bysetposmonth', $meeting_bysetposmonth);
                update_post_meta($post_id, 'meeting_bydaysmonth', $meeting_bydaysmonth);
                update_post_meta($post_id, 'meeting_intervalmonthorder', $meeting_intervalmonthorder);

                update_post_meta($post_id, 'meeting_intervalyear', $meeting_intervalyear);
                update_post_meta($post_id, 'meeting_intervalselyear', $meeting_intervalselyear);
                update_post_meta($post_id, 'meeting_monthdyear', $meeting_monthdyear);
                update_post_meta($post_id, 'meeting_mdayyear', $meeting_mdayyear);
                update_post_meta($post_id, 'meeting_orderdmyear', $meeting_orderdmyear);
                update_post_meta($post_id, 'meeting_odaymyear', $meeting_odaymyear);
                update_post_meta($post_id, 'meeting_odmonthyear', $meeting_odmonthyear);

                update_post_meta($post_id, 'meeeting_range', $meeeting_range);
                update_post_meta($post_id, 'meeeting_occurences', $meeeting_occurences);
                update_post_meta($post_id, 'meeeting_endby', $meeeting_endby);


                $sequence = false;
                $method = false;
                $status = false;
                $title = false;
                $meeting_message = false;
                $meeting_startDate = false;
                $meeting_startTime = false;
                $meeting_endDate = false;
                $meeting_endTime = false;
                $location = false;
                $meeting_organizer = false;
                $meeting_organizeremail = false;
                $meeting_timezone = false;
                $meeting_recurrence = false;

                $meeting_intervalselday = false;
                $meeting_intervalday = false;


                $meeting_intervalweek = false;
                $meeting_daysweek = false;

                $meeting_intervalselmonth = false;
                $meeting_intervalmonth = false;
                $meeting_bymonthday = false;
                $meeting_bysetposmonth = false;
                $meeting_bydaysmonth = false;
                $meeting_intervalmonthorder = false;

                $meeting_intervalyear = false;
                $meeting_intervalselyear = false;
                $meeting_monthdyear = false;
                $meeting_mdayyear = false;
                $meeting_orderdmyear = false;
                $meeting_odaymyear = false;
                $meeting_odmonthyear = false;

                $meeeting_range = false;
                $meeeting_occurences = false;
                $meeeting_endby = false;

                $successedit = true;
            }
        }
    }

} else {
    $sequence = 0;
    $meeting_organizer = $current_user->first_name . ' ' . $current_user->last_name;
    $meeting_organizeremail = $current_user->user_email;

    if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) && $_POST['action'] == "icsForm") {

        $success = false;

        // Do some minor form validation to make sure there is content
        if ($_POST['meeting_method'] != '') {
            $method = $_POST['meeting_method'];
            if ($method == 'REQUEST') {
                $status = 'CONFIRMED';
            } elseif ($method == 'CANCEL') {
                $status = 'CANCELLED';
            }
        } else {
            $methodvld = true;
            $hasError = true;
        }
        if ($_POST['meeting_timezone'] != '') {
            $meeting_timezone = $_POST['meeting_timezone'];
        } else {
            $meeting_timezonevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_subject'] != '') {
            $title = $_POST['meeting_subject'];
        } else {
            $titlevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_message'] != '') {
            $meeting_message = $_POST['meeting_message'];
        } else {
            $meeting_messagevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_startDate'] != '') {
            $meeting_startDate = $_POST['meeting_startDate'];
        } else {
            $meeting_startDatevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_startTime'] != '') {
            $meeting_startTime = $_POST['meeting_startTime'];
        } else {
            $meeting_startTimevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_endDate'] != '') {
            $meeting_endDate = $_POST['meeting_endDate'];
        } else {
            $meeting_endDatevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_endTime'] != '') {
            $meeting_endTime = $_POST['meeting_endTime'];
        } else {
            $meeting_endTimevld = true;
            $hasError = true;
        }
        if ($_POST['meeting_location'] != '') {
            $location = $_POST['meeting_location'];
        } else {
            $locationvld = true;
            $hasError = true;
        }

        if ($_POST['meeting_recurrence'] != '') {

            $meeting_recurrence = $_POST['meeting_recurrence'];

            //daily
            if ($meeting_recurrence == 'DAILY') {
                if ($_POST['meeting_intervalselday'] != '') {

                    $meeting_intervalselday = $_POST['meeting_intervalselday'];

                    if ($_POST['meeting_intervalselday'] == 'numdays') {
                        $meeting_intervalday = $_POST['meeting_intervalday'];
                        if ($meeting_intervalday == '') {
                            $meeting_intervaldayvld = true;
                            $hasError = true;
                        }

                    } else {
                        $meeting_intervalday = false;
                    }
                } else {
                    $meeting_intervalseldayvld = true;
                    $hasError = true;
                }
            } else {
                $meeting_intervalselday = false;
                $meeting_intervalday = false;
            }

            //weekly
            if ($meeting_recurrence == 'WEEKLY') {

                $meeting_intervalweek = $_POST['meeting_intervalweek'];
                $meeting_daysweek = $_POST['meeting_daysweek'];

                if ($meeting_intervalweek == '') {
                    $meeting_intervalweekvld = true;
                    $hasError = true;
                }
                if ($meeting_daysweek == '') {
                    $meeting_daysweekvld = true;
                    $hasError = true;
                }

            } else {

                $meeting_intervalweek = false;
                $meeting_daysweek = false;
            }
            //monthly
            if ($meeting_recurrence == 'MONTHLY') {
                $meeting_intervalselmonth = $_POST['meeting_intervalselmonth'];

                if ($meeting_intervalselmonth == '') {
                    $meeting_intervalselmonthvld = true;
                    $hasError = true;
                }

                if ($_POST['meeting_intervalselmonth'] == 'monthbyday') {
                    $meeting_intervalmonth = $_POST['meeting_intervalmonth'];
                    $meeting_bymonthday = $_POST['meeting_bymonthday'];
                    $meeting_bysetposmonth = false;
                    $meeting_bydaysmonth = false;
                    $meeting_intervalmonthorder = false;

                    if ($meeting_intervalmonth == '') {
                        $meeting_intervalmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_bymonthday == '') {
                        $meeting_bymonthdayvld = true;
                        $hasError = true;
                    }
                }

                if ($_POST['meeting_intervalselmonth'] == 'monthorder') {

                    $meeting_bysetposmonth = $_POST['meeting_bysetposmonth'];
                    $meeting_bydaysmonth = $_POST['meeting_bydaysmonth'];
                    $meeting_intervalmonthorder = $_POST['meeting_intervalmonthorder'];
                    $meeting_intervalmonth = false;
                    $meeting_bymonthday = false;

                    if ($meeting_bysetposmonth == '') {
                        $meeting_bysetposmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_bydaysmonth == '') {
                        $meeting_bydaysmonthvld = true;
                        $hasError = true;
                    }
                    if ($meeting_intervalmonthorder == '') {
                        $meeting_intervalmonthordervld = true;
                        $hasError = true;
                    }
                }

            } else {
                $meeting_intervalselmonth = false;
                $meeting_intervalmonth = false;
                $meeting_bymonthday = false;
                $meeting_bysetposmonth = false;
                $meeting_bydaysmonth = false;
                $meeting_intervalmonthorder = false;
            }
            //yearly
            if ($meeting_recurrence == 'YEARLY') {
                $meeting_intervalyear = $_POST['meeting_intervalyear'];
                if ($meeting_intervalyear == '') {
                    $meeting_intervalyearvld = true;
                    $hasError = true;
                }

                $meeting_intervalselyear = $_POST['meeting_intervalselyear'];

                if ($meeting_intervalselyear == '') {
                    $meeting_intervalselyearvld = true;
                    $hasError = true;
                }

                if ($_POST['meeting_intervalselyear'] == 'mdyear') {
                    $meeting_monthdyear = $_POST['meeting_monthdyear'];
                    $meeting_mdayyear = $_POST['meeting_mdayyear'];

                    $meeting_orderdmyear = false;
                    $meeting_odaymyear = false;
                    $meeting_odmonthyear = false;

                    if ($meeting_monthdyear == '') {
                        $meeting_monthdyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_mdayyear == '') {
                        $meeting_mdayyearvld = true;
                        $hasError = true;
                    }

                }

                if ($_POST['meeting_intervalselyear'] == 'odmyear') {
                    $meeting_orderdmyear = $_POST['meeting_orderdmyear'];
                    $meeting_odaymyear = $_POST['meeting_odaymyear'];
                    $meeting_odmonthyear = $_POST['meeting_odmonthyear'];

                    $meeting_monthdyear = false;
                    $meeting_mdayyear = false;

                    if ($meeting_orderdmyear == '') {
                        $meeting_orderdmyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_odaymyear == '') {
                        $meeting_odaymyearvld = true;
                        $hasError = true;
                    }
                    if ($meeting_odmonthyear == '') {
                        $meeting_odmonthyearvld = true;
                        $hasError = true;
                    }
                }
            } else {
                $meeting_intervalyear = false;
                $meeting_intervalselyear = false;
                $meeting_monthdyear = false;
                $meeting_mdayyear = false;
                $meeting_orderdmyear = false;
                $meeting_odaymyear = false;
                $meeting_odmonthyear = false;
                $meeeting_range = false;
            }

            if ($meeting_recurrence != 'None') {
                //range
                $meeeting_range = $_POST['meeeting_range'];

                if ($meeeting_range == '') {
                    $meeeting_rangevld = true;
                    $hasError = true;
                }

                if ($meeeting_range == 'endafter') {
                    $meeeting_occurences = $_POST['meeeting_occurences'];

                    if ($meeeting_occurences == '') {
                        $meeeting_occurencesvld = true;
                        $hasError = true;
                    }
                } else {
                    $meeeting_occurences = false;
                }



                if ($meeeting_range == 'endby') {
                    $meeeting_endby = $_POST['meeeting_endby'];
                } else {
                    $meeeting_endby = false;
                }
            } else {
                if($meeting_recurrence == ''){
                    $meeeting_range = false;
                }
                $meeeting_range = false;
            }


        } else {

            $meeting_recurrencevld = true;
            $hasError = true;
        }


        $meeting_organizer = $_POST['meeting_organizer'];
        $meeting_organizeremail = $_POST['meeting_organizeremail'];


        // ADD THE FORM INPUT TO $new_post ARRAY
        $icsForm = array(
            'post_title' => $title,
            'post_status' => 'publish', // Choose: publish, preview, future, draft, etc.
            'post_type' => 'ics_type', //'post',page' or use a custom post type if you want to
            'meeting_subject' => $title

        );

        //SAVE THE POST
        if (!isset($hasError)) {
            $pid = wp_insert_post($icsForm);

            //ADD OUR CUSTOM FIELDS

            add_post_meta($pid, 'meeting_subject', $title, true);
            add_post_meta($pid, 'meeting_message', $meeting_message, true);

            add_post_meta($pid, 'meeting_startDate', $meeting_startDate, true);
            add_post_meta($pid, 'meeting_startTime', $meeting_startTime, true);
            add_post_meta($pid, 'meeting_endDate', $meeting_endDate, true);
            add_post_meta($pid, 'meeting_endTime', $meeting_endTime, true);

            add_post_meta($pid, 'meeting_sequence', $sequence, true);
            add_post_meta($pid, 'meeting_method', $method, true);
            add_post_meta($pid, 'meeting_status', $status, true);
            add_post_meta($pid, 'meeting_location', $location, true);
            add_post_meta($pid, 'meeting_organizer', $meeting_organizer, true);
            add_post_meta($pid, 'meeting_organizeremail', $meeting_organizeremail, true);
            add_post_meta($pid, 'meeting_timezone', $meeting_timezone, true);
            add_post_meta($pid, 'meeting_recurrence', $meeting_recurrence, true);

            add_post_meta($pid, 'meeting_intervalselday', $meeting_intervalselday, true);
            add_post_meta($pid, 'meeting_intervalday', $meeting_intervalday, true);


            add_post_meta($pid, 'meeting_intervalweek', $meeting_intervalweek, true);
            add_post_meta($pid, 'meeting_daysweek', $meeting_daysweek, true);

            add_post_meta($pid, 'meeting_intervalselmonth', $meeting_intervalselmonth, true);
            add_post_meta($pid, 'meeting_intervalmonth', $meeting_intervalmonth, true);
            add_post_meta($pid, 'meeting_bymonthday', $meeting_bymonthday, true);
            add_post_meta($pid, 'meeting_bysetposmonth', $meeting_bysetposmonth, true);
            add_post_meta($pid, 'meeting_bydaysmonth', $meeting_bydaysmonth, true);
            add_post_meta($pid, 'meeting_intervalmonthorder', $meeting_intervalmonthorder, true);

            add_post_meta($pid, 'meeting_intervalyear', $meeting_intervalyear, true);
            add_post_meta($pid, 'meeting_intervalselyear', $meeting_intervalselyear, true);
            add_post_meta($pid, 'meeting_monthdyear', $meeting_monthdyear, true);

            add_post_meta($pid, 'meeting_mdayyear', $meeting_mdayyear, true);
            add_post_meta($pid, 'meeting_orderdmyear', $meeting_orderdmyear, true);
            add_post_meta($pid, 'meeting_odaymyear', $meeting_odaymyear, true);
            add_post_meta($pid, 'meeting_odmonthyear', $meeting_odmonthyear, true);


            add_post_meta($pid, 'meeeting_range', $meeeting_range, true);
            add_post_meta($pid, 'meeeting_occurences', $meeeting_occurences, true);
            add_post_meta($pid, 'meeeting_endby', $meeeting_endby, true);


            $sequence = false;
            $method = false;
            $status = false;
            $title = false;
            $meeting_message = false;
            $meeting_startDate = false;
            $meeting_startTime = false;
            $meeting_endDate = false;
            $meeting_endTime = false;
            $location = false;
            $meeting_organizer = false;
            $meeting_organizeremail = false;
            $meeting_timezone = false;
            $meeting_recurrence = false;

            $meeting_intervalselday = false;
            $meeting_intervalday = false;


            $meeting_intervalweek = false;
            $meeting_daysweek = false;

            $meeting_intervalselmonth = false;
            $meeting_intervalmonth = false;
            $meeting_bymonthday = false;
            $meeting_bysetposmonth = false;
            $meeting_bydaysmonth = false;
            $meeting_intervalmonthorder = false;

            $meeting_intervalyear = false;
            $meeting_intervalselyear = false;
            $meeting_monthdyear = false;
            $meeting_mdayyear = false;
            $meeting_orderdmyear = false;
            $meeting_odaymyear = false;
            $meeting_odmonthyear = false;

            $meeeting_range = false;
            $meeeting_occurences = false;
            $meeeting_endby = false;

            $success = true;
        }
    }
    do_action('wp_insert_post', 'wp_insert_post');
}

?>

<form id="icsForm" name="icsForm" method="post" action="" enctype="multipart/form-data">
    <?php if ($success == true) { ?>
        <h2> The meeting has been added</h2>

    <?php } ?>
    <?php if ($successedit == true) { ?>
        <h2> The meeting has been updated</h2>

    <?php } ?>
    <legend class="icstextinput">Hello <?php echo $current_user->display_name; ?>,</legend>

    <fieldset>
        <input name="meeting_sequence" type="hidden" value="<?php echo $sequence; ?>"/>

        <div class="joined">
            <label for="meeting_organizer">Organizer:</label>
            <input class="icstextinput" name="meeting_organizer" type="text" value="<?php echo $meeting_organizer; ?>"/>
        </div>
        <div class="joined">
            <label for="meeting_method">Organizer email:</label>
            <input class="icstextinput" name="meeting_organizeremail" type="text"
                   value="<?php echo $meeting_organizeremail; ?>"/>
        </div>
        <label class="blocked" for="meeting_method">Status of Meeting:</label>
        <label>
            <select class="icstextselect" name="meeting_method">
                <?php if ($sequence == 0) { ?>
                    <option value="REQUEST" <?php if ($method == 'REQUEST') echo 'selected="selected"'; ?>> Invite</option>
                <?php } ?>
                <?php if (!$sequence == 0) { ?>
                    <option value="REQUEST" <?php if ($method == 'REQUEST') echo 'selected="selected"'; ?>> Update</option>
                    <option value="CANCEL" <?php if ($method == 'CANCEL') echo 'selected="selected"'; ?>> Cancel
                        Meeting</option>
                <?php } ?>
            </select>
        </label>
        <?php if ($methodvld == true) { ?>
            <h3>Please enter meeting type</h3>

        <?php } ?>
        <input name="meeting_status" type="hidden" value="<?php echo $status; ?>"/>
        <label for="meeting_location"> <span>Meeting Location:</span>

            <input class="icstextinput" name="meeting_location" type="text" size="30" value="<?php echo $location ?>"/>
        </label>
        <?php if ($locationvld == true) { ?>
            <h3>Please enter meeting location</h3>

        <?php } ?>
        <label for="meeting_subject"> <span>Meeting Subject:</span>

            <input class="icstextinput" name="meeting_subject" type="text" size="30" value="<?php echo $title ?>"/>
        </label>
        <?php if ($titlevld == true) { ?>
            <h3>Please enter meeting subject</h3>

        <?php } ?>
        <label for="meeting_message"> <span>Meeting Message:</span>
            <textarea class="icstextarea" name="meeting_message"><?php echo $meeting_message ?></textarea>
        </label>
        <?php if ($meeting_messagevld == true) { ?>
            <h3>Please enter meeting message</h3>

        <?php } ?>
        <label class="blocked" for="meeting_timezone">Meeting Timezone:</label>
        <select class="icstextselect" name="meeting_timezone">
            <option value="">Select Timezone</option>
            <option value="America/New_York" <?php if ($meeting_timezone == 'America/New_York') echo 'selected="selected"'; ?>>
                New York</option>
            <option value="Europe/London" <?php if ($meeting_timezone == 'Europe/London') echo 'selected="selected"'; ?>>
                London</option>
            <option value="Europe/Brussels" <?php if ($meeting_timezone == 'Europe/Brussels') echo 'selected="selected"'; ?>>
                Brussels</option>
            <option value="Asia/Tokyo" <?php if ($meeting_timezone == 'Asia/Tokyo') echo 'selected="selected"'; ?>>
                Tokyo</option>
            <option value="Asia/Hong_Kong" <?php if ($meeting_timezone == 'Asia/Hong_Kong') echo 'selected="selected"'; ?>> Hong
                Kong</option>
            <option value="Asia/Singapore" <?php if ($meeting_timezone == 'Asia/Singapore') echo 'selected="selected"'; ?>>
                Singapore</option>
        </select>
        <?php if ($meeting_timezonevld == true) { ?>
            <h3>Please enter meeting type</h3>

        <?php } ?>
        <div class="joined">
            <label for="meeting_startDate"> <span>Start Date:</span>

                <input id="from" class="icsdate icstextinput" name="meeting_startDate" type="text" size="30"
                       value="<?php echo $meeting_startDate ?>"/>
            </label>
            <?php if ($meeting_startDatevld == true) { ?>
                <h3>Please enter meeting start Date</h3>

            <?php } ?>
            <label for="meeting_startTime"> <span>Start Time:</span>

                <input class="icstime icstextinput" name="meeting_startTime" type="text" size="30"
                       value="<?php echo $meeting_startTime ?>"/>
            </label>
            <?php if ($meeting_startDatevld == true) { ?>
                <h3>Please enter meeting start Time</h3>

            <?php } ?>
        </div>
        <div class="joined">
            <label for="meeting_endDate"> <span>End Date:</span>

                <input id="to"  class="icsdate icstextinput" name="meeting_endDate" type="text" size="30"
                       value="<?php echo $meeting_endDate ?>"/>
            </label>
            <?php if ($meeting_endDatevld == true) { ?>
                <h3>Please enter meeting end Date </h3>

            <?php } ?>
            <label for="meeting_endTime"> <span>End Time:</span>

                <input class="icstime icstextinput" name="meeting_endTime" type="text" size="30"
                       value="<?php echo $meeting_endTime ?>"/>
            </label>
            <?php if ($meeting_endTimevld == true) { ?>
                <h3>Please enter meeting end time</h3>

            <?php } ?>
        </div>

        <label class="blocked" for="meeting_recurrence">Meeting Recurrence:
            <select id="meeting_recurrence" class="icstextselect" name="meeting_recurrence">
                <option value="">Select Recurrence</option>
                <option value="None" <?php if ($meeting_recurrence == 'None') echo 'selected="selected"'; ?>> None</option>
                <option value="DAILY" <?php if ($meeting_recurrence == 'DAILY') echo 'selected="selected"'; ?>> Daily</option>
                <option value="WEEKLY" <?php if ($meeting_recurrence == 'WEEKLY') echo 'selected="selected"'; ?>>
                    Weekly</option>
                <option value="MONTHLY" <?php if ($meeting_recurrence == 'MONTHLY') echo 'selected="selected"'; ?>>
                    Monthly</option>
                <option value="YEARLY" <?php if ($meeting_recurrence == 'YEARLY') echo 'selected="selected"'; ?>>
                    Yearly</option>
            </select>
        </label>

        <?php if ($meeting_recurrencevld == true) { ?>
            <h3>Please Select Recurrence</h3>
        <?php } ?>


        <div id="freqday" class="blockedfreq">
            <?php if ($meeting_intervaldayvld == true) { ?>
                <h3>Please Enter Interval</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalselday" type="radio"
                   value="numdays" <?php if ($meeting_intervalselday == 'numdays') echo 'checked'; ?> />Every:
            <input class="joined" name="meeting_intervalday" type="text" size="5" value="<?php echo $meeting_intervalday ?>"/>day(s)

            <label class="blockedfreq">
                <input class="joined" name="meeting_intervalselday" type="radio"
                       value="BYDAY=MO,TU,WE,TH,FR" <?php if ($meeting_intervalselday == 'BYDAY=MO,TU,WE,TH,FR') echo 'checked'; ?> />Every
                weekday
            </label>
        </div>

        <?php if ($meeting_intervalseldayvld == true) { ?>
            <h3>Please Select Type Above</h3>
        <?php } ?>

        <div id="freqweek" class="blockedfreq">Recur every:
            <?php if ($meeting_intervalweekvld == true) { ?>
                <h3>Please Enter Interval</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalweek" type="text" size="5" value="<?php echo $meeting_intervalweek ?>"/>week(s)
            on:
            <div class="blocked"></div>
            <?php if ($meeting_daysweekvld == true) { ?>
                <h3>Please Select Day(s) </h3>
            <?php } ?>
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="SU"  <?php if (in_array("SU", $meeting_daysweek)) echo 'checked'; ?>/>Sunday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="MO"  <?php if (in_array("MO", $meeting_daysweek)) echo 'checked'; ?>/>Monday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="TU"  <?php if (in_array("TU", $meeting_daysweek)) echo 'checked'; ?>/>Tuesday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="WE"  <?php if (in_array("WE", $meeting_daysweek)) echo 'checked'; ?>/>Wednesday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="TH"  <?php if (in_array("TH", $meeting_daysweek)) echo 'checked'; ?>/>Thursday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="FR"  <?php if (in_array("FR", $meeting_daysweek)) echo 'checked'; ?>/>Friday
            <input class="joined" name="meeting_daysweek[]" type="checkbox"
                   value="SA"  <?php if (in_array("SA", $meeting_daysweek)) echo 'checked'; ?>/>Saturday
        </div>

        <div id="freqmonth" class="blockedfreq">
            <?php if ($meeting_intervalmonthvld == true) { ?>
                <h3>Please Number of Days </h3>
            <?php } ?>
            <?php if ($meeting_bymonthdayvld == true) { ?>
                <h3>Please Interval of Months</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalselmonth" type="radio"
                   value="monthbyday" <?php if ($meeting_intervalselmonth == 'monthbyday') echo 'checked'; ?>/>Day:
            <input class="joined" name="meeting_intervalmonth" type="text" size="5"
                   value="<?php echo $meeting_intervalmonth ?>"/>of every
            <input class="joined" name="meeting_bymonthday" type="text" size="5" value="<?php echo $meeting_bymonthday ?>"/>month(s)
            <div class="blocked"></div>
            <?php if ($meeting_bysetposmonthvld == true) { ?>
                <h3>Please Select Position </h3>
            <?php } ?>
            <?php if ($meeting_bydaysmonthvld == true) { ?>
                <h3>Please Select Day</h3>
            <?php } ?>

            <?php if ($meeting_intervalmonthordervld == true) { ?>
                <h3>Please Select Interval</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalselmonth" type="radio"
                   value="monthorder" <?php if ($meeting_intervalselmonth == 'monthorder') echo 'checked'; ?>/>The
            <select name="meeting_bysetposmonth">
                <option value="" <?php if ($meeting_bysetposmonth == '') echo 'selected="selected"'; ?>>Select</option>
                <option value="1" <?php if ($meeting_bysetposmonth == '1') echo 'selected="selected"'; ?>>first</option>
                <option value="2" <?php if ($meeting_bysetposmonth == '2') echo 'selected="selected"'; ?>>second</option>
                <option value="3" <?php if ($meeting_bysetposmonth == '3') echo 'selected="selected"'; ?>>third</option>
                <option value="4" <?php if ($meeting_bysetposmonth == '4') echo 'selected="selected"'; ?>>fourth</option>
                <option value="-1" <?php if ($meeting_bysetposmonth == '-1') echo 'selected="selected"'; ?>>last</option>
            </select>
            <select name="meeting_bydaysmonth">
                <option value="" <?php if ($meeting_bydaysmonth == '') echo 'selected="selected"'; ?>>Select</option>
                <option
                    value="SU,MO,TU,WE,TH,FR,SA" <?php if ($meeting_bydaysmonth == 'SU,MO,TU,WE,TH,FR,SA') echo 'selected="selected"'; ?>>
                    day</option>
                <option
                    value="MO,TU,WE,TH,FR" <?php if ($meeting_bydaysmonth == 'MO,TU,WE,TH,FR') echo 'selected="selected"'; ?>>
                    weekday</option>
                <option value="SU,SA" <?php if ($meeting_bydaysmonth == 'SU,SA') echo 'selected="selected"'; ?>>weekend
                    day</option>
                <option value="SU" <?php if ($meeting_bydaysmonth == 'SU') echo 'selected="selected"'; ?>>Sunday</option>
                <option value="MO" <?php if ($meeting_bydaysmonth == 'MO') echo 'selected="selected"'; ?>>Monday</option>
                <option value="TU" <?php if ($meeting_bydaysmonth == 'TU') echo 'selected="selected"'; ?>>Tuesday</option>
                <option value="WE" <?php if ($meeting_bydaysmonth == 'WE') echo 'selected="selected"'; ?>>Wednesday</option>
                <option value="TH" <?php if ($meeting_bydaysmonth == 'TH') echo 'selected="selected"'; ?>>Thursday</option>
                <option value="FR" <?php if ($meeting_bydaysmonth == 'FR') echo 'selected="selected"'; ?>>Friday</option>
                <option value="SA" <?php if ($meeting_bydaysmonth == 'SA') echo 'selected="selected"'; ?>>Saturday</option>
            </select>of every
            <input class="joined" name="meeting_intervalmonthorder" type="text" size="5"
                   value="<?php echo $meeting_intervalmonthorder ?>"/>month(s)
        </div>

        <?php if ($meeting_intervalselmonthvld == true) { ?>
            <h3>Please Select Type Above</h3>
        <?php } ?>


        <div id="freqyear" class="blockedfreq">Recur Every:
            <?php if ($meeting_intervalyearvld == true) { ?>
                <h3>Please Select Interval</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalyear" type="text" size="5" value="<?php echo $meeting_intervalyear ?>"/>year(s)

            <div class="blocked"></div>
            <?php if ($meeting_monthdyearvld == true) { ?>
                <h3>Please Select Month</h3>
            <?php } ?>
            <?php if ($meeting_mdayyearvld == true) { ?>
                <h3>Please Select Day</h3>
            <?php } ?>
            <input class="joined" name="meeting_intervalselyear" type="radio"
                   value="mdyear" <?php if ($meeting_intervalselyear == 'mdyear') echo 'checked'; ?>/>On:

            <select name="meeting_monthdyear">
                <option value="" <?php if ($meeting_monthdyear == '') echo 'selected="selected"'; ?>>Select</option>
                <option value="1" <?php if ($meeting_monthdyear == '1') echo 'selected="selected"'; ?>>January</option>
                <option value="2" <?php if ($meeting_monthdyear == '2') echo 'selected="selected"'; ?>>February</option>
                <option value="3" <?php if ($meeting_monthdyear == '3') echo 'selected="selected"'; ?>>March</option>
                <option value="4" <?php if ($meeting_monthdyear == '4') echo 'selected="selected"'; ?>>April</option>
                <option value="5" <?php if ($meeting_monthdyear == '5') echo 'selected="selected"'; ?>>May</option>
                <option value="6" <?php if ($meeting_monthdyear == '6') echo 'selected="selected"'; ?>>June</option>
                <option value="7" <?php if ($meeting_monthdyear == '7') echo 'selected="selected"'; ?>>July</option>
                <option value="8" <?php if ($meeting_monthdyear == '8') echo 'selected="selected"'; ?>>August</option>
                <option value="9" <?php if ($meeting_monthdyear == '9') echo 'selected="selected"'; ?>>September</option>
                <option value="10" <?php if ($meeting_monthdyear == '10') echo 'selected="selected"'; ?>>October</option>
                <option value="11" <?php if ($meeting_monthdyear == '11') echo 'selected="selected"'; ?>>November</option>
                <option value="12" <?php if ($meeting_monthdyear == '12') echo 'selected="selected"'; ?>>December</option>
            </select>

            <input class="joined" name="meeting_mdayyear" type="text" size="5" value="<?php echo $meeting_mdayyear ?>"/>
            <?php if ($meeting_orderdmyearvld == true) { ?>
                <h3>Please Select Order</h3>
            <?php } ?>
            <?php if ($meeting_odaymyearvld == true) { ?>
                <h3>Please Select Day</h3>
            <?php } ?>
            <?php if ($meeting_odmonthyearvld == true) { ?>
                <h3>Please Select month</h3>
            <?php } ?>
            <div class="blocked"></div>

            <input class="joined" name="meeting_intervalselyear" type="radio"
                   value="odmyear" <?php if ($meeting_intervalselyear == 'odmyear') echo 'checked'; ?>/>On the:
            <select name="meeting_orderdmyear">
                <option value="" <?php if ($meeting_orderdmyear == '') echo 'selected="selected"'; ?>>Select</option>
                <option value="1" <?php if ($meeting_orderdmyear == '1') echo 'selected="selected"'; ?>>first</option>
                <option value="2" <?php if ($meeting_orderdmyear == '2') echo 'selected="selected"'; ?>>second</option>
                <option value="3" <?php if ($meeting_orderdmyear == '3') echo 'selected="selected"'; ?>>third</option>
                <option value="4" <?php if ($meeting_orderdmyear == '4') echo 'selected="selected"'; ?>>fourth</option>
                <option value="-1" <?php if ($meeting_orderdmyear == '-1') echo 'selected="selected"'; ?>>last</option>
            </select>
            <select name="meeting_odaymyear">
                <option value="" <?php if ($meeting_odaymyear == '') echo 'selected="selected"'; ?>>Select</option>
                <option
                    value="SU,MO,TU,WE,TH,FR,SA" <?php if ($meeting_odaymyear == 'SU,MO,TU,WE,TH,FR,SA') echo 'selected="selected"'; ?>>
                    day</option>
                <option value="MO,TU,WE,TH,FR" <?php if ($meeting_odaymyear == 'MO,TU,WE,TH,FR') echo 'selected="selected"'; ?>>
                    weekday</option>
                <option value="SU,SA" <?php if ($meeting_odaymyear == 'SU,SA') echo 'selected="selected"'; ?>>weekend
                    day</option>
                <option value="SU" <?php if ($meeting_odaymyear == 'SU') echo 'selected="selected"'; ?>>Sunday</option>
                <option value="MO" <?php if ($meeting_odaymyear == 'MO') echo 'selected="selected"'; ?>>Monday</option>
                <option value="TU" <?php if ($meeting_odaymyear == 'TU') echo 'selected="selected"'; ?>>Tuesday</option>
                <option value="WE" <?php if ($meeting_odaymyear == 'WE') echo 'selected="selected"'; ?>>Wednesday</option>
                <option value="TH" <?php if ($meeting_odaymyear == 'TH') echo 'selected="selected"'; ?>>Thursday</option>
                <option value="FR" <?php if ($meeting_odaymyear == 'FR') echo 'selected="selected"'; ?>>Friday</option>
                <option value="SA" <?php if ($meeting_odaymyear == 'SA') echo 'selected="selected"'; ?>>Saturday</option>
            </select>of
            <select name="meeting_odmonthyear">
                <option value="" <?php if ($meeting_odmonthyear == '') echo 'selected="selected"'; ?>>Select</option>
                <option value="1" <?php if ($meeting_odmonthyear == '1') echo 'selected="selected"'; ?>>January</option>
                <option value="2" <?php if ($meeting_odmonthyear == '2') echo 'selected="selected"'; ?>>February</option>
                <option value="3" <?php if ($meeting_odmonthyear == '3') echo 'selected="selected"'; ?>>March</option>
                <option value="4" <?php if ($meeting_odmonthyear == '4') echo 'selected="selected"'; ?>>April</option>
                <option value="5" <?php if ($meeting_odmonthyear == '5') echo 'selected="selected"'; ?>>May</option>
                <option value="6" <?php if ($meeting_odmonthyear == '6') echo 'selected="selected"'; ?>>June</option>
                <option value="7" <?php if ($meeting_odmonthyear == '7') echo 'selected="selected"'; ?>>July</option>
                <option value="8" <?php if ($meeting_odmonthyear == '8') echo 'selected="selected"'; ?>>August</option>
                <option value="9" <?php if ($meeting_odmonthyear == '9') echo 'selected="selected"'; ?>>September</option>
                <option value="10" <?php if ($meeting_odmonthyear == '10') echo 'selected="selected"'; ?>>October</option>
                <option value="11" <?php if ($meeting_odmonthyear == '11') echo 'selected="selected"'; ?>>November</option>
                <option value="12" <?php if ($meeting_odmonthyear == '12') echo 'selected="selected"'; ?>>December</option>
            </select>
        </div>
        <?php if ($meeting_intervalselyearvld == true) { ?>
            <h3>Please Select Type Above</h3>
        <?php } ?>

        <div id="range">



            <label class="blockedfreq">Range of Recurrence:</label>
            <?php if ($meeeting_rangevld == true) { ?>
                <h3>Please Select Range</h3>
            <?php } ?>
            <label class="blockedfreq">
                <input class="joined" type="radio" name="meeeting_range"
                       value="noenddate" <?php if ($meeeting_range == 'noenddate') echo 'checked'; ?>>No end date
            </label>
            <?php if ($meeeting_occurencesvld == true) { ?>
                <h3>Please Enter Number of Occurrences</h3>
            <?php } ?>
            <label class="blockedfreq">
                <input class="joined" type="radio" name="meeeting_range"
                       value="endafter" <?php if ($meeeting_range == 'endafter') echo 'checked'; ?>>End after:
                <input size="5" type="text" name="meeeting_occurences" value="<?php echo $meeeting_occurences ?>">occurrences
            </label>

            <!--    <label/* class="blockedfreq">
        <input class="joined" type="radio*/" name="meeeting_range"
               value="endby" <?php /* if ($meeeting_range == 'endby') echo 'checked';*/ ?>>End by:
        <input class="icsdate" type="text" name="meeeting_endby" value="<?php /* echo $meeeting_endby */ ?>">
    </label>
-->
        </div>


        <input class="btn blocked" type="submit" value="Done"/>
        <input type="hidden" name="action" value="icsForm"/>
        <?php wp_nonce_field('icsForm'); ?>
    </fieldset>
</form>
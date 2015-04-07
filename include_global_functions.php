<?php

function odoo_connect($server_url, $dbname, $user, $password) {

    $connexion = new xmlrpc_client($server_url . "/xmlrpc/common");
    $connexion->setSSLVerifyPeer(0);

    $c_msg = new xmlrpcmsg('login');
    $c_msg->addParam(new xmlrpcval($dbname, "string"));
    $c_msg->addParam(new xmlrpcval($user, "string"));
    $c_msg->addParam(new xmlrpcval($password, "string"));
    
    return $connexion->send($c_msg);
}

function get_sponsorships($partner_id, $client, $dbname, $uid, $password, $return = array()) {

    $contracts_filter = array(
        new xmlrpcval(
                array(new xmlrpcval('partner_id', "string"),
            new xmlrpcval('=', "string"),
            new xmlrpcval($partner_id, "int")
                ), "array"
        ),
    );

    $s_contracts = new xmlrpcmsg('execute');
    $s_contracts->addParam(new xmlrpcval($dbname, "string"));
    $s_contracts->addParam(new xmlrpcval($uid, "int"));
    $s_contracts->addParam(new xmlrpcval($password, "string"));
    $s_contracts->addParam(new xmlrpcval("recurring.contract", "string"));
    $s_contracts->addParam(new xmlrpcval("search", "string"));
    $s_contracts->addParam(new xmlrpcval($contracts_filter, "array"));
    $s_contracts->addParam(new xmlrpcval(0, "int"));
    $s_contracts->addParam(new xmlrpcval('None', "int"));
    $s_contracts->addParam(new xmlrpcval("create_date DESC", "string"));
    $contracts = $client->send($s_contracts);

    $contracts_objects = $contracts->value()->scalarval();
    $id_list = array();

    for ($i = 0; $i < count($contracts_objects); $i++) {
        $id_list[] = new xmlrpcval($contracts_objects[$i]->me['int'], 'int');
    }

    $field_list_contracts = array(
        new xmlrpcval("child_id"),
        new xmlrpcval("state"),
        new xmlrpcval("activation_date"),
        new xmlrpcval("months_paid"),
        new xmlrpcval("next_invoice_date"),
    );

    $msg3 = new xmlrpcmsg('execute');
    $msg3->addParam(new xmlrpcval($dbname, "string"));
    $msg3->addParam(new xmlrpcval($uid, "int"));
    $msg3->addParam(new xmlrpcval($password, "string"));
    $msg3->addParam(new xmlrpcval("recurring.contract", "string"));
    $msg3->addParam(new xmlrpcval("read", "string"));
    $msg3->addParam(new xmlrpcval($id_list, "array"));
    $msg3->addParam(new xmlrpcval($field_list_contracts, "array"));

    $resp3 = $client->send($msg3);

    if ($resp3->faultCode()) {
        echo $resp3->faultString();
    }

    return $resp3;
}

function get_sponsorship_by_child_id($child_id, $client, $dbname, $uid, $password, $return = array()) {

    $contracts_filter = array(
        new xmlrpcval(
                array(new xmlrpcval('child_id', "string"),
            new xmlrpcval('=', "string"),
            new xmlrpcval($child_id, "int")
                ), "array"
        ),
    );

//    print_r(php_xmlrpc_decode($contracts_filter));

    $s_contracts = new xmlrpcmsg('execute');
    $s_contracts->addParam(new xmlrpcval($dbname, "string"));
    $s_contracts->addParam(new xmlrpcval($uid, "int"));
    $s_contracts->addParam(new xmlrpcval($password, "string"));
    $s_contracts->addParam(new xmlrpcval("recurring.contract", "string"));
    $s_contracts->addParam(new xmlrpcval("search", "string"));
    $s_contracts->addParam(new xmlrpcval($contracts_filter, "array"));
    $contracts = $client->send($s_contracts);

    $contracts_objects = $contracts->value()->scalarval();
    $id_list = array();

    for ($i = 0; $i < count($contracts_objects); $i++) {
        $id_list[] = new xmlrpcval($contracts_objects[$i]->me['int'], 'int');
    }

    $field_list_contracts = array(
        new xmlrpcval("partner_id"),
        new xmlrpcval("state"),
        new xmlrpcval("activation_date"),
        new xmlrpcval("months_paid"),
        new xmlrpcval("next_invoice_date"),
    );

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($dbname, "string"));
    $msg->addParam(new xmlrpcval($uid, "int"));
    $msg->addParam(new xmlrpcval($password, "string"));
    $msg->addParam(new xmlrpcval("recurring.contract", "string"));
    $msg->addParam(new xmlrpcval("read", "string"));
    $msg->addParam(new xmlrpcval($id_list, "array"));
    $msg->addParam(new xmlrpcval($field_list_contracts, "array"));

    $resp = $client->send($msg);

    if ($resp->faultCode()) {
        echo $resp->faultString();
    }

    return $resp->value()->scalarval();
}

function get_child_details($child_id, $client, $dbname, $uid, $password, $return = array()) {

    $child_filter = array(
        new xmlrpcval(
                array(new xmlrpcval('child_id', "string"),
            new xmlrpcval('=', "string"),
            new xmlrpcval(intval($child_id), "int")
                ), "array"
        ),
    );

    $child_filter = array(
        new xmlrpcval($child_id, 'int')
    );

    $child_fieldlist = array(
        new xmlrpcval("id"),
        new xmlrpcval("firstname"),
        new xmlrpcval("name"),
        new xmlrpcval("code"),
        new xmlrpcval("state"),
        new xmlrpcval("has_desc_fr"),
        new xmlrpcval("desc_fr"),
        new xmlrpcval("has_desc_en"),
        new xmlrpcval("desc_en"),
        new xmlrpcval("has_desc_de"),
        new xmlrpcval("desc_de"),
        new xmlrpcval("has_desc_it"),
        new xmlrpcval("desc_it"),
        new xmlrpcval("message_ids"),
        new xmlrpcval("type"),
        new xmlrpcval("birthdate"),
        new xmlrpcval("gender"),
        new xmlrpcval("case_study_ids"),
    );

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($dbname, "string"));
    $msg->addParam(new xmlrpcval($uid, "int"));
    $msg->addParam(new xmlrpcval($password, "string"));
    $msg->addParam(new xmlrpcval("compassion.child", "string"));
    $msg->addParam(new xmlrpcval("read", "string"));
    $msg->addParam(new xmlrpcval($child_filter, "array"));
    $msg->addParam(new xmlrpcval($child_fieldlist, "array"));

    $resp = $client->send($msg);


    if ($resp->faultCode()) {
        echo $resp->faultString();
    }

    return $resp->value()->scalarval();
}

function display_child_picture($child_id, $client, $dbname, $uid, $password, $image_sel = 'headshot', $width = 80, $height = 80, $custom_style = '') {
    // if picture exits, check for a thumbnail in cache (or create it) and display a cropped image in a div
    
    try {

        if (!file_exists('tmp/thumbnails/' . $child_id . '_' . $image_sel . '_' . $width . 'x' . $height . '.jpg')) {

            $childpictures_filter = array(
                new xmlrpcval(
                        array(new xmlrpcval('child_id', "string"),
                    new xmlrpcval('=', "string"),
                    new xmlrpcval($child_id, "int")
                        ), "array"
                ),
            );

            $msg = new xmlrpcmsg('execute');
            $msg->addParam(new xmlrpcval($dbname, "string"));
            $msg->addParam(new xmlrpcval($uid, "int"));
            $msg->addParam(new xmlrpcval($password, "string"));
            $msg->addParam(new xmlrpcval("compassion.child.pictures", "string"));
            $msg->addParam(new xmlrpcval("search", "string"));
            $msg->addParam(new xmlrpcval($childpictures_filter, "array"));
            $response = $client->send($msg);
            //print_r($response5);

            $result = $response->value();
            $ids5 = $result->scalarval();
            $idpictures_list = array();

            for ($i5 = 0; $i5 < count($ids5); $i5++) {
                $idpictures_list[] = new xmlrpcval($ids5[$i5]->me['int'], 'int');
            }

            $field_list_child = array(
                new xmlrpcval($image_sel, "string"),
                new xmlrpcval('id', "int"),
                new xmlrpcval('date', "string"),
            );

            $msg4 = new xmlrpcmsg('execute');
            $msg4->addParam(new xmlrpcval($dbname, "string"));
            $msg4->addParam(new xmlrpcval($uid, "int"));
            $msg4->addParam(new xmlrpcval($password, "string"));
            $msg4->addParam(new xmlrpcval("compassion.child.pictures", "string"));
            $msg4->addParam(new xmlrpcval("read", "string"));
            $msg4->addParam(new xmlrpcval($idpictures_list, "array"));
            $msg4->addParam(new xmlrpcval($field_list_child, "array"));

            $resp4 = $client->send($msg4);
            //print_r($resp4);
            if ($resp4->faultCode()) {
                echo $resp4->faultString();
            }
            //print_r($resp4);

            $result4 = $resp4->value()->scalarval();
            //print_r($result4);
            for ($i4 = 0; $i4 < count($result4); $i4++) {

                $child_portrait = $result4[$i4]->me['struct'][$image_sel]->me['string'];

                // load an image
                @$i = new Imagick();
                if ($i->readimageblob(base64_decode($child_portrait))) {
                    // get the current image dimensions
                    $geo = $i->getImageGeometry();

                    // crop the image
                    if (($geo['width'] / $width) < ($geo['height'] / $height)) {
                        $i->cropImage($geo['width'], floor($height * $geo['width'] / $width), 0, (($geo['height'] - ($height * $geo['width'] / $width)) / 1.2));
                    } else {
                        $i->cropImage(ceil($width * $geo['height'] / $height), $geo['height'], (($geo['width'] - ($width * $geo['height'] / $height)) / 2), 0);
                    }
                    // thumbnail the image
                    $i->ThumbnailImage($width, $height, true);

                    $i->writeimage('tmp/thumbnails/' . $child_id . '_' . $image_sel . '_' . $width . 'x' . $height . '.jpg');
                }
            }
        }

        if (file_exists('tmp/thumbnails/' . $child_id . '_' . $image_sel . '_' . $width . 'x' . $height . '.jpg')) {
            echo '<div class="crop-square" style="width:' . $width . 'px; height:' . $height . 'px; ' . @$custom_style . '">';
            echo '<img class="img-responsive" src="' . 'tmp/thumbnails/' . $child_id . '_' . $image_sel . '_' . $width . 'x' . $height . '.jpg' . '"/>';
            echo '</div>';
        }
    } catch (Exception $ex) {

//        print_r($ex);
    }
}

function get_invoices($partner_id, $client, $dbname, $uid, $password, $return = array()) {

    $invoices_filter = array(
        new xmlrpcval(
                array(new xmlrpcval('partner_id', "string"),
            new xmlrpcval('=', "string"),
            new xmlrpcval($partner_id, "int")
                ), "array"
        ),
    );

    $s_invoices = new xmlrpcmsg('execute');
    $s_invoices->addParam(new xmlrpcval($dbname, "string"));
    $s_invoices->addParam(new xmlrpcval($uid, "int"));
    $s_invoices->addParam(new xmlrpcval($password, "string"));
    $s_invoices->addParam(new xmlrpcval("account.invoice", "string"));
    $s_invoices->addParam(new xmlrpcval("search", "string"));
    $s_invoices->addParam(new xmlrpcval($invoices_filter, "array"));
    $s_invoices->addParam(new xmlrpcval(0, "int")); /* OFFSET, START FROM */
    $s_invoices->addParam(new xmlrpcval('None', "int")); /* MAX RECORD LIMITS */
    $s_invoices->addParam(new xmlrpcval("date_invoice ASC", "string"));
    $invoices = $client->send($s_invoices);

    $invoices_objects = $invoices->value()->scalarval();
    $id_list = array();

    for ($i = 0; $i < count($invoices_objects); $i++) {
        $id_list[] = new xmlrpcval($invoices_objects[$i]->me['int'], 'int');
    }

    $field_list_invoices = array(
        new xmlrpcval("date_invoice"),
        new xmlrpcval("date_due"),
        new xmlrpcval("amount_total"),
        new xmlrpcval("amount_to_pay"),
        new xmlrpcval("name"),
        new xmlrpcval("number"),
        new xmlrpcval("comment"),
        new xmlrpcval("section_id"),
        new xmlrpcval("move_name"),
        new xmlrpcval("state"),
        new xmlrpcval("type"),
    );

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($dbname, "string"));
    $msg->addParam(new xmlrpcval($uid, "int"));
    $msg->addParam(new xmlrpcval($password, "string"));
    $msg->addParam(new xmlrpcval("account.invoice", "string"));
    $msg->addParam(new xmlrpcval("read", "string"));
    $msg->addParam(new xmlrpcval($id_list, "array"));
    $msg->addParam(new xmlrpcval($field_list_invoices, "array"));

    $resp = $client->send($msg);

    if ($resp->faultCode()) {
        echo $resp->faultString();
    }

    return $resp;
}

function get_invoiceslines($partner_id, $client, $dbname, $uid, $password, $return = array()) {

    $invoiceslines_filter = array(
        new xmlrpcval(
                array(new xmlrpcval('partner_id', "string"),
            new xmlrpcval('=', "string"),
            new xmlrpcval($partner_id, "int")
                ), "array"
        ),
    );

    $s_invoiceslines = new xmlrpcmsg('execute');
    $s_invoiceslines->addParam(new xmlrpcval($dbname, "string"));
    $s_invoiceslines->addParam(new xmlrpcval($uid, "int"));
    $s_invoiceslines->addParam(new xmlrpcval($password, "string"));
    $s_invoiceslines->addParam(new xmlrpcval("account.invoice.line", "string"));
    $s_invoiceslines->addParam(new xmlrpcval("search", "string"));
    $s_invoiceslines->addParam(new xmlrpcval($invoiceslines_filter, "array"));
    $invoiceslines = $client->send($s_invoiceslines);

    $invoiceslines_objects = $invoiceslines->value()->scalarval();
    $id_list = array();

    for ($i = 0; $i < count($invoiceslines_objects); $i++) {
        $id_list[] = new xmlrpcval($invoiceslines_objects[$i]->me['int'], 'int');
    }

    $field_list_invoiceslines = array(
        new xmlrpcval("child_name"),
        new xmlrpcval("account_id"),
        new xmlrpcval("product_id"),
        new xmlrpcval("due_date"),
        new xmlrpcval("last_payment"),
        new xmlrpcval("price_subtotal"),
        new xmlrpcval("name"),
        new xmlrpcval("state"),
    );

    $msg = new xmlrpcmsg('execute');
    $msg->addParam(new xmlrpcval($dbname, "string"));
    $msg->addParam(new xmlrpcval($uid, "int"));
    $msg->addParam(new xmlrpcval($password, "string"));
    $msg->addParam(new xmlrpcval("account.invoice.line", "string"));
    $msg->addParam(new xmlrpcval("read", "string"));
    $msg->addParam(new xmlrpcval($id_list, "array"));
    $msg->addParam(new xmlrpcval($field_list_invoiceslines, "array"));

    $resp = $client->send($msg);

    if ($resp->faultCode()) {
        echo $resp->faultString();
    }

    return $resp;
}

function is_monthspaid_uptodate($months_paid, $return = 'boolean', $language = 'en_EN') {

    // current month without trailing zero
    $current_month = date('n');

    if ($return == 'boolean') {
        if ($months_paid >= $current_month - 1) {
            return true;
        }
        return false;
    } elseif ($return == 'text') {
        if ($months_paid >= $current_month - 1) {
            switch (substr($language, 0, 2)) {
                case 'fr': return 'à jour';
                case 'en': return 'up to date';
                case 'de': return 'okay';
                case 'it': return 'aggiornati';
            }
        }
        switch (substr($language, 0, 2)) {
            case 'fr': return 'paiements en attente : ' . (($current_month - 1 - $months_paid)) . ' mois';
            case 'en': return '<span style="color:#c9302c;">overdue payments : ' . (($current_month - 1 - $months_paid)) . ' month(s)</span>';
            case 'de': return 'offene Zahlungen : ' . (($current_month - 1 - $months_paid)) . ' Monaten';
            case 'it': return 'pagamenti pendenti : ' . (($current_month - 1 - $months_paid)) . ' mese';
        }
    }
    return false;
}

function trim_text($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
        $input = strip_tags($input);
    }

    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
        return $input;
    }

    //find last space within length
    $last_space = strrpos(substr($input, 0, $length), ' ');
    $trimmed_text = substr($input, 0, $last_space);

    //add ellipses (...)
    if ($ellipses) {
        $trimmed_text .= '...';
    }

    return $trimmed_text;
}


function localize_date($date, $location = 'en_EN') {
    // depending on $location, switch $date from Y-m-d to d.m.Y
    if (strlen($date) == 10) {

        $date_array = explode('-', $date);

        if (substr($location, 0, 2) == 'fr' OR substr($location, 0, 2) == 'de' OR substr($location, 0, 2) == 'es') {
            return $date_array[2] . '.' . $date_array[1] . '.' . $date_array[0];
        } elseif (substr($location, 0, 2) == 'en') {
            return $date;
        }
    }

    return $date;
}

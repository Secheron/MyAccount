<?php
@session_start();
require_once 'include_global_functions.php';
include_once 'include_global_localization.php';
error_reporting(E_ALL | E_STRICT);

if(isset($_GET['partner'])) {
    $_SESSION['partner_id'] = filter_input(INPUT_GET, 'partner', FILTER_SANITIZE_SPECIAL_CHARS);
};
    
if($_SESSION['partner_id']!='') {
    $partner_id = $_SESSION['partner_id'];
}

include("includes/xmlrpc_lib/xmlrpc.inc");
include("includes/xmlrpc_lib/xmlrpcs.inc");
$GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';

require_once 'config/odoo_config.php';


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>my.Compassion.ch</title>

        <!-- Bootstrap Core CSS -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- MetisMenu CSS -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <!-- Timeline CSS -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/dist/css/timeline.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/dist/css/sb-admin-2.css" rel="stylesheet">

        <!-- Morris Charts CSS -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/morrisjs/morris.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <link href="style/compassion.css" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="/images/favicons/favicon.ico" type="image/x-ico; charset=binary" />
        <link rel="icon" href="/images/favicons/favicon.ico" type="image/x-ico; charset=binary" />  
    </head>
    <body>

        <div id="wrapper">
            

            <?php
            $c_response = odoo_connect($server_url, $dbname, $user, $password);

            if ($c_response->errno != 0 OR !isset($c_response)) {
                echo '<html style="background-color:black; margin:100px;"><h2 style="color:white; background-color:black;">Sorry, your portal is not available at the moment. <br/>Please try again in a few minutes or contact us : <a href="https://www.compassion.ch" target="_blank">www.compassion.ch</a></h2></html>';
                die();
            } else {

                $uid = $c_response->value()->scalarval();
                $client = new xmlrpc_client($server_url . "/xmlrpc/object");
                $client->setSSLVerifyPeer(0);

                $include_allowed = true;

                $field_list_partner = array(
                    new xmlrpcval("firstname", "string"),
                    new xmlrpcval("lang", "string"),
                );
                $msg = new xmlrpcmsg('execute');
                $msg->addParam(new xmlrpcval($dbname, "string"));
                $msg->addParam(new xmlrpcval($uid, "int"));
                $msg->addParam(new xmlrpcval($password, "string"));
                $msg->addParam(new xmlrpcval("res.partner", "string"));
                $msg->addParam(new xmlrpcval("read", "string"));
                $msg->addParam(new xmlrpcval(array(new xmlrpcval($partner_id, 'int')), "array"));
                $msg->addParam(new xmlrpcval($field_list_partner, "array"));
                $resp = $client->send($msg);

                if ($resp->faultCode()) {
                    echo $resp->faultString();
                }

                $partner = $resp->value()->scalarval();

                //echo $partner[0]->me['struct']['lang']->me['string'];

//                $partner_language = substr($partner[0]->me['struct']['lang']->me['string'], 0, 2);
//                $_SESSION['partner_id'] = $partner[0]->me['struct']['id']->me['int'];
////                $_SESSION['partner_language'] = substr($partner[0]->me['struct']['lang']->me['string'], 0, 2);
//                $_SESSION['partner_language'] = 'en';
//
//                $locale = 'fr_CH';
////        $locale = $partner[0]->me['struct']['lang']->me['string'];
////        echo $locale;
//                putenv("LC_ALL=$locale"); //needed on some systems
//                putenv("LANGUAGE=$locale"); //needed on some systems
//                setlocale(LC_ALL, $locale);
//                setlocale(LC_MESSAGES, $locale);
//                bindtextdomain("messages", "./locale");
//                bind_textdomain_codeset("messages", "UTF-8");
//                textdomain("messages");
                
                $sponsorships_list = get_sponsorships($partner_id, $client, $dbname, $uid, $password);
                $sponsorships = $sponsorships_list->value()->scalarval();

                include_once 'include_global_navigation.php';
                include_once 'include_global_menu.php';
            }
            ?>
            <div id="page-wrapper">
            <?php 
                
                if(isset($_SESSION['partner_id'])) {
                    
                    if(isset($_GET['invoices'])) {
                        
                        include 'include_summary_invoices.php';
                        
                    } elseif(isset($_GET['child'])) {

                        include 'include_detail_child.php';
                        
                    } else {
                        
                        include 'include_summary_dashboard.php';
                        
                    }
                    
                }
            
            ?>
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- jQuery -->
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/jquery/dist/jquery.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/metisMenu/dist/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/dist/js/sb-admin-2.js"></script>

        <!-- DataTables JavaScript -->
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="includes/startbootstrap-sb-admin-2-1.0.5/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

        <script type="text/javascript">
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                    responsive: true,
                    "order": [[ 0, "desc" ]],
            });
        });
        
        $('#sponsor_switch').change(function() {
            window.location = "?partner=" + $(this).val();
        });
        </script>
        
    </body>
</html>
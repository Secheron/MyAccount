<?php
if(!isset($include_allowed)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<?php
$invoices_list = get_invoices($partner_id, $client, $dbname, $uid, $password);
$invoices = $invoices_list->value()->scalarval();

?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php echo _("My invoices"); ?></h1>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Please find your invoices below
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice Nr.</th>
                                <th>Comment</th>
                                <th>Total</th>
                                <th>Date due</th>
                                <th>Amount due</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($invoices as $invoice) {
                                    
                                    $inv = php_xmlrpc_decode($invoice);
                                    
                                    // hide invoices generated in advance
                                    if(strtotime($inv['date_invoice']) > strtotime(date('Y-m-d'))) {
                                        continue;
                                    }
                                    
                                    // hide invoices where sponsor *may* be supplier
                                    if($inv['type']=='in_invoice') {
                                        continue;
                                    }
                                    
//                                    print_r($inv);
//                                    echo '<hr/>';
                                    
                                    if($inv['state']!='cancel') {
                                        if($inv['amount_to_pay']>0) {
//                                            echo 'due:'.strtotime($inv['date_due']).' ';
//                                            echo 'today:'.strtotime(date('Y-m-d')).' ';
                                            if(strtotime($inv['date_due']) < strtotime(date('Y-m-d', strtotime('-30days')))) {
                                                echo '<tr class="danger">';
                                            } else {
                                                echo '<tr class="warning">';
                                            }
                                        } else {
                                            echo '<tr class="success">';
                                        }
                                    } else {
                                        echo '<tr style="color:#cccccc;">';
                                    }
                                        echo '<td>'.localize_date($inv['date_invoice']).'</td>';
                                        echo '<td>'.$inv['number'].'</td>';
                                        echo '<td>'.$inv['comment'].'&nbsp;</td>';
                                        echo '<td style="text-align:right;">'.number_format($inv['amount_total'],2,".","'").'</td>';
//                                        echo '<td>'.$inv['amount_total'].'</td>';
                                        echo '<td>'.localize_date($inv['date_due']).'</td>';
                                        echo '<td style="text-align:right;">';
                                            if($inv['amount_to_pay']!=0) {
                                                echo number_format($inv['amount_to_pay'],2,".","'");
//                                                echo $inv['amount_to_pay'];
                                            } else {
                                                echo '&nbsp;';
                                            }
                                        echo '</td>';
                                        echo '<td>'.$inv['state'].'</td>';
                                    echo '</tr>';
                                    
                                }
                            
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>



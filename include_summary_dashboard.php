<?php
if(!isset($include_allowed)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php printf(_("Hello %s, you're welcome !"), $partner[0]->me['struct']['firstname']->me['string']); ?></h1>
    </div>
    <!-- /.col-lg-12 -->
</div>

<div class="row">

    <div class="col-lg-12"><h3 class=""><?php printf(_("My active sponsorships")); ?></h3></div>
    <?php
    // Active sponsorships for that sponsor

    foreach ($sponsorships as $sponsorship) {

        $sponsorship_array = php_xmlrpc_decode($sponsorship);
        $current_child = $sponsorship_array['child_id'][0];

        if (in_array($sponsorship_array['state'], array('active', 'draft', 'mandate', 'waiting'))) {

            $child_details = get_child_details($sponsorship_array['child_id'][0], $client, $dbname, $uid, $password);
            $child_det = php_xmlrpc_decode($child_details[0]);
            ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-compassion" <?php if($sponsorship_array['state']=='waiting') { echo 'style="opacity:0.5;"'; }; ?>>
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <a href="?child=<?php echo $child_det['id']; ?>">
                                <i class="fa fa-5x"><?php display_child_picture($sponsorship_array['child_id'][0], $client, $dbname, $uid, $password); ?></i>
                                </a>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size:1.5em;"><?php echo $child_det['firstname']; ?></div>
                                <div style="height:70px; overflow:hidden;">
                                    <?php
                                    if ($child_det['has_desc_' . substr($locale,0,2)]) {
                                        echo trim_text($child_det['desc_' . substr($locale,0,2)], 85);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left"><?php printf(_('Beginning of sponsorship')); ?></span>
                            <span class="pull-right"><?php echo localize_date($sponsorship_array['activation_date'], $locale); ?> &nbsp;<i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Status of payments</span>
                                <span class="pull-right"><?php
                                if(in_array($sponsorship_array['state'], array('draft', 'mandate', 'waiting'))) {
                                    echo _('pending');
                                } else {
                                    if (is_monthspaid_uptodate($sponsorship_array['months_paid'])) {
                                        echo is_monthspaid_uptodate($sponsorship_array['months_paid'], 'text', $locale);
                                    } else {
                                        echo is_monthspaid_uptodate($sponsorship_array['months_paid'], 'text', $locale);
                                    }
                                }
                                    ?>
                                &nbsp;<i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    <a href="#">
                        <div class="panel-footer">
                            <a href="?child=<?php echo $child_det['id']; ?>">
                                <span class="pull-left">View more details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            </a>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php
    }
}
?>
</div>

<div class="row">
    <div class="col-lg-12"><h3 class="">Funds I have supported</h3></div>
<?php
// Active sponsorships for that sponsor
$invoiceslines_list = get_invoiceslines($partner_id, $client, $dbname, $uid, $password);
$invoiceslines = $invoiceslines_list->value()->scalarval();

if (is_array($invoiceslines)) {

    $sponsored_funds = array();

    foreach ($invoiceslines as $invoiceline) {

        $invlin = php_xmlrpc_decode($invoiceline);
        // print_r($invlin);


        $product_id = $invlin['product_id'][0];

        // Let's group funds by product_id
        if (!is_array(@$sponsored_funds[$product_id])) {
            $sponsored_funds[$product_id] = array();
        }

        $sponsored_funds[$product_id]['account_id'] = $invlin['account_id'][0];
        $sponsored_funds[$product_id]['account_name'] = $invlin['account_id'][1];
        @$sponsored_funds[$product_id]['amount_paid'] += $invlin['price_subtotal'];
        $sponsored_funds[$product_id]['product_name'] = $invlin['product_id'][1];
    }

    foreach ($sponsored_funds as $sponsored_fund) {
        ?>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-support fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" style="font-size:1.5em;"><?php echo $sponsored_fund['product_name']; ?></div>
                                <div><?php echo 'My donations : ' . number_format($sponsored_fund['amount_paid'], 2, ".", "'") . ' CHF'; ?></div>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">Informations about the project</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        <?php
    }
} else {
    echo 'No fund sponsored until now. ';
}
?>
</div>
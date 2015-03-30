<?php
if(!isset($include_allowed)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
<?php

if(isset($_GET['child'])) {
    $child_id = filter_input(INPUT_GET, 'child', FILTER_SANITIZE_SPECIAL_CHARS);
    
//    echo $child_id;
    $child_details = get_child_details($child_id, $client, $dbname, $uid, $password);
    $child = php_xmlrpc_decode($child_details[0]);

    if($child) {
        
        $sponsorship_details = get_sponsorship_by_child_id($child['id'], $client, $dbname, $uid, $password);
        $sponsorship = php_xmlrpc_decode($sponsorship_details[0]);
        //    print_r($sponsorship);
        
    }
    
//    print_r($child);
    
} else {
    exit();
}


?>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?php printf(_("Informations about %s"), $child['firstname']); ?></h1>
    </div>
    <!-- DESCRIPTION -->
    <div class="col-md-7">
        <div class="panel panel-compassion">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-9 text-left">
                        <div class="huge" style="font-size:1.9em;"><?php echo $child['name']; ?></div>
                        <div style="font-size:1.1em;"><?php echo _('Birthdate&nbsp;: ').' '.localize_date($child['birthdate'], $locale); ?></div>
                        <div style="font-size:1.1em;"><?php echo _('Gender&nbsp;: ').' '.localize_date($child['gender'], $locale); ?></div>
                        <div style="font-size:1.1em;"><?php echo _('Beginning of sponsorship&nbsp;: ').localize_date($sponsorship['activation_date'],$locale); ?></div>
                        <div style="font-size:1.1em;"><?php echo _('State&nbsp;: ').localize_date($sponsorship['state'],$locale); ?></div>
                    </div>
                    <div class="col-xs-3 text-right">
                        <div style="font-size:1.1em;"><?php echo $child['code']; ?></div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">
                    <?php
                    echo '<h4>'._('Description : ').'</h4>';
                    if ($child['has_desc_' . substr($locale,0,2)]) {
                        echo $child['desc_' . substr($locale,0,2)];
                    } elseif ($child['has_desc_fr']) {
                        echo $child['desc_fr'];
                    } else {
                        echo $child['desc_en'];
                    }
                    ?>
                </span>
                <div class="clearfix"></div>
            </div>
        </div>        
    </div>
    <!-- PICTURES -->
    <div class="col-md-5">
        <div class="panel panel-compassion">
            <div class="panel-heading" style="height:50px;">
                <div class="row">
                    <div class="col-xs-9 text-left">
                        <div class="huge" style="font-size:1.5em;">Pictures</div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <span class="pull-left">
                    <?php
                        echo '<a href="#" data-toggle="modal" data-target=".pop-up-1">';
                        display_child_picture($child['id'], $client, $dbname, $uid, $password, 'headshot', 150, 150, 'float:left; margin-right:15px; margin-bottom:15px;');
                        echo '</a>';
                        echo '<a href="#" data-toggle="modal" data-target=".pop-up-2">';
                        display_child_picture($child['id'], $client, $dbname, $uid, $password, 'fullshot', 95, 150, '');
                        echo '</a>';
                    ?>
                </span>
                <!--  Modal content for the mixer image example -->
                <div class="modal fade pop-up-1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="width:430px;">
                        <div class="modal-content">
                            <div class="modal-body">
                                <?php display_child_picture($child['id'], $client, $dbname, $uid, $password, 'headshot', 400, 400); ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal mixer image -->

                <!--  Modal content for the lion image example -->
                <div class="modal fade pop-up-2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-2" aria-hidden="true">
                    <div class="modal-dialog modal-lg" style="width:530px;">
                        <div class="modal-content">
                            <div class="modal-body">
                                <?php display_child_picture($child['id'], $client, $dbname, $uid, $password, 'fullshot', 500, 800); ?>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal mixer image -->
                  
                <div class="clearfix"></div>
            </div>
        </div>        
    </div>
</div>



<?php
if(!isset($include_allowed)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}
?>
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-switch" style="margin:15px;">
                    <div class="form-group">
                        <span class="input-group-btn">
                            <select class="form-control" name="sponsor_switch" id="sponsor_switch" style="background-color: darkred; color:white;">
                                <option value="">Choose a TEST Account</option>
                                <option value="111">- Emanuel</option>
                                <option value="13">- David</option>
                                <option value="5560">- Marc</option>
                                <option value="4729">- Hannah</option>
                                <option value="13410">- Mme Dean</option>
                            </select>
                        </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" placeholder="Search..." disabled>
                        <span class="input-group-btn">
                        <button class="btn btn-default" type="button" disabled>
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                    </div>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="/"><i class="fa fa-dashboard fa-fw"></i> My Compassion</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-sitemap fa-fw"></i> Children<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <?php

                        $children_list = array();
                        
                        foreach($sponsorships as $sponsorship) {

                            $sponsorship_array = php_xmlrpc_decode($sponsorship);
                            $current_child = $sponsorship_array['child_id'][0];

                            if(in_array($sponsorship_array['state'], array('active','draft','mandate','waiting'))) {

                                $child_details = get_child_details($sponsorship_array['child_id'][0], $client, $dbname, $uid, $password);
                                $child_det = php_xmlrpc_decode($child_details[0]);
                                
//                                echo $current_child.' '.$child_det['firstname'].' - ';
                                
                                $children_list["".intval($current_child).""] = $child_det['firstname'];
//                                print_r($current_child);
                            }
                        }
                        
                        asort($children_list);
//                        print_r($children_list);
                        
                        foreach ($children_list as $cur_child_id => $cur_child_firstname) {
                            
//                            print_r($cur_child);

                                echo '<li>';
                                    echo '<a href="#">'.$cur_child_firstname.' <span class="fa arrow"></span></a>';
                                    echo '<ul class="nav nav-third-level">';
                                        echo '<li>';
                                            echo '<a href="?child='.$cur_child_id.'"><i class="fa fa-info-circle fa-fw"></i> Informations</a>';
                                        echo '</li>';
                                        echo '<li>';
                                            echo '<a href="?child='.$cur_child_id.'&read"><i class="fa fa-envelope fa-fw"></i> Read letters</a>';
                                        echo '</li>';
                                        echo '<li>';
                                            echo '<a href="?child='.$cur_child_id.'&write"><i class="fa fa-send fa-fw"></i> Write a letter</a>';
                                        echo '</li>';
                                    echo '</ul>';
                                echo '</li>';
                        }

                            /*
                            $children = get_sponsorships($partner_id, $client, $dbname, $uid, $password);
                            foreach($children as $child) {

                                echo '<li>';
                                    echo '<a href="#">'.$child->me['struct']['child_name']->me['string'].' <span class="fa arrow"></span></a>';
                                    echo '<ul class="nav nav-third-level">';
                                        echo '<li>';
                                            ?><a href="#"><i class="fa fa-info-circle fa-fw"></i> Informations</a><?php
                                        echo '</li>';
                                        echo '<li>';
                                            echo '<a href="#"><i class="fa fa-edit fa-fw"></i> Write a letter</a>';
                                        echo '</li>';
                                    echo '</ul>';
                                echo '</li>';

                                //show_child_picture($child->me['struct']['id']->me['int'], $client, $dbname, $uid, $password);

                            }

                            */

                        ?>

                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-heart fa-fw"></i> Sponsor a child<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="?sponsor&byproject">From same project as<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="#"><i class="fa fa-info-circle fa-fw"></i> Firstname...</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Choose by age<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="?sponsor&byage=1-3"><i class="fa fa-th-list fa-fw"></i> 1-3</a>
                                </li>
                                <li>
                                    <a href="?sponsor&byage=4-6"><i class="fa fa-th-list fa-fw"></i> 4-6</a>
                                </li>
                                <li>
                                    <a href="?sponsor&byage=7-9"><i class="fa fa-th-large fa-fw"></i> 7-9</a>
                                </li>
                                <li>
                                    <a href="?sponsor&byage=10-12"><i class="fa fa-th-large fa-fw"></i> 10-12</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">Choose by country<span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level">
                                <li>
                                    <a href="?sponsor&bycountry=1"><i class="fa fa-th-list fa-fw"></i> .</a>
                                </li>
                                <li>
                                    <a href="?sponsor&bycountry=2"><i class="fa fa-th-list fa-fw"></i> ..</a>
                                </li>
                                <li>
                                    <a href="?sponsor&bycountry=3"><i class="fa fa-th-list fa-fw"></i> ...</a>
                                </li>
                                <li>
                                    <a href="?sponsor&bycountry=4"><i class="fa fa-th-list fa-fw"></i> ....</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="?invoices"><i class="fa fa-table fa-fw"></i> Invoices</a>
                </li>
                <!--
                <li>
                    <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="blank.html">Blank Page</a>
                        </li>
                        <li>
                            <a href="login.html">Login Page</a>
                        </li>
                    </ul>
                </li>
                -->
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
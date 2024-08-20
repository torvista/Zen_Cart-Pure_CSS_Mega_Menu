<?php
//Pure CSS Mega Menu

declare(strict_types=1);
/**
 * CSS Mega Menu layout
 * @version tpl_mega_menu.php 2024-08-20 torvista
 */
?>

<div id="mega-wrapper"><!-- bof mega-wrapper -->

    <ul class="mega-menu menu_red"><!-- bof mega-menu -->

        <li class="quicklinks-li"><a href="<?= zen_href_link(FILENAME_DEFAULT) ?>" class="drop"><?= HEADER_TITLE_QUICK_LINKS ?></a><!-- bof quick links  -->
            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">
                    <ul class="levels">
                        <li><a href="<?= zen_href_link(FILENAME_DEFAULT) ?>"><?= HEADER_TITLE_CATALOG ?></a></li>
                        <li><a href="<?= zen_href_link(FILENAME_PRODUCTS_NEW) ?>"><?= HEADER_TITLE_NEW_PRODUCTS ?></a></li>
                        <li><a href="<?= zen_href_link(FILENAME_FEATURED_PRODUCTS) ?>"><?= TABLE_HEADING_FEATURED_PRODUCTS ?></a></li>
                        <li><a href="<?= zen_href_link(FILENAME_PRODUCTS_ALL) ?>"><?= HEADER_TITLE_ALL_PRODUCTS ?></a></li>
                        <li><a href="<?= zen_href_link(FILENAME_SPECIALS) ?>"><?= HEADER_TITLE_SPECIALS ?></a></li>
                        <li><a href="<?= zen_href_link(FILENAME_ADVANCED_SEARCH) ?>"><?= HEADER_TITLE_SEARCH ?></a></li>
                    </ul>
                </div>
            </div>
        </li><!-- eof quick links -->

        <li class="categories-li"><a href="<?= zen_href_link(FILENAME_DEFAULT) ?>" class="drop"><?= HEADER_TITLE_CATEGORIES ?></a><!-- bof cateories    -->

            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">
                    <div class="levels">
                        <?php

                        // load the UL-generator class and produce the menu list dynamically from there
                        require_once(DIR_WS_CLASSES . 'categories_ul_generator.php');
                        $zen_CategoriesUL = new zen_categories_ul_generator();
                        $menulist = $zen_CategoriesUL->buildTree(true);
                        $menulist = str_replace('"level4"', '"level5"', $menulist);
                        $menulist = str_replace('"level3"', '"level4"', $menulist);
                        $menulist = str_replace('"level2"', '"level3"', $menulist);
                        $menulist = str_replace('"level1"', '"level2"', $menulist);
                        $menulist = str_replace('<li class="submenu">', '<li class="submenu">', $menulist);
                        $menulist = str_replace("</li>\n</ul>\n</li>\n</ul>\n", "</li>\n</ul>\n", $menulist);
                        echo $menulist;
                        ?>
                    </div>
                </div>
            </div>
        </li><!-- eof categories  -->

        <li class="manufacturers-li"><a href="<?= zen_href_link(FILENAME_DEFAULT) ?>" class="drop"><?= HEADER_TITLE_MANUFACTURERS ?></a><!--bof shop by brand   -->
            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">
                    <ul>
                        <?php

                        $show_manufacturers = true;
                        // for large lists of manufacturers uncomment this section
                        /*
                          if (($_GET['main_page']==FILENAME_DEFAULT and ($_GET['cPath'] == '' or $_GET['cPath'] == 0)) or  ($request_type == 'SSL')) {
                            $show_manufacturers= false;
                          } else {
                            $show_manufacturers= true;
                          }
                        */

                        // Set to true to display manufacturers' images in place of names
                        define('DISPLAY_MANUFACTURERS_IMAGES', false);

                        if ($show_manufacturers) {
// only check products if requested - this may slow down the processing of the manufacturers sidebox
                            if (PRODUCTS_MANUFACTURERS_STATUS == '1') {
                                $manufacturer_sidebox_query = 'select distinct m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
                            from ' . TABLE_MANUFACTURERS . ' m
                            left join ' . TABLE_PRODUCTS . ' p on m.manufacturers_id = p.manufacturers_id
                            where m.manufacturers_id = p.manufacturers_id and p.products_status= 1
                            order by manufacturers_name';
                            } else {
                                $manufacturer_sidebox_query = 'select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
                            from ' . TABLE_MANUFACTURERS . ' m
                            order by manufacturers_name';
                            }

                            $manufacturer_sidebox = $db->Execute($manufacturer_sidebox_query);

                            if ($manufacturer_sidebox->RecordCount() > 0) {
                                $number_of_rows = $manufacturer_sidebox->RecordCount() + 1;

// Display a list
                                $manufacturer_sidebox_array = [];
//		kuroi: commented out to avoid starting list with text scrolling list entries such as "reset" and "please select"
//    if (!isset($_GET['manufacturers_id']) || $_GET['manufacturers_id'] == '' ) {
//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_ALL);
//    } else {
//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_MANUFACTURERS);
//    }

                                while (!$manufacturer_sidebox->EOF) {
                                    $manufacturer_sidebox_name = ((strlen($manufacturer_sidebox->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr(
                                            $manufacturer_sidebox->fields['manufacturers_name'],
                                            0,
                                        (int)MAX_DISPLAY_MANUFACTURER_NAME_LEN
                                        ) . '..' : $manufacturer_sidebox->fields['manufacturers_name']);
                                    $manufacturer_sidebox_image = $manufacturer_sidebox->fields['manufacturers_image'];
                                    $manufacturer_sidebox_array[] =
                                        [
                                            'id' => $manufacturer_sidebox->fields['manufacturers_id'],
                                            'text' => DISPLAY_MANUFACTURERS_IMAGES ?
                                                zen_image(DIR_WS_IMAGES . $manufacturer_sidebox_image, $manufacturer_sidebox_name) :
                                                $manufacturer_sidebox_name
                                        ];
                                    $manufacturer_sidebox->MoveNext();
                                }
                            }
                        } // $show_manufacturers
                        for ($i = 0; $i < sizeof($manufacturer_sidebox_array); $i++) {
                            $content = '<li ><a class="hide" href="' . zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer_sidebox_array[$i]['id']) . '">';
                            $content .= $manufacturer_sidebox_array[$i]['text'];
                            $content .= '</a></li>' . "\n";
                            echo $content;
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </li><!-- eof shop by brand    -->

        <li class="aboutus-li"><a href="<?= zen_href_link(FILENAME_ABOUT_US) ?>" class="drop"><?= HEADER_TITLE_ABOUT_US ?></a><!-- bof about us -->

            <div class="dropdown_aboutus">

                <div class="col_aboutus">
                    <h2><?= TITLE_ABOUT_US; ?></h2>
                </div>

                <div class="col_cs">
                    <p class="mega-about"><?= TEXT_ABOUT_US; ?></p>
                    <img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . ABOUT_US_IMAGE ?>" class="imgshadow_light aboutus-image" alt="about us"/>
                </div>
            </div>
        </li><!-- eof about us -->

        <li class="information-li"><a href="<?= zen_href_link(FILENAME_DEFAULT) ?>" class="drop"><?= HEADER_TITLE_INFORMATION ?></a><!-- bof information -->

            <div class="dropdown_info">

                <div class="col_1">
                    <h3><?= TITLE_GENERAL ?></h3>
                    <ul>
                        <li><a href="<?= zen_href_link(FILENAME_ABOUT_US) ?>"><?= BOX_INFORMATION_ABOUT_US ?></a></li>
                        <?php
                        if (DEFINE_SITE_MAP_STATUS <= 1) { ?>
                            <li><a href="<?= zen_href_link(FILENAME_SITE_MAP) ?>"><?= BOX_INFORMATION_SITE_MAP ?></a></li>
                        <?php
                        } ?>
                        <?php
                        if (MODULE_ORDER_TOTAL_GV_STATUS == 'true') { ?>
                            <li><a href="<?= zen_href_link(FILENAME_GV_FAQ, '', 'NONSSL') ?>"><?= BOX_INFORMATION_GV ?></a></li>
                        <?php
                        } ?>
                        <?php
                        if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') { ?>
                            <li><a href="<?= zen_href_link(FILENAME_DISCOUNT_COUPON, '', 'NONSSL') ?>"><?= BOX_INFORMATION_DISCOUNT_COUPONS ?></a></li>
                        <?php
                        } ?>
                        <?php
                        if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK == 'true') { ?>
                            <li><a href="<?= zen_href_link(FILENAME_UNSUBSCRIBE, '', 'NONSSL') ?>"><?= BOX_INFORMATION_UNSUBSCRIBE ?></a></li>
                        <?php
                        } ?>
                    </ul>
                </div>

                <div class="col_1">
                    <h3><?= TITLE_CUSTOMERS ?></h3>
                    <ul>
                        <?php
                        if (!empty($_SESSION['customer_id'])) { ?>
                            <li><a href="<?= zen_href_link(FILENAME_ACCOUNT, '', 'SSL') ?>"><?= HEADER_TITLE_MY_ACCOUNT ?></a></li>
                            <li><a href="<?= zen_href_link(FILENAME_LOGOFF, '', 'SSL') ?>"><?= HEADER_TITLE_LOGOFF ?></a></li>
                            <li><a href="<?= zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') ?>"><?= TITLE_NEWSLETTERS ?></a></li>
                        <?php
                        } else { ?>
                            <li><a href="<?= zen_href_link(FILENAME_LOGIN, '', 'SSL') ?>"><?= HEADER_TITLE_LOGIN ?></a></li>
                            <li><a href="<?= zen_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') ?>"><?= HEADER_TITLE_CREATE_ACCOUNT ?></a></li>
                        <?php
                        } ?>
                        <li><a href="<?= zen_href_link(FILENAME_CONTACT_US, '', 'NONSSL') ?>"><?= BOX_INFORMATION_CONTACT ?></a></li>
                        <?php
                        if (DEFINE_SHIPPINGINFO_STATUS <= 1) { ?>
                            <li><a href="<?= zen_href_link(FILENAME_SHIPPING) ?>"><?= BOX_INFORMATION_SHIPPING ?></a></li>
                        <?php
                        } ?>
                        <?php
                        if (DEFINE_PRIVACY_STATUS <= 1) { ?>
                            <li><a href="<?= zen_href_link(FILENAME_PRIVACY) ?>"><?= BOX_INFORMATION_PRIVACY ?></a></li>
                        <?php
                        } ?>
                        <?php
                        if (DEFINE_CONDITIONS_STATUS <= 1) { ?>
                            <li><a href="<?= zen_href_link(FILENAME_CONDITIONS) ?>"><?= BOX_INFORMATION_CONDITIONS ?></a></li>
                        <?php
                        } ?>
                    </ul>
                </div>

                <div class="col_1">
                    <h3><?= TITLE_EZ_PAGES ?></h3>
                    <ul>
                        <?php
                        require(DIR_WS_MODULES . 'sideboxes/' . $template_dir . '/' . 'ezpages_drop_menu.php'); ?>
                    </ul>
                </div>
            </div>

        </li><!-- eof information -->


        <li class="connect-li"><a href="<?= zen_href_link(FILENAME_ABOUT_US) ?>" class="drop"><?= TITLE_CONNECT ?></a><!-- bof connect -->
            <div class="dropdown_1column">
                <div class="col_1 firstcolumn">
                    <ul class="levels">
                        <li class="hfacebook"><a href="<?= FACEBOOK ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . FACEBOOK_ICON_SM ?>" alt="facebook link" class="h-sm"/><?= FACEBOOK_TEXT ?></a></li>
                        <li class="htwitter"><a href="<?= TWITTER ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . TWITTER_ICON_SM ?>" alt="twitter link" class="h-sm"/><?= TWITTER_TEXT ?></a></li>
                        <li class="hyoutube"><a href="<?= YOUTUBE ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . YOUTUBE_ICON_SM ?>" alt="youtube link" class="h-sm"/><?= YOUTUBE_TEXT ?></a></li>
                        <li class="hpinterest"><a href="<?= PINTEREST ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . PINTEREST_ICON_SM ?>" alt="pinterest link" class="h-sm"/><?= PINTEREST_TEXT ?></a></li>
                        <li class="hgoogle"><a href="<?= GOOGLE ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . GOOGLE_ICON_SM ?>" alt="google link" class="h-sm"/><?= GOOGLE_TEXT ?></a></li>
                        <li class="hblog"><a href="<?= BLOG ?>" target="_blank"><img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . BLOG_ICON_SM ?>" alt="blog link" class="h-sm"/><?= BLOG_TEXT ?></a></li>
                    </ul>
                </div>
            </div>

        </li><!-- eof connect-->

        <li class="customerservice-li"><a href="<?= zen_href_link(FILENAME_SHIPPING) ?>" class="drop"><?= HEADER_TITLE_CUSTOMER_SERVICE ?></a><!-- bof customer service -->

            <div class="dropdown_customer_service align_right">

                <div class="col_cs">
                    <h2><?= TITLE_SHIPPING ?></h2>
                </div>

                <div class="col_cs">
                    <p><?= TEXT_SHIPPING_INFO ?></p>
                </div>

                <div class="col_cs">
                    <h2><?= TITLE_CONFIDENCE ?></h2>
                </div>

                <div class="col_cs">
                    <img src="<?= $template->get_template_dir('', DIR_WS_TEMPLATE, $current_page_base, 'images') . '/' . PAYMENT_ICON ?>" alt="payments we accept" class="payments-icon"/>
                    <p class="mega-confidence"><?= TEXT_CONFIDENCE ?></p>
                </div>

            </div><!-- eof customer service -->

        </li>

    </ul><!-- eof mega-menu -->

</div><!-- eof mega-wrapper -->

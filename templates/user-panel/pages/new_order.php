<?php
global $post;
$opt = get_option( 'wp_afraex_email_opt' );
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
					<?php //_e( "My Exchange List", "wp-afraex" ); ?>
                </h4>
                <!--                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>-->
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">

                    <form method="post" action="<?php echo add_query_arg( array( 'method' => 'new_order' ), get_page_link( $post->ID ) ) ?>" id="new_order">
						<?php wp_nonce_field( 'create_new_order_currency', 'create_new_order_currency' ); ?>
						<?php $user = get_userdata( get_current_user_id() ); ?>

                        <div data-step="1">

                            <div class="row">
                                <div class=" text-center" style="width:20%; float:left; padding-left: 2px; font-size: 10px;color: #000;">
                                    پرداخت می کنید
                                </div>
                                <div style="width:60%; float:left; padding-left: 2px;">
                                    <input type="tel" class="form-control text-center" name="xx" value="0" style="opacity: 0;height: 0px;">
                                </div>
                                <div class="text-center" style="width:20%; float:left; font-size: 10px;color: #000;padding-right: 0.6rem;">
                                    نام ارز
                                </div>
                            </div>

                            <div class="row" style="margin-top:0;">
                                <div style="width:20%; float:left; padding-left: 2px;">
                                    <select name="from_currency" class="select2-icons form-control" id="select2-currency">
										<?php
										$opt              = get_option( 'wp_afraex_options' );
										$rial_currency_id = $opt['rial_currency'];

										$currency_list_post = array();
										$args               = array(
											'post_type'      => 'currency',
											'post_status'    => 'publish',
											'posts_per_page' => '-1',
											'order'          => 'ASC',
											'fields'         => 'ids'
										);
										$query              = new \WP_Query( $args );
										$c_id               = 1;
										foreach ( $query->posts as $ID ) {

											// disable Rial To Another
											if ( $ID == $rial_currency_id ) {
												continue;
											}

											// Get ICON
											$icon = wp_get_attachment_image_src( get_post_meta( $ID, 'currency_icon', true ) );

											?>
                                            <option value="<?php echo $ID; ?>" data-depozit="<?php echo get_post_meta( $ID, 'currency_depozit_tag', true ) ?>" data-modir-wallet="<?php echo get_post_meta( $ID, 'currency_admin_card', true ) ?>" data-barcode-image="<?php echo wp_get_attachment_url( get_post_meta( $ID, 'currency_barcode', true ) ); ?>" data-persian="<?php echo get_post_meta( $ID, 'currency_persian', true ) ?>" data-name="<?php echo get_the_title( $ID ); ?>" data-image="<?php echo $icon[0]; ?>" <?php echo( $c_id < 2 ? 'selected' : '' ); ?>></option>
											<?php
											$c_id ++;
										}
										wp_reset_postdata();
										?>
                                    </select>


                                </div>
                                <div style="width:60%; float:left; padding-left: 2px;">
                                    <input type="tel" class="form-control text-center" name="from_currency_price" data-only-numeric value="0" style="direction: ltr;">
                                </div>
                                <div style="width:20%; float:left;">
                                    <div style="min-width: 40px !important; border: 1px solid #cacfe7;width: 100%;text-align: center;height: 41px;border-radius: 4px; padding: 1px;padding-top: 6px;">
                                        <div style="font-size: 10px;color: #000;" data-from-name></div>
                                        <div style="font-size: 10px;color: #000;" data-from-persian></div>
                                    </div>
                                </div>
                            </div>


                            <div class="row text-center">
                                <div class="change_currency" id="change_currency_prototype" style="margin: 16px auto 7px auto !important;width: 32px;height: 32px; background: #e3e3e3; border-radius: 50%;padding-top: 6px; cursor: pointer;">
                                    <i class="la la-refresh" style="cursor: pointer;"></i></div>
                            </div>


                            <div class="row">
                                <div class="text-center" style="width:20%; float:left; padding-left: 2px; font-size: 10px;color: #000;">
                                    دریافت می کنید
                                </div>
                                <div style="width:60%; float:left; padding-left: 2px;">
                                    <input type="tel" class="form-control text-center" name="xx" value="0" style="opacity: 0;     height: 0px;">
                                </div>
                                <div class="text-center" style="width:20%; float:left; font-size: 10px;color: #000;padding-right: 0.6rem;">
                                    نام ارز
                                </div>
                            </div>


                            <div class="row" style="margin-top:0;">
                                <div style="width:20%; float:left; padding-left: 2px;">
                                    <select name="to_currency" class="select2-icons form-control" id="select2-currency-to">
										<?php

										$rial_currency_id = $opt['rial_currency'];

										$currency_list_post = array();
										$args               = array(
											'post_type'      => 'currency',
											'post_status'    => 'publish',
											'posts_per_page' => '-1',
											'order'          => 'ASC',
											'fields'         => 'ids'
										);
										$query              = new \WP_Query( $args );
										$c_id               = 2;
										foreach ( $query->posts as $ID ) {

											// Get ICON
											$icon = wp_get_attachment_image_src( get_post_meta( $ID, 'currency_icon', true ) );

											?>
                                            <option value="<?php echo $ID; ?>" data-depozit="<?php echo get_post_meta( $ID, 'currency_depozit_tag', true ) ?>" data-modir-wallet="<?php echo get_post_meta( $ID, 'currency_admin_card', true ) ?>" data-barcode-image="<?php echo wp_get_attachment_url( get_post_meta( $ID, 'currency_barcode', true ) ); ?>" data-persian="<?php echo get_post_meta( $ID, 'currency_persian', true ) ?>" data-name="<?php echo get_the_title( $ID ); ?>" data-image="<?php echo $icon[0]; ?>" <?php echo( $c_id == 3 ? 'selected' : '' ); ?>></option>
											<?php
											$c_id ++;
										}
										wp_reset_postdata();
										?>
                                    </select>
                                </div>
                                <div style="width:60%; float:left; padding-left: 2px;">
                                    <input type="tel" class="form-control text-center" name="to_currency_price" value="0" data-only-numeric readonly>
                                </div>
                                <div style="width:20%; float:left;">
                                    <div style="min-width: 40px !important; border: 1px solid #cacfe7;width: 100%;text-align: center;height: 41px;border-radius: 4px; padding: 1px;padding-top: 6px;">
                                        <div style="font-size: 10px;color: #000;" data-to-name></div>
                                        <div style="font-size: 10px;color: #000;" data-to-persian></div>
                                    </div>
                                </div>
                            </div>


                            <div class="row" style="margin-top:30px;">
                                <div class="text-center" style="width: 100%;">
                                    <div id="first_step_loading" style="color: #ca1111; display: none;">

                                        <div style="height: 35px;width: 35px;text-align: center;display: inline-block;vertical-align: top;">
                                            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="29px" height="29px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
<path fill="#ff000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z" transform="rotate(131.38 25 25)">
    <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.6s" repeatCount="indefinite"></animateTransform>
</path>
</svg>
                                        </div>

                                    </div>
                                    <button type="button" data-change_price_form class="btn btn-primary" id="start_change_price" style="width: 100%;font-weight: 300;" disabled>شروع تبادل</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12" id="result_step_1_text" style="font-size: 0.78rem !important;text-align: center;margin-top: 1rem;"></div>
                            </div>


                        </div>
                        <div data-step="2">


                            <p>آدرس مقصد</p>
                            <hr>

                            <div class="select-2-wallet">
								<?php
								global $wpdb;
								$list = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bank_card WHERE `user_id` = " . get_current_user_id() . " ORDER BY ID DESC", ARRAY_A );
								if ( count( $list ) < 1 ) {
									echo '<p>برای انجام تبادلات به ریال حتما می بایست یک شماره حساب بانکی به سیستم معرفی کنید.می توانید از منوی افزودن کارت بانکی این کار را انجام دهید..</p>';
								} else {
									?>
                                    <select name="wallet_select" class="select2 form-control" style="width: 100%">
										<?php
										foreach ( $list as $r ) {
											?>
                                            <option value="<?php echo $r['bank']; ?> | <?php echo $r['card']; ?>"><?php echo $r['bank']; ?> | <?php echo $r['card']; ?></option>
											<?php
										}
										?>
                                    </select>
									<?php
								}
								?>
                            </div>

                            <input type="text" class="form-control text-left" name="wallet" value="" style="direction: ltr;">
                            <br/>
                            <br/>
                            <button type="button" data-change_price_form class="btn btn-primary" id="go_to_3" style="width: 100%;font-weight: 300;">مرحله بعد</button>

                        </div>
                        <div data-step="3">

                            <p>بررسی تبادل</p>
                            <hr>
                            <p>ارز مبداء</p>
                            <div class="text-left text-danger" data-step-3-arz-mabda></div>
                            <br/>
                            <p>ارز مقصد</p>
                            <div class="text-left text-danger" data-step-3-arz-maghsad></div>
                            <br/>
                            <p>میزان پرداخت</p>
                            <div class="text-left text-danger" data-step-3-m-p></div>
                            <br/>
                            <p>میزان دریافت</p>
                            <div class="text-left text-danger" data-step-3-m-d></div>
                            <br/>
                            <p>آدرس مقصد</p>
                            <div class="text-left text-danger" data-step-3-a-m></div>
                            <br/>
                            <button type="button" data-change_price_form class="btn btn-primary" id="go_to_4" style="width: 100%;font-weight: 300;">مرحله بعد</button>

                        </div>
                        <div data-step="4">

                            <p>پرداخت ارز</p>
                            <hr>
                            <p id="variz_kon"></p><br/>

                            <div class="text-center">
                                <img src="" id="wallet_img_barcode" style="width: 200px;height: 200px; margin-bottom: 10px;">
                            </div>
                            <br/>
                            <p>آدرس مقصد</p>
                            <div class="text-left text-danger" data-step-4-a-m-k></div>
                            <p data-depozit-show>دیپُزیت تَگ</p>
                            <div class="text-left text-danger" data-depozit-show="yes" data-step-4-a-m-d></div>
                            <br/>
                            <button type="button" data-change_price_form class="btn btn-primary" id="go_to_5" style="width: 100%;font-weight: 300;">پرداخت کردم</button>

                        </div>
                        <div data-step="5">

                            <p>نتیجه تبادل</p>
                            <hr>
                            <p>ارز مبداء</p>
                            <div class="text-left text-danger" data-step-3-arz-mabda></div>
                            <br/>
                            <p>ارز مقصد</p>
                            <div class="text-left text-danger" data-step-3-arz-maghsad></div>
                            <br/>
                            <p>میزان پرداخت</p>
                            <div class="text-left text-danger" data-step-3-m-p></div>
                            <br/>
                            <p>میزان دریافت</p>
                            <div class="text-left text-danger" data-step-3-m-d></div>
                            <br/>
                            <p>آدرس مقصد</p>
                            <div class="text-left text-danger" data-step-3-a-m></div>
                            <br/>

                            <br/>
                            <input type="submit" data-change_price_form class="btn btn-danger" value="ثبت سفارش" style="background-color: #f10210; width: 100%;font-weight: 300;">
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
					<?php //_e( "My Exchange List", "wp-afraex" ); ?>
                </h4>
                <!--                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>-->
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">

					<?php

					// Get List Last 30 Day
					$d           = array();
					$chart_label = array();
					for ( $i = 0; $i < 28; $i ++ ) {
						$d[]           = date( "Y-m-d", strtotime( '-' . $i . ' days' ) );
						$chart_label[] = date_i18n( "d M", strtotime( '-' . $i . ' days' ) );
					}

					// Get Count Post
					$list_post   = array();
					$arz_be_arz  = 0;
					$arz_be_rial = 0;

					$args             = array(
						'post_type'      => 'currency_order',
						'post_status'    => 'completed',
						'posts_per_page' => '-1',
						'order'          => 'ASC',
						'fields'         => 'ids',
						'date_query'     => array(
							array(
								'after' => '-1 month',
							),
						),
						'meta_query'     => array(
							array(
								'key'   => 'order_user_id',
								'value' => get_current_user_id(),
							)
						),
					);
					$query            = new \WP_Query( $args );
					$rial_currency_id = $opt['rial_currency'];
					foreach ( $query->posts as $ID ) {

						$type        = 'rial';
						$to_currency = get_post_meta( $ID, 'order_currency_to', true );
						if ( $to_currency == $rial_currency_id ) {
							$arz_be_rial ++;
						} else {
							$arz_be_arz ++;
							$type = 'arz';
						}


						// Add To Post List
						$t_p         = get_post( $ID );
						$date        = explode( " ", $t_p->post_date );
						$list_post[] = array( 'date' => $date[0], 'type' => $type );
					}
					wp_reset_postdata();


					// Prepare Chart
					$arz_be_rial_chart = $arz_be_arz_chart = array();
					foreach ( $d as $day ) {
						$arz_be_rial_chart_roz = $arz_be_arz_chart_roz = 0;


						foreach ( $list_post as $p ) {
							if ( $p['date'] == $day ) {
								if ( $p['type'] == "arz" ) {
									$arz_be_arz_chart_roz ++;
								} else {
									$arz_be_rial_chart_roz ++;
								}
							}
						}

						// Push to array
						$arz_be_rial_chart[] = $arz_be_rial_chart_roz;
						$arz_be_arz_chart[]  = $arz_be_arz_chart_roz;
					}
					?>
                    <script>
                        window.wp_price_chart_data_label = ['<?php echo implode( "','", $chart_label ); ?>'];
                        window.wp_price_chart_data_arz_be_rial = ['<?php echo implode( "','", $arz_be_rial_chart ); ?>'];
                        window.wp_price_chart_data_arz_be_arz = ['<?php echo implode( "','", $arz_be_arz_chart ); ?>'];
                    </script>

                    <div class="row">
                        <div class="col-12">
                            <div class="card pull-up bg-gradient-directional-danger">
                                <div class="card-header bg-hexagons-danger">
                                    <h4 class="card-title white">تبادلات ارز به ارز</h4>
                                    <div class="heading-elements"></div>
                                </div>
                                <div class="card-content collapse show bg-hexagons-danger">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center width-100">
                                                <div id="Analytics-donut-chart" class="height-100 donutShadow">
                                                    <div class="circle-w" style="text-align:center; border: 3px solid #fff;width: 80px;height: 80px;border-radius: 50%;">
                                                        <i class="la la-share" style="margin-top: 14px; font-size: 3rem;color: #fff;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="media-body text-right mt-1">
                                                <h3 class="font-large-2 white"><?php echo number_format( $arz_be_arz ); ?></h3>
                                                <h6 class="mt-1">
                                                    <span class="text-muted white">بررسی تبادلات در یک ماه </span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="card pull-up bg-gradient-directional-danger">
                                <div class="card-header bg-hexagons-danger">
                                    <h4 class="card-title white">تبادلات ارز به ریال</h4>
                                    <div class="heading-elements"></div>
                                </div>
                                <div class="card-content collapse show bg-hexagons-danger">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="align-self-center width-100">
                                                <div id="Analytics-donut-chart" class="height-100 donutShadow">
                                                    <div class="circle-w" style="text-align:center; border: 3px solid #fff;width: 80px;height: 80px;border-radius: 50%;">
                                                        <i class="la la-money" style="margin-top: 14px; font-size: 3rem;color: #fff;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="media-body text-right mt-1">
                                                <h3 class="font-large-2 white"><?php echo $arz_be_rial; ?></h3>
                                                <h6 class="mt-1">
                                                    <span class="text-muted white">بررسی تبادلات در یک ماه </span></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
					<?php //_e( "My Exchange List", "wp-afraex" ); ?>
                </h4>
                <!--                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>-->
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <canvas id="wp_stock_price_chart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    var admin_ajax_refresh_ = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    var _rial_id = '<?php echo $opt['rial_currency']; ?>';
</script>
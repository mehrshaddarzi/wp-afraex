<?php
global $wpdb;
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
					<?php //_e( "My Exchange List", "wp-afraex" ); ?>
                </h4>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
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
					if ( isset( $_GET['alert'] ) and isset( $_GET['meta'] ) ) {
						?>
                        <div class="alert alert-success mb-2" role="alert">
							سفارش با موفقیت ثبت گردید.
                        </div>
						<?php
					}
					?>

					<?php
					$paged       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
					$args        = array(
						'paged'          => $paged,
						'post_type'      => 'currency_order',
						'post_status'    => 'any',
						'meta_query'     => array(
							array(
								'key'     => 'order_user_id',
								'value'   => get_current_user_id(),
								'compare' => '=',
							)
						),
						'posts_per_page' => 20,
						'order'          => 'DESC',
						'orderby'        => 'ID'
					);
					$query       = new \WP_Query( $args );
					$count_order = $query->found_posts;
					if ( $count_order < 1 ) {
						?>
                        <p class="text-center"><?php _e( "You haven't made any exchanges yet", "wp-afraex" ); ?></p>
						<?php
					} else {
						?>

                        <div class="table-responsive">
                            <style>
                                @media (max-width: 920px) {
                                    .w-m-100 {
                                        width: 100px !important;
                                    }

                                    .w-m-200 {
                                        width: 200px !important;
                                    }

                                    .w-m-150 {
                                        width: 150px !important;
                                    }
                                }
                            </style>
                            <table class="table table-striped table-bordered" style="table-layout: fixed;">
                                <thead>
                                <tr>
                                    <th class="w-m-150"><?php _e( "Order id", "wp-afraex" ); ?></th>
                                    <th class="w-m-200"><?php _e( "Date", "wp-afraex" ); ?></th>
                                    <th class="w-m-150"><?php _e( "From", "wp-afraex" ); ?></th>
                                    <th class="w-m-150"><?php _e( "Price", "wp-afraex" ); ?></th>
                                    <th class="w-m-150"><?php _e( "To", "wp-afraex" ); ?></th>
                                    <th class="w-m-150"><?php _e( "Exchange", "wp-afraex" ); ?></th>
                                    <th class="w-m-200"><?php _e( "Status", "wp-afraex" ); ?></th>
                                    <th class="w-m-200"></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								while ( $query->have_posts() ):
									$query->the_post();
									$post_id = get_the_ID();
									?>
                                    <tr>
                                        <td><strong>#<?php echo get_the_ID(); ?></strong></td>
                                        <td><?php echo get_the_date( 'Y-m-d H:i', get_the_ID() ); ?></td>
                                        <td>
											<?php
											echo get_the_title( get_post_meta( $post_id, 'order_currency_from', true ) ) . '<br />' . get_post_meta( get_post_meta( $post_id, 'order_currency_from', true ), 'currency_persian', true );
											?>
                                        </td>
                                        <td>
											<?php
											$price = get_post_meta( $post_id, 'order_currency_from_price', true );
											echo( \WP_AFRAEX\admin\Admin::is_numeric( $price ) ? number_format( $price ) : $price );
											?>
                                        </td>
                                        <td>
											<?php
											echo get_the_title( get_post_meta( $post_id, 'order_currency_to', true ) ) . '<br />' . get_post_meta( get_post_meta( $post_id, 'order_currency_to', true ), 'currency_persian', true );
											?>
                                        </td>
                                        <td>
											<?php
											$price = get_post_meta( $post_id, 'order_currency_calculate', true );
											echo( \WP_AFRAEX\admin\Admin::is_numeric( $price ) ? number_format( $price ) : $price );
											?>
                                        </td>
                                        <td>
											<?php
											$post_status        = get_post_status( $post_id );
											$post_status_detail = get_post_status_object( $post_status );
											$color              = 'primary';
											if ( $post_status == "canceled" ) {
												$color = 'danger';
											}
											if ( $post_status == "completed" ) {
												$color = 'success';
											}
											echo '<span class="text-' . $color . '">' . $post_status_detail->label . '</span>';
											?>
                                        </td>
                                        <td>
											<?php
											$content_stripe = wp_strip_all_tags( get_the_content() );
											if ( ! empty( $content_stripe ) ) {
												?>
                                                <button type="button" class="btn btn-primary" style="font-weight: normal;" data-toggle="modal" data-target="#default_<?php echo get_the_ID(); ?>">
													<?php _e( "Description", "wp-afraex" ); ?>
                                                </button>

                                                <div class="modal fade" tabindex="-1" role="dialog" id="default_<?php echo get_the_ID(); ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
																<?php
																echo apply_filters( 'the_content', get_the_content() );
																$wallet = get_post_meta( $post_id, 'order_user_wallet', true );
																if ( ! empty( $wallet ) ) {
																	?>
                                                                    <br/>
                                                                    <hr>
                                                                    <p><?php _e( "Your Wallet", "wp-afraex" ); ?>: <?php echo $wallet; ?></p>
																	<?php
																}
																?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												<?php
											} else {
												echo '-';
											}
											?>
                                        </td>
                                    </tr>

								<?php
								endwhile;
								wp_reset_postdata();
								?>
                                </tbody>
                            </table>
                        </div>

                        <br/>
						<?php
						$big   = 999999999; // need an unlikely integer
						$pages = paginate_links( array(
								'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
								'format'    => '?paged=%#%',
								'current'   => max( 1, get_query_var( 'paged' ) ),
								'total'     => $query->max_num_pages,
								'type'      => 'array',
								'prev_next' => true,
								'prev_text' => __( 'Prev', "wp-afraex" ),
								'next_text' => __( 'Next', "wp-afraex" ),
							)
						);

						if ( is_array( $pages ) ) {
							$paged      = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );
							$pagination = '<div class="pagination-wrap"><ul class="pagination">';
							foreach ( $pages as $page ) {

								$page = str_replace( 'class="page-numbers"', 'class="page-link"', $page );
								$page = str_replace( 'class="page-numbers"', 'class="page-link"', $page );

								$pagination .= "<li class=\"page-item\">" .
								               str_replace( 'class="page-numbers"', 'class="page-link"', $page )
								               . "</li>";
							}
							$pagination .= '</ul></div>';
							$output     = $pagination;


							// Create an instance of DOMDocument
							$dom = new \DOMDocument();

							// Populate $dom with $output, making sure to handle UTF-8, otherwise
							// problems will occur with UTF-8 characters.
							$dom->loadHTML( mb_convert_encoding( $output, 'HTML-ENTITIES', 'UTF-8' ) );

							// Create an instance of DOMXpath and all elements with the class 'page-numbers'
							$xpath = new \DOMXpath( $dom );

							// http://stackoverflow.com/a/26126336/3059883
							$page_numbers = $xpath->query( "//*[contains(concat(' ', normalize-space(@class), ' '), ' page-numbers ')]" );

							// Iterate over the $page_numbers node...
							foreach ( $page_numbers as $page_numbers_item ) {

								// Add class="mynewclass" to the <li> when its child contains the current item.
								$page_numbers_item_classes = explode( ' ', $page_numbers_item->attributes->item( 0 )->value );
								if ( in_array( 'current', $page_numbers_item_classes ) ) {
									$list_item_attr_class        = $dom->createAttribute( 'class' );
									$list_item_attr_class->value = 'mynewclass';
									$page_numbers_item->parentNode->appendChild( $list_item_attr_class );
								}

								// Replace the class 'current' with 'active'
								$page_numbers_item->attributes->item( 0 )->value = str_replace(
									'current',
									'active',
									$page_numbers_item->attributes->item( 0 )->value );

								// Replace the class 'page-numbers' with 'page-link'
								$page_numbers_item->attributes->item( 0 )->value = str_replace(
									'page-numbers',
									'page-link',
									$page_numbers_item->attributes->item( 0 )->value );
							}

							// Save the updated HTML and output it.
							$output = $dom->saveHTML();

							echo str_replace( array( "<span aria-current=\"page\" class=\"page-numbers current\">", "</span>" ), array( "<a aria-current=\"page\" class=\"page-link current\">", "</a>" ), $output );
						}


					} // Exist Order
					?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .current, .current:hover {
        background: #280975 !important;
        color: #fff !important;
        border-color: transparent;
    }
</style>
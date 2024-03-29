<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.12
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>

<div class="wishlistPageCustom">
  <div class="container">
    <form id="yith-wcwl-form" action="<?php echo $form_action ?>" method="post" class="woocommerce">

        <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>

        <!-- TITLE -->
        <?php
        do_action( 'yith_wcwl_before_wishlist_title', $wishlist_meta );

        if( ! empty( $page_title ) ) :
        ?>
            <div style="display: none;" class="wishlist-title <?php echo ( $is_custom_list ) ? 'wishlist-title-with-form' : ''?>">
                <?php echo apply_filters( 'yith_wcwl_wishlist_title', '<h2>' . $page_title . '</h2>' ); ?>
                <?php if( $is_custom_list ): ?>
                    <a class="btn button show-title-form">
                        <?php echo apply_filters( 'yith_wcwl_edit_title_icon', '<i class="fa fa-pencil"></i>' )?>
                        <?php _e( 'Edit title', 'yith-woocommerce-wishlist' ) ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php if( $is_custom_list ): ?>
                <div class="hidden-title-form">
                    <input type="text" value="<?php echo $page_title ?>" name="wishlist_name"/>
                    <button>
                        <?php echo apply_filters( 'yith_wcwl_save_wishlist_title_icon', '<i class="fa fa-check"></i>' )?>
                        <?php _e( 'Save', 'yith-woocommerce-wishlist' )?>
                    </button>
                    <a class="hide-title-form btn button">
                        <?php echo apply_filters( 'yith_wcwl_cancel_wishlist_title_icon', '<img src="'.get_bloginfo('url').'/wp-content/uploads/2019/08/times.png" alt="" />' )?>
                        <?php _e( 'Cancel', 'yith-woocommerce-wishlist' )?>
                    </a>
                </div>
            <?php endif; ?>
        <?php
        endif;

         do_action( 'yith_wcwl_before_wishlist', $wishlist_meta ); ?>

        <!-- WISHLIST TABLE -->
    	<div class="shop_table cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo $wishlist_id ?>" data-token="<?php echo $wishlist_token ?>">

    	    <?php $column_count = 2; ?>


            <div class="tbody">
            <?php
            if( count( $wishlist_items ) > 0 ) :
    	        $added_items = array();
                foreach( $wishlist_items as $item ) :
                    global $product;

    	            $item['prod_id'] = yit_wpml_object_id ( $item['prod_id'], 'product', true );

    	            if( in_array( $item['prod_id'], $added_items ) ){
    		            continue;
    	            }

    	            $added_items[] = $item['prod_id'];
    	            $product = wc_get_product( $item['prod_id'] );
    	            $availability = $product->get_availability();
    	            $stock_status = isset( $availability['class'] ) ? $availability['class'] : false;

                    if( $product && $product->exists() ) :
    	                ?>
                        <div class="trWrap">
                          <div class="tr" id="yith-wcwl-row-<?php echo $item['prod_id'] ?>" data-row-id="<?php echo $item['prod_id'] ?>">
      	                    <div class="mainPartItem">
      												<?php if( $show_cb ) : ?>
      			                    <div class="td" class="product-checkbox">
      				                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( ! $product->is_type( 'simple' ) ) ? 'disabled="disabled"' : '' ?>/>
      			                    </div>
      		                    <?php endif ?>

      	                        <?php if( $is_user_owner ): ?>
      	                        <div class="td product-remove">
      	                            <div>
      	                                <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist" title="<?php echo apply_filters( 'yith_wcwl_remove_product_wishlist_message_title',__( 'Remove this product', 'yith-woocommerce-wishlist' )); ?>"><img src="<?php echo get_bloginfo('url'); ?>/wp-content/uploads/2019/08/times.png" alt="remove" /></a>
      	                            </div>
      	                        </div>
      	                        <?php endif; ?>

      	                        <div class="td product-thumbnail">
      	                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
      	                                <?php echo $product->get_image() ?>
      	                            </a>
      	                        </div>

      	                        <div class="td product-name">
      	                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
      	                            <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>
      	                        </div>

																<div class="td product-exerpt">
																		<?php
																		echo $product->get_short_description();
																		?>
																</div>


      	                        <?php if( $show_price ) : ?>
      	                            <div class="td product-price">
      	                                <?php
      	                                $base_product = $product->is_type( 'variable' ) ? $product->get_variation_regular_price( 'max' ) : $product->get_price();
      	                                echo $base_product ? $product->get_price_html() : apply_filters( 'yith_free_text', __( 'Free!', 'yith-woocommerce-wishlist' ), $product );
      	                                ?>
      	                            </div>
      	                        <?php endif ?>
      	                    </div>

      	                    <?php if( $show_last_column ): ?>
                              <div class="td product-add-to-cart">
      	                        <!-- Date added -->
      	                        <?php
      	                        if( $show_dateadded && isset( $item['dateadded'] ) ):
      								echo '<span class="dateadded">' . sprintf( __( 'Added on : %s', 'yith-woocommerce-wishlist' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
      	                        endif;
      	                        ?>

                                  <!-- Add to cart button -->
                                  <?php if( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'out-of-stock' ): ?>
                                      <?php woocommerce_template_loop_add_to_cart(); ?>
                                  <?php endif ?>

      	                        <!-- Change wishlist -->
      							<?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist && $is_user_owner ): ?>
      	                        <select class="change-wishlist selectBox">
      		                        <option value=""><?php _e( 'Move', 'yith-woocommerce-wishlist' ) ?></option>
      		                        <?php
      		                        foreach( $users_wishlists as $wl ):
      			                        if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
      				                        continue;
      			                        }

      		                        ?>
      			                        <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
      				                        <?php
      				                        $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
      				                        if( $wl['wishlist_privacy'] == 1 ){
      					                        $wl_privacy = __( 'Shared', 'yith-woocommerce-wishlist' );
      				                        }
      				                        elseif( $wl['wishlist_privacy'] == 2 ){
      					                        $wl_privacy = __( 'Private', 'yith-woocommerce-wishlist' );
      				                        }
      				                        else{
      					                        $wl_privacy = __( 'Public', 'yith-woocommerce-wishlist' );
      				                        }

      				                        echo sprintf( '%s - %s', $wl_title, $wl_privacy );
      				                        ?>
      			                        </option>
      		                        <?php
      		                        endforeach;
      		                        ?>
      	                        </select>
      	                        <?php endif; ?>

      	                        <!-- Remove from wishlist -->
      	                        <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                      <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php echo apply_filters( 'yith_wcwl_remove_product_wishlist_message_title',__( 'Remove this product', 'yith-woocommerce-wishlist' )); ?>"><?php _e( 'Remove', 'yith-woocommerce-wishlist' ) ?></a>
                                  <?php endif; ?>
                              </div>
      	                <?php endif; ?>
                          </div>
                        </div>
                    <?php
                    endif;
                endforeach;
            else: ?>
                <div class="tr">
                    <div class="td" colspan="<?php echo esc_attr( $column_count ) ?>" class="wishlist-empty"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', __( 'No products were added to the wishlist', 'yith-woocommerce-wishlist' ) ) ?></div>
                </div>
            <?php
            endif;

            if( ! empty( $page_links ) ) : ?>
                <div class="tr pagination-row">
                    <div class="td" colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo $page_links ?></div>
                </div>
            <?php endif ?>
            </div>

        </div>

        <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

        <?php if( ! $is_default ): ?>
            <input type="hidden" value="<?php echo $wishlist_token ?>" name="wishlist_id" id="wishlist_id">
        <?php endif; ?>

        <?php do_action( 'yith_wcwl_after_wishlist', $wishlist_meta ); ?>

    </form>

    <?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

    <?php if( $show_ask_estimate_button && ( ! is_user_logged_in() || $additional_info ) ): ?>
    	<div id="ask_an_estimate_popup">
    		<form action="<?php echo $ask_estimate_url ?>" method="post" class="wishlist-ask-an-estimate-popup">
    			<?php if( ! is_user_logged_in() ): ?>
    				<label for="reply_email"><?php echo apply_filters( 'yith_wcwl_ask_estimate_reply_mail_label', __( 'Your email', 'yith-woocommerce-wishlist' ) ) ?></label>
    				<input type="email" value="" name="reply_email" id="reply_email">
    			<?php endif; ?>
    			<?php if( ! empty( $additional_info_label ) ):?>
    				<label for="additional_notes"><?php echo esc_html( $additional_info_label ) ?></label>
    			<?php endif; ?>
    			<textarea id="additional_notes" name="additional_notes"></textarea>

    			<button class="btn button ask-an-estimate-button ask-an-estimate-button-popup" >
    				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
    				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', __( 'Ask for an estimate', 'yith-woocommerce-wishlist' ) ) ?>
    			</button>
    		</form>
    	</div>
    <?php endif; ?>
  </div>
</div>

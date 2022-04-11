<?php
/*
Plugin Name: WPC Added To Cart Notification for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Added To Cart Notification will open a popup to notify the customer immediately after adding a product to cart.
Version: 2.0.6
Author: WPClever
Author URI: https://wpclever.net
Text Domain: woo-added-to-cart-notification
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 5.9
WC requires at least: 3.0
WC tested up to: 6.3
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOAC_VERSION' ) && define( 'WOOAC_VERSION', '2.0.6' );
! defined( 'WOOAC_URI' ) && define( 'WOOAC_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOAC_SUPPORT' ) && define( 'WOOAC_SUPPORT', 'https://wpclever.net/support?utm_source=support&utm_medium=wooac&utm_campaign=wporg' );
! defined( 'WOOAC_REVIEWS' ) && define( 'WOOAC_REVIEWS', 'https://wordpress.org/support/plugin/woo-added-to-cart-notification/reviews/?filter=5' );
! defined( 'WOOAC_CHANGELOG' ) && define( 'WOOAC_CHANGELOG', 'https://wordpress.org/plugins/woo-added-to-cart-notification/#developers' );
! defined( 'WOOAC_DISCUSSION' ) && define( 'WOOAC_DISCUSSION', 'https://wordpress.org/support/plugin/woo-added-to-cart-notification' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOAC_URI );

include 'includes/wpc-dashboard.php';
include 'includes/wpc-menu.php';
include 'includes/wpc-kit.php';
include 'includes/wpc-notice.php';

if ( ! function_exists( 'wooac_init' ) ) {
	add_action( 'plugins_loaded', 'wooac_init', 11 );

	function wooac_init() {
		// load text-domain
		load_plugin_textdomain( 'woo-added-to-cart-notification', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0', '>=' ) ) {
			add_action( 'admin_notices', 'wooac_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WPCleverWooac' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWooac {
				protected static $localization = array();

				function __construct() {
					add_action( 'init', array( $this, 'wooac_init' ) );

					// menu
					add_action( 'admin_menu', array( $this, 'wooac_admin_menu' ) );

					// backend scripts
					add_action( 'admin_enqueue_scripts', array( $this, 'wooac_admin_enqueue_scripts' ) );

					// frontend scripts
					add_action( 'wp_enqueue_scripts', array( $this, 'wooac_enqueue_scripts' ) );

					// link
					add_filter( 'plugin_action_links', array( $this, 'wooac_action_links' ), 10, 2 );
					add_filter( 'plugin_row_meta', array( $this, 'wooac_row_meta' ), 10, 2 );

					// add the time
					add_action( 'woocommerce_add_to_cart', array( $this, 'wooac_add_to_cart' ), 10 );

					// fragments
					add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'wooac_cart_fragment' ) );

					// footer
					add_action( 'wp_footer', array( $this, 'wooac_footer' ) );
				}

				function wooac_init() {
					self::$localization = (array) get_option( 'wooac_localization' );
				}

				function wooac_localization( $key = '', $default = '' ) {
					$str = '';

					if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
						$str = self::$localization[ $key ];
					} elseif ( ! empty( $default ) ) {
						$str = $default;
					}

					return apply_filters( 'wooac_localization_' . $key, $str );
				}

				function wooac_admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Added To Cart Notification', 'woo-added-to-cart-notification' ), esc_html__( 'Added To Cart Notification', 'woo-added-to-cart-notification' ), 'manage_options', 'wpclever-wooac', array(
						&$this,
						'wooac_admin_menu_content'
					) );
				}

				function wooac_admin_menu_content() {
					add_thickbox();
					$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Added To Cart Notification', 'woo-added-to-cart-notification' ) . ' ' . WOOAC_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'woo-added-to-cart-notification' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOAC_REVIEWS ); ?>"
                                   target="_blank"><?php esc_html_e( 'Reviews', 'woo-added-to-cart-notification' ); ?></a>
                                | <a
                                        href="<?php echo esc_url( WOOAC_CHANGELOG ); ?>"
                                        target="_blank"><?php esc_html_e( 'Changelog', 'woo-added-to-cart-notification' ); ?></a>
                                | <a href="<?php echo esc_url( WOOAC_DISCUSSION ); ?>"
                                     target="_blank"><?php esc_html_e( 'Discussion', 'woo-added-to-cart-notification' ); ?></a>
                            </p>
                        </div>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=settings' ); ?>"
                                   class="<?php echo $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>">
									<?php esc_html_e( 'Settings', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=localization' ); ?>"
                                   class="<?php echo $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>">
									<?php esc_html_e( 'Localization', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-wooac&tab=premium' ); ?>"
                                   class="<?php echo $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>"
                                   style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'woo-added-to-cart-notification' ); ?>
                                </a>
                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>"
                                   class="nav-tab">
									<?php esc_html_e( 'Essential Kit', 'woo-added-to-cart-notification' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$wooac_style = get_option( 'wooac_style', 'default' );

								// popup
								$wooac_effect                 = get_option( 'wooac_effect', 'mfp-3d-unfold' );
								$wooac_show_image             = get_option( 'wooac_show_image', 'yes' );
								$wooac_show_content           = get_option( 'wooac_show_content', 'yes' );
								$wooac_free_shipping_bar      = get_option( 'wooac_free_shipping_bar', 'yes' );
								$wooac_show_related           = get_option( 'wooac_show_related', 'no' );
								$wooac_show_share_cart        = get_option( 'wooac_show_share_cart', 'yes' );
								$wooac_show_view_cart         = get_option( 'wooac_show_view_cart', 'yes' );
								$wooac_show_checkout          = get_option( 'wooac_show_checkout', 'no' );
								$wooac_show_continue_shopping = get_option( 'wooac_show_continue_shopping', 'yes' );
								$wooac_continue_url           = get_option( 'wooac_continue_url', '' );
								$wooac_add_link               = get_option( 'wooac_add_link', 'yes' );
								$wooac_auto_close             = get_option( 'wooac_auto_close', '2000' );

								// notiny
								$wooac_notiny_position = get_option( 'wooac_notiny_position', 'right-bottom' );
								?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ) ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th colspan="2">
												<?php esc_html_e( 'General', 'woo-added-to-cart-notification' ); ?>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php esc_html_e( 'Style', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_style">
                                                    <option value="default" <?php echo esc_attr( $wooac_style === 'default' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Popup (default)', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="notiny" <?php echo esc_attr( $wooac_style === 'notiny' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Notiny', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Popup effect', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_effect">
                                                    <option value="mfp-fade" <?php echo esc_attr( $wooac_effect === 'mfp-fade' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Fade', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-zoom-in" <?php echo esc_attr( $wooac_effect === 'mfp-zoom-in' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Zoom in', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-zoom-out" <?php echo esc_attr( $wooac_effect === 'mfp-zoom-out' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Zoom out', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-newspaper" <?php echo esc_attr( $wooac_effect === 'mfp-newspaper' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Newspaper', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-move-horizontal" <?php echo esc_attr( $wooac_effect === 'mfp-move-horizontal' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Move horizontal', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-move-from-top" <?php echo esc_attr( $wooac_effect === 'mfp-move-from-top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Move from top', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-3d-unfold" <?php echo esc_attr( $wooac_effect === 'mfp-3d-unfold' ? 'selected' : '' ); ?>>
														<?php esc_html_e( '3d unfold', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="mfp-slide-bottom" <?php echo esc_attr( $wooac_effect === 'mfp-slide-bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Slide bottom', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Product image', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_image">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_image === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_image === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide the product image.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Link to individual product', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_add_link">
                                                    <option value="yes" <?php echo esc_attr( $wooac_add_link === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open in the same tab', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="yes_blank" <?php echo esc_attr( $wooac_add_link === 'yes_blank' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open in the new tab', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="yes_popup" <?php echo esc_attr( $wooac_add_link === 'yes_popup' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Yes, open quick view popup', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_add_link === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'No', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">If you choose "Open quick view popup", please install <a
                                                            href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                            class="thickbox" title="Install WPC Smart Quick View">WPC Smart Quick View</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Related products', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_related">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_related === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_related === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide related products.', 'woo-added-to-cart-notification' ); ?></span>
                                                <p style="color: #c9356e">
                                                    This feature is only available on the Premium Version. Click <a
                                                            href="https://wpclever.net/downloads/added-to-cart-notification?utm_source=pro&utm_medium=wooac&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Cart content', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_content">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_content === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_content === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide the cart total and cart content count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Free shipping bar', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_free_shipping_bar">
                                                    <option value="yes" <?php echo esc_attr( $wooac_free_shipping_bar === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_free_shipping_bar === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">If you enable this option, please install and activate <a
                                                            href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-free-shipping-bar&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                            class="thickbox" title="Install WPC Free Shipping Bar">WPC Free Shipping Bar</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th><?php esc_html_e( 'Share cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_share_cart">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_share_cart === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_share_cart === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description">If you enable this option, please install and activate <a
                                                            href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-share-cart&TB_iframe=true&width=800&height=550' ) ); ?>"
                                                            class="thickbox" title="Install WPC Share Cart">WPC Share Cart</a> to make it work.</span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'View cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_view_cart">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_view_cart === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_view_cart === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "View cart" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Checkout', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_checkout">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_checkout === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_checkout === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "Checkout" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_show_continue_shopping">
                                                    <option value="yes" <?php echo esc_attr( $wooac_show_continue_shopping === 'yes' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="no" <?php echo esc_attr( $wooac_show_continue_shopping === 'no' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                                <span class="description"><?php esc_html_e( 'Show/hide "Continue shopping" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Continue shopping link', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="url" name="wooac_continue_url"
                                                       value="<?php echo esc_url( $wooac_continue_url ); ?>"
                                                       class="regular-text code"/>
                                                <span class="description"><?php esc_html_e( 'By default, only hide the popup when clicking on "Continue Shopping" button.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-default">
                                            <th scope="row"><?php esc_html_e( 'Auto close', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input name="wooac_auto_close" type="number" min="0" max="300000"
                                                       step="1"
                                                       value="<?php echo esc_attr( $wooac_auto_close ); ?>"/>ms.
                                                <span class="description"><?php esc_html_e( 'Set the time is zero to disable auto close.', 'woo-added-to-cart-notification' ); ?></span>
                                                <p style="color: #c9356e">
                                                    This feature is only available on the Premium Version. Click <a
                                                            href="https://wpclever.net/downloads/added-to-cart-notification?utm_source=pro&utm_medium=wooac&utm_campaign=wporg"
                                                            target="_blank">here</a> to buy, just $29.
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="wooac-show-if-style-notiny">
                                            <th scope="row"><?php esc_html_e( 'Position', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <select name="wooac_notiny_position">
                                                    <option value="right-top" <?php echo esc_attr( $wooac_notiny_position === 'right-top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'right-top', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="right-bottom" <?php echo esc_attr( $wooac_notiny_position === 'right-bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'right-bottom', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="fluid-top" <?php echo esc_attr( $wooac_notiny_position === 'fluid-top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'center-top', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="fluid-bottom" <?php echo esc_attr( $wooac_notiny_position === 'fluid-bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'center-bottom', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="left-top" <?php echo esc_attr( $wooac_notiny_position === 'left-top' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'left-top', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                    <option value="left-bottom" <?php echo esc_attr( $wooac_notiny_position === 'left-bottom' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'left-bottom', 'woo-added-to-cart-notification' ); ?>
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_attr_e( 'Update Options', 'woo-added-to-cart-notification' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options"
                                                       value="wooac_style,wooac_effect,wooac_show_image,wooac_show_content,wooac_free_shipping_bar,wooac_show_related,wooac_show_share_cart,wooac_show_view_cart,wooac_show_checkout,wooac_show_continue_shopping,wooac_continue_url,wooac_add_link,wooac_auto_close,wooac_notiny_position"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'localization' ) { ?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ) ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th scope="row"><?php esc_html_e( 'Localization', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
												<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'woo-added-to-cart-notification' ); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Added to the cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text" name="wooac_localization[added]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'added' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( '%s was added to the cart.', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the product name.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'You may also like', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[related]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'related' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'You may also like', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Cart content', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[cart_content]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'cart_content' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Your cart: %s', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the cart content.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Count (singular)', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[count_singular]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'count_singular' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( '%s item', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Count (plural)', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[count_plural]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'count_plural' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( '%s items', 'woo-added-to-cart-notification' ); ?>"/>
                                                <span class="description"><?php esc_html_e( 'Use %s to show the count.', 'woo-added-to-cart-notification' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Share cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[share_cart]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'share_cart' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Share cart', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'View cart', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[view_cart]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'view_cart' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'View cart', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Checkout', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[checkout]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'checkout' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Checkout', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?></th>
                                            <td>
                                                <input type="text" class="regular-text"
                                                       name="wooac_localization[continue]"
                                                       value="<?php echo esc_attr( self::wooac_localization( 'continue' ) ); ?>"
                                                       placeholder="<?php esc_attr_e( 'Continue shopping', 'woo-added-to-cart-notification' ); ?>"/>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_attr_e( 'Update Options', 'woo-added-to-cart-notification' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options" value="wooac_localization"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
                                        Get the Premium Version just $29! <a
                                                href="https://wpclever.net/downloads/added-to-cart-notification?utm_source=pro&utm_medium=wooac&utm_campaign=wporg"
                                                target="_blank">https://wpclever.net/downloads/added-to-cart-notification</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Add the time to auto close popup.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function wooac_admin_enqueue_scripts() {
					wp_enqueue_script( 'wooac-backend', WOOAC_URI . 'assets/js/backend.js', array( 'jquery' ), WOOAC_VERSION, true );
				}

				function wooac_enqueue_scripts() {
					$style = get_option( 'wooac_style', 'default' );

					switch ( $style ) {
						case 'notiny':
							// notiny
							wp_enqueue_style( 'notiny', WOOAC_URI . 'assets/libs/notiny/notiny.css' );
							wp_enqueue_script( 'notiny', WOOAC_URI . 'assets/libs/notiny/notiny.js', array( 'jquery' ), WOOAC_VERSION, true );
							break;
						default:
							// feather icons
							wp_enqueue_style( 'wooac-feather', WOOAC_URI . 'assets/libs/feather/feather.css' );

							// magnific
							wp_enqueue_style( 'magnific-popup', WOOAC_URI . 'assets/libs/magnific-popup/magnific-popup.css' );
							wp_enqueue_script( 'magnific-popup', WOOAC_URI . 'assets/libs/magnific-popup/jquery.magnific-popup.min.js', array( 'jquery' ), WOOAC_VERSION, true );
					}

					// main style & js
					wp_enqueue_style( 'wooac-frontend', WOOAC_URI . 'assets/css/frontend.css', array(), WOOAC_VERSION );
					wp_enqueue_script( 'wooac-frontend', WOOAC_URI . 'assets/js/frontend.js', array( 'jquery' ), WOOAC_VERSION, true );
					wp_localize_script( 'wooac-frontend', 'wooac_vars', array(
							'ajax_url'        => admin_url( 'admin-ajax.php' ),
							'style'           => get_option( 'wooac_style', 'default' ),
							'effect'          => get_option( 'wooac_effect', 'mfp-3d-unfold' ),
							'related'         => get_option( 'wooac_show_related', 'no' ),
							'close'           => (int) get_option( 'wooac_auto_close', '2000' ),
							'delay'           => (int) apply_filters( 'wooac_delay', 300 ),
							'notiny_position' => get_option( 'wooac_notiny_position', 'right-bottom' ),
							'nonce'           => wp_create_nonce( 'wooac-nonce' ),
							'is_rtl'          => is_rtl()
						)
					);
				}

				function wooac_action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings         = '<a href="' . admin_url( 'admin.php?page=wpclever-wooac&tab=settings' ) . '">' . esc_html__( 'Settings', 'woo-added-to-cart-notification' ) . '</a>';
						$links['premium'] = '<a href="' . admin_url( 'admin.php?page=wpclever-wooac&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'woo-added-to-cart-notification' ) . '</a>';
						array_unshift( $links, $settings );
					}

					return (array) $links;
				}

				function wooac_row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = array(
							'support' => '<a href="' . esc_url( WOOAC_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'woo-added-to-cart-notification' ) . '</a>',
						);

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function wooac_get_notiny() {
					$items       = WC()->cart->get_cart();
					$return_html = '<div class="wooac-notiny">';

					if ( count( $items ) > 0 ) {
						foreach ( $items as $key => $item ) {
							if ( ! isset( $item['wooac_time'] ) ) {
								$items[ $key ]['wooac_time'] = time() - 10000;
							}
						}

						array_multisort( array_column( $items, 'wooac_time' ), SORT_ASC, $items );
						$wooac_product = end( $items )['data'];

						if ( $wooac_product && ( $wooac_product_id = $wooac_product->get_id() ) ) {
							if ( ! in_array( $wooac_product_id, apply_filters( 'wooac_exclude_ids', array( 0 ) ), true ) ) {
								$return_html .= apply_filters( 'wooac_image', '<div class="wooac-image">' . $wooac_product->get_image() . '</div>', $wooac_product );
								$return_html .= apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::wooac_localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<span>' . $wooac_product->get_name() . '</span>' ) . '</div>', $wooac_product );
							}
						}
					}

					$return_html .= '</div>';

					return apply_filters( 'wooac_notiny_html', $return_html );
				}

				function wooac_get_popup() {
					$items = WC()->cart->get_cart();

					$return_html = '<div class="wooac-popup mfp-with-anim">';
					$return_html .= apply_filters( 'wooac_before', '' );

					if ( count( $items ) > 0 ) {
						foreach ( $items as $key => $item ) {
							if ( ! isset( $item['wooac_time'] ) ) {
								$items[ $key ]['wooac_time'] = time() - 10000;
							}
						}

						array_multisort( array_column( $items, 'wooac_time' ), SORT_ASC, $items );
						$wooac_product = end( $items )['data'];

						if ( $wooac_product && ( $wooac_product_id = $wooac_product->get_id() ) ) {
							if ( ! in_array( $wooac_product_id, apply_filters( 'wooac_exclude_ids', array( 0 ) ), true ) ) {
								$_link = get_option( 'wooac_add_link', 'yes' );

								if ( get_option( 'wooac_show_image', 'yes' ) === 'yes' ) {
									if ( $_link !== 'no' ) {
										$return_html .= apply_filters( 'wooac_image', '<div class="wooac-image"><a ' . ( $_link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $wooac_product_id . '"' : '' ) . ' href="' . $wooac_product->get_permalink() . '" ' . ( $_link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $wooac_product->get_image() . '</a></div>', $wooac_product );
									} else {
										$return_html .= apply_filters( 'wooac_image', '<div class="wooac-image">' . $wooac_product->get_image() . '</div>', $wooac_product );
									}
								}

								$return_html .= apply_filters( 'wooac_image_after', '' );

								if ( $_link !== 'no' ) {
									$return_html .= apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::wooac_localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<a ' . ( $_link === 'yes_popup' ? 'class="woosq-btn" data-id="' . $wooac_product_id . '"' : '' ) . ' href="' . $wooac_product->get_permalink() . '" ' . ( $_link === 'yes_blank' ? 'target="_blank"' : '' ) . '>' . $wooac_product->get_name() . '</a>' ) . '</div>', $wooac_product );
								} else {
									$return_html .= apply_filters( 'wooac_text', '<div class="wooac-text">' . sprintf( self::wooac_localization( 'added', esc_html__( '%s was added to the cart.', 'woo-added-to-cart-notification' ) ), '<span>' . $wooac_product->get_name() . '</span>' ) . '</div>', $wooac_product );
								}

								$return_html .= apply_filters( 'wooac_text_after', '' );

								if ( get_option( 'wooac_show_content', 'yes' ) === 'yes' ) {
									$count = WC()->cart->get_cart_contents_count();

									if ( $count === 1 ) {
										$count_str = self::wooac_localization( 'count_singular', esc_html__( '%s item', 'woo-added-to-cart-notification' ) );
									} else {
										$count_str = self::wooac_localization( 'count_plural', esc_html__( '%s items', 'woo-added-to-cart-notification' ) );
									}

									$cart_content_data = '<span class="wooac-cart-content-total">' . apply_filters( 'wooac_cart_content_total', wp_kses_post( WC()->cart->get_cart_subtotal() ) ) . '</span> <span class="wooac-cart-content-count">' . apply_filters( 'wooac_cart_content_count', wp_kses_data( sprintf( $count_str, WC()->cart->get_cart_contents_count() ) ) ) . '</span>';
									$cart_content      = sprintf( self::wooac_localization( 'cart_content', esc_html__( 'Your cart: %s', 'woo-added-to-cart-notification' ) ), $cart_content_data );
									$return_html       .= apply_filters( 'wooac_cart_content', '<div class="wooac-cart-content">' . $cart_content . '</div>' );
								}

								$return_html .= apply_filters( 'wooac_cart_content_after', '' );

								if ( ( get_option( 'wooac_free_shipping_bar', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcfb' ) ) {
									$return_html .= do_shortcode( '[wpcfb]' );
								}

								if ( ( ( get_option( 'wooac_show_share_cart', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcss' ) ) || ( get_option( 'wooac_show_view_cart', 'yes' ) === 'yes' ) || ( get_option( 'wooac_show_checkout', 'no' ) === 'yes' ) || ( get_option( 'wooac_show_continue_shopping', 'yes' ) === 'yes' ) ) {
									$return_html .= '<div class="wooac-action">';

									if ( ( get_option( 'wooac_show_share_cart', 'yes' ) === 'yes' ) && class_exists( 'WPCleverWpcss' ) ) {
										$return_html .= apply_filters( 'wooac_share', '<a id="wooac-share" class="wpcss-btn" data-hash="' . esc_attr( WC()->cart->get_cart_hash() ) . '" href="' . wc_get_cart_url() . '">' . self::wooac_localization( 'share_cart', esc_html__( 'Share cart', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( get_option( 'wooac_show_view_cart', 'yes' ) === 'yes' ) {
										$return_html .= apply_filters( 'wooac_cart', '<a id="wooac-cart" href="' . wc_get_cart_url() . '">' . self::wooac_localization( 'view_cart', esc_html__( 'View cart', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( get_option( 'wooac_show_checkout', 'no' ) === 'yes' ) {
										$return_html .= apply_filters( 'wooac_checkout', '<a id="wooac-checkout" href="' . wc_get_checkout_url() . '">' . self::wooac_localization( 'checkout', esc_html__( 'Checkout', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									if ( get_option( 'wooac_show_continue_shopping', 'yes' ) === 'yes' ) {
										$return_html .= apply_filters( 'wooac_continue', '<a id="wooac-continue" href="#" data-url="' . get_option( 'wooac_continue_url' ) . '">' . self::wooac_localization( 'continue', esc_html__( 'Continue shopping', 'woo-added-to-cart-notification' ) ) . '</a>' );
									}

									$return_html .= '</div>';
								}

								$return_html .= apply_filters( 'wooac_action_after', '' );
							}
						}
					}

					$return_html .= apply_filters( 'wooac_after', '' );
					$return_html .= '</div>';

					return apply_filters( 'wooac_popup_html', $return_html );
				}

				function wooac_add_to_cart( $cart_item_key ) {
					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['woosb_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['woobt_parent_id'] ) || isset( WC()->cart->cart_contents[ $cart_item_key ]['woofs_parent_id'] ) ) {
						// prevent bundled products and composite products
						WC()->cart->cart_contents[ $cart_item_key ]['wooac_time'] = time() - 10000;
					} else {
						WC()->cart->cart_contents[ $cart_item_key ]['wooac_time'] = time();
					}
				}

				function wooac_cart_fragment( $fragments ) {
					$style = get_option( 'wooac_style', 'default' );

					switch ( $style ) {
						case 'notiny':
							$fragments['.wooac-notiny'] = $this->wooac_get_notiny();
							break;
						default:
							$fragments['.wooac-popup'] = $this->wooac_get_popup();
					}

					return $fragments;
				}

				function wooac_footer() {
					if ( is_admin() ) {
						return;
					}

					$style = get_option( 'wooac_style', 'default' );

					switch ( $style ) {
						case 'notiny':
							echo '<div class="wooac-notiny"></div>';
							break;
						default:
							echo '<div class="wooac-popup mfp-with-anim"></div>';
					}

					if ( isset( $_POST['add-to-cart'] ) || isset( $_GET['add-to-cart'] ) ) {
						?>
                        <script>
                          jQuery(document).ready(function() {
                            jQuery('body').on('wc_fragments_refreshed', function() {
                              wooac_show();
                            });
                          });
                        </script>
						<?php
					}
				}
			}

			new WPCleverWooac();
		}
	}
} else {
	add_action( 'admin_notices', 'wooac_notice_premium' );
}

if ( ! function_exists( 'wooac_notice_wc' ) ) {
	function wooac_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Added To Cart Notification</strong> requires WooCommerce version 3.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'wooac_notice_premium' ) ) {
	function wooac_notice_premium() {
		?>
        <div class="error">
            <p>Seems you're using both free and premium version of <strong>WPC Added To Cart Notification</strong>.
                Please deactivate the free version when using the premium version.</p>
        </div>
		<?php
	}
}
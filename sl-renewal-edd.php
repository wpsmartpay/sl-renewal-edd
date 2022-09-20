<?php

/**
 * Plugin Name: Easy Digital Downloads - Software License Renewal
 * Description: Easy way to renew license for the product on Easy Digital Downloads.
 * Plugin URI:  https://wpsmartpay.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Tags: edd, edd-licensing, edd-software-licensing, edd-software-license-renewal
 * Version:     1.0.0
 * Author:      WPSmartPay
 * Author URI:  https://wpsmartpay.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Text Domain: edd-sl-renewal
 *
 */

defined('ABSPATH') || exit;

// show the renewal link on the purchase history page
add_action('edd_purchase_history_header_after', 'sl_renewal_edd_add_renewal_action_header_purchase_history_page', 11,
    2);
add_action('edd_order_history_row_end', 'sl_renewal_edd_add_renewal_action_link_purchase_history_page', 11, 2);


function sl_renewal_edd_add_renewal_action_header_purchase_history_page(): void
{
    echo '<th class="edd_license_key">' . __( 'Actions', 'edd-sl-renewal' ) . '</th>';
}

function sl_renewal_edd_add_renewal_action_link_purchase_history_page($order): void
{
    if (!function_exists('edd_software_licensing')) return;
    $licensing = edd_software_licensing();
    $licenses  = apply_filters( 'edd_sl_licenses_of_purchase', $licensing->get_licenses_of_purchase( $order->id));

    if (empty($licenses)) {
        return;
    }

    echo '<td class="edd_license_key">';
    foreach ($licenses as $license):?>
        <a href="<?php echo $license->get_renewal_url(); ?>" class="btn btn-primary"
            <?php echo count($licenses) > 1 ? 'style="display:block; margin-top:10px;"' : ''; ?>
           data-license-id="<?php echo
           $license->ID; ?>" data-nonce="<?php echo wp_create_nonce( 'edd_renew_license_nonce' ); ?>">
            <?php esc_html_e( 'Renew', 'edd-sl-renewal' ); ?> -#
            <?php echo esc_html($licensing->get_license_download_display_name($license)) ?>
        </a>
    <?php endforeach; ?>
    <?php
    echo '</td>';
}

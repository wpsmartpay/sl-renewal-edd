<?php

/**
 * Plugin Name: Software License Renewal for Easy Digital Downloads
 * Description: Easy way to renew license for the product on Easy Digital Downloads.
 * Plugin URI:  https://wpsmartpay.com/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Tags: edd, edd-licensing, edd-software-licensing, edd-software-license-renewal
 * Version:     1.0.0
 * Author:      WPSmartPay
 * Author URI:  https://wpsmartpay.com/?utm_source=wp-plugins&utm_campaign=author-uri&utm_medium=wp-dash
 * Text Domain: sl-renewal-edd
 *
 */

defined('ABSPATH') || exit;

// show renewal link on the license page
add_action('edd_order_receipt_after_table', 'sl_renewal_edd_show_renewal', 10, 2);


function sl_renewal_edd_show_renewal($order): void
{
    $licensing = edd_software_licensing();
    $licenses  = apply_filters( 'edd_sl_licenses_of_purchase', $licensing->get_licenses_of_purchase( $order->id));

    if (empty($licenses)) {
        return;
    }
    ?>

    <table id="edd_purchase_receipt" class="edd-table">
        <thead>
        <tr>
            <th><strong><?php echo esc_html_x( 'Order', 'heading', 'easy-digital-downloads' ); ?>:</strong></th>
            <th><?php echo esc_html( $order->get_number() ); ?></th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="edd_receipt_payment_status"><strong><?php esc_html_e( 'Order Status', 'easy-digital-downloads' ); ?>:</strong></td>
            <td class="edd_receipt_payment_status <?php echo esc_attr( strtolower( $order->status ) ); ?>"><?php echo esc_html( edd_get_status_label( $order->status ) ); ?></td>
        </tr>
        <tr>
            <td><strong><?php esc_html_e( 'Payment Key', 'easy-digital-downloads' ); ?>:</strong></td>
            <td><?php echo esc_html( $order->payment_key ); ?></td>
        </tr>
        </tbody>
    </table>

    <?php
    foreach ($licenses as $license) {
        echo $licensing->get_license_download_display_name($license);
        ?>
        <tr>
            <td><strong><?php esc_html_e( 'Actions', 'easy-digital-downloads' ); ?>:</strong></td>
            <td>
                <a href="<?php echo $license->get_renewal_url(); ?>" class="btn btn-primary" data-license-id="<?php echo
                $license->ID; ?>" data-nonce="<?php echo wp_create_nonce( 'edd_renew_license_nonce' ); ?>"><?php esc_html_e( 'Renew', 'easy-digital-downloads' ); ?></a>
            </td>
        </tr>
        <?php
    }
}

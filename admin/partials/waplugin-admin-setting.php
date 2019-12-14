<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://waplugin.com/
 * @since      1.0.0
 *
 * @package    Waplugin
 * @subpackage Waplugin/admin/partials
 */
?>

<div class="notice notice-info">
    <h2><?php echo esc_html('Available Shortcode','waplugin');?></h2>
    <p><code>{order_id}</code> <code>{first_name}</code> <code>{last_name}</code> <code>{total}</code> <code>{payment_method}</code> <code>{status}</code> <code>{bank_accounts}</code> <code>{items}</code> <code>{site_name}</code> <code>{phone}</code> <code>{email}</code></p>
</div>
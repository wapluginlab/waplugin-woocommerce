<?php

/**
 * WAPLUGIN Notification
 *
 * @link       https://waplugin.com/
 * @since      1.0.0
 *
 * @package    Waplugin
 * @subpackage Waplugin/includes
 */

/**
 * WAPLUGIN Notification
 *
 * @since      1.0.0
 * @package    Waplugin
 * @subpackage Waplugin/includes
 * @author     WAPLUGIN <waplugin@gmail.com>
 */
class Waplugin_Notif {

    public function replacer($text, $data, $items)
    {
        $banks = '';
        $bacs_info  = get_option( 'woocommerce_bacs_accounts');
        if ($bacs_info !== false) {
            foreach ($bacs_info as $account) {
                $banks.= "-----\n";
                $banks.= $account['bank_name']."\n";
                $banks.= $account['account_number']."\n";
                $banks.= $account['account_name']."\n";
            }
            $banks.= "-----\n";
        }

        $allItem = '';
        foreach ($items as $item) {
            $allItem.= "-----\n";
            $allItem.= $item['name']." "."(".$item['quantity']."x)"."\n";
            $allItem.= str_replace('&nbsp;', ' ', strip_tags(wc_price($item['subtotal'])))."\n";
        }
        $allItem.= "-----\n";

        $shortcodes = [
            '{order_id}',
            '{first_name}',
            '{last_name}',
            '{total}',
            '{payment_method}',
            '{status}',
            '{bank_accounts}',
            '{items}',
            '{site_name}',
            '{phone}',
            '{email}',
        ];

        $values = [
            $data['id'],
            $data['billing']['first_name'],
            $data['billing']['last_name'],
            str_replace('&nbsp;', ' ', strip_tags(wc_price($data['total']))),
            $data['payment_method_title'],
            ucfirst($data['status']),
            $banks,
            $allItem,
            get_bloginfo( 'name' ),
            $data['billing']['phone'],
            $data['billing']['email'],
        ];

        return str_replace($shortcodes, $values, $text);
    }

    public function send($order_id, $api_requestor)
    {
        try {
            $order = new WC_Order( $order_id );
            $data = $order->get_data();
            $getItems = $order->get_items();
            $items = [];
            foreach ($getItems as $item) {
                array_push($items, $item);
            }

            if ($data['status'] == 'on-hold') {
                // New Order
                $text  = get_option( 'waplugin_tab_new_order');
                if (false === $text && empty($text) && $text == '')
                    return;

                // Admin Content
                $adminText  = get_option( 'waplugin_tab_new_order_admin');
                if (false !== $adminText && !empty($adminText) && $adminText != '') {
                    $contentAdmin = $this->replacer($adminText, $data, $items);
                }

                $content = $this->replacer($text, $data, $items);
            } else {
                // Order status has changed
                $text  = get_option( 'waplugin_tab_order_status_changed');
                if (false === $text && empty($text) && $text == '')
                    return;

                $content = $this->replacer($text, $data, $items);
            }

            $api = get_option( 'waplugin_api');
            $account = get_option( 'waplugin_account_id');
            if (false !== $api && false !== $account) {
                // Check Account
                $chkAccount = $api_requestor->get('/account/'.$account, $api, null);
                if ($this->isAvailable($chkAccount)) {
                    // Check Phone Number
                    if ($this->havePhoneNumber($data)) {
                        // Build Phone Number
                        $ph = [
                            'phone' => $data['billing']['phone'],
                            'phone_country' => $data['billing']['country'],
                        ];
                        $buildPhone = $api_requestor->post('/wa/build-phone-number', $api, $ph);
                        if (isset($buildPhone['results']['phone']) && !empty($buildPhone['results']['phone'])) {
                            $sd = [
                                'phone' => $buildPhone['results']['phone'],
                                'msg' => $content,
                            ];
                            $sendMessage = $api_requestor->post('/wa/send-message/'.$account, $api, $sd);
                        }
                    }
                }
                // Send notif to Admin
                if (isset($contentAdmin)) {
                    $waplugin_admin_phone = get_option( 'waplugin_admin_phone' );
                    if (false !== $waplugin_admin_phone) {
                        $asd = [
                            'phone' => $waplugin_admin_phone,
                            'msg' => $contentAdmin,
                        ];
                        $api_requestor->post('/wa/send-message/'.$account, $api, $asd);
                    }
                }
            }
        } catch (\Exception $e) {
            // 
        }
    }

    public function isAvailable($account)
    {
        return ($account['results']['connected'] && $account['results']['active']);
    }

    public function havePhoneNumber($data)
    {
        return (!empty($data['billing']['country']) && !empty($data['billing']['phone']));
    }

}

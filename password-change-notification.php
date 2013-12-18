<?php
/*
Plugin Name: Password Change Notification
Plugin URI: http://dessibelle.se
Description: A WordPress plugin for sending users an email notifications when their password changes
Author: Simon Fransson
Version: 1.0b1
Author URI: http://dessibelle.se
*/


class PasswordChangeNotification {

    protected static $instance;

    protected function initialize() {

        load_plugin_textdomain( 'pwcn', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        add_action('profile_update', array(&$this, 'profile_update'), 10, 2 );
        
        // let's add an extra field to the user edit page to enble or disable notifications
        add_action( 'show_user_profile', array( __CLASS__, 'enable_notification' ), 5 );	
        add_action( 'edit_user_profile', array( __CLASS__, 'enable_notification' ), 5 );
        
    }


    public static function instance() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;

            self::$instance->initialize();
        }
        return self::$instance;
    }

    public function profile_update($user_id, $old_user_data = null)
    {

        $name = apply_filters('pwcn_to_name', implode(" ", array_filter(array($_POST['first_name'], $_POST['last_name']))));
        $email = $to = apply_filters('pwcn_to_email', $_POST['email'], $user_id);;
        $password = $_POST['pass1'];
        
        $send_notification = isset( $_POST['user_notification'] ) && $_POST['user_notification'] == 1? true : false;

        if (!empty($password) && $send_notification == true) {
            $to = $email;
            if (!empty($name) && !empty($to)) {
                $to = sprintf('%s <%s>', $name, $email);
            }
            $to = apply_filters('pwcn_to_header', $to, $user_id);

            $site_url = apply_filters('pwcn_site_url', get_bloginfo('url'), $user_id);
            $login_url = apply_filters('pwcn_login_url', wp_login_url(apply_filters('pwcn_login_url_redirect', null, $user_id)), $user_id);
            $site_name = apply_filters('pwcn_site_name', get_bloginfo('name'), $user_id);
            $host = apply_filters('pwcn_site_hostname', parse_url($site_url, PHP_URL_HOST), $user_id);

            $subject = apply_filters('pwcn_subject', sprintf(__('New password for %s', 'pwcn'), $host), $user_id);
            $message_format = __("Hi %s,\n\nyour password for %s (%s) has changed. The new password is: \n\n%s\n\nTo login, go to %s.", 'pwcn');
            $message = apply_filters('pwcn_message', sprintf($message_format, $_POST['first_name'], $site_name, $site_url, $password, $login_url), $user_id);

            wp_mail( $to, $subject, $message);
        }
    }
    
    static function enable_notification( $user )
    {
        echo '<table class="form-table"><tr>';
        echo '<th scope="row">'.__('Notify user of password change:', 'pwcn').'</th>';
        echo '<td><fieldset><legend class="screen-reader-text"><span>'.__('Notify user of password change:', 'pwcn').'</span></legend>';
        echo '<label for="user_notification">';
        echo '<input name="user_notification" type="checkbox" id="user_notification" value="1" >'.__('Yes', 'pwcn').'</label><br/>';
        echo '</fieldset></td></tr></tbody></table>';
    }

}


function pwcn() {
    return PasswordChangeNotification::instance();
}

$pwcn = pwcn();

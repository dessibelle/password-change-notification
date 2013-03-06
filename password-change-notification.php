<?php
/*
Plugin Name: Password Change Notification
Plugin URI: http://dessibelle.se
Description: A WordPress plugin for sending email notifications when passwords change
Author: Simon Fransson
Version: 1.0
Author URI: http://dessibelle.se
*/


class PasswordChangeNotification {

    protected static $instance;

    protected function initialize() {

        load_plugin_textdomain( 'pwcn', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

        add_action('profile_update', array(&$this, 'profile_update'), 10, 2 );
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

        $name = implode(" ", array_filter(array($_POST['first_name'], $_POST['last_name'])));
        $email = $_POST['email'];
        $password = $_POST['pass1'];

        if (!empty($password)) {
            $to = $email;
            if (!empty($name) && !empty($to)) {
                $to = sprintf('%s <%s>', $name, $email);
            }

            $site_url = get_bloginfo('home');
            $login_url = wp_login_url();
            $site_name = get_bloginfo('name');
            $host = parse_url($site_url, PHP_URL_HOST);

            $subject = sprintf(__('New password for %s', 'pwcn'), $host);
            $message_format = __("Hi %s,\n\nyour password for %s (%s) has changed. The new password is: \n\n%s\n\nTo login, go to %s.", 'pwcn');
            $message = sprintf($message_format, $_POST['first_name'], $site_name, $site_url, $password, $login_url);

            wp_mail( $to, $subject, $message);
        }
    }

}


function pwcn() {
    return PasswordChangeNotification::instance();
}

$pwcn = pwcn();

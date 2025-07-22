<?php 
class MTMEmail_send{
    public function __construct(){

            add_action('wp_ajax_submit_member_contact', array($this,'handle_ajax_member_contact'));
           add_action('wp_ajax_nopriv_submit_member_contact', array($this,'handle_ajax_member_contact'));

    }

public function handle_ajax_member_contact() {
    // Verify nonce
    if (!isset($_POST['member_contact_nonce']) || !wp_verify_nonce($_POST['member_contact_nonce'], 'submit_member_contact')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    // Sanitize input
    $member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
    $full_name = sanitize_text_field($_POST['full_name']);
    $email = sanitize_email($_POST['email']);
    $message = sanitize_textarea_field($_POST['message']);

    // Prepare email
    $to = get_post_meta($member_id, '_email', true);
    $subject = "New message from member #{$full_name}";
    $body = "Name: {$full_name}\nEmail: {$email}\n\nMessage:\n{$message}";
    $headers = ['Content-Type: text/plain; charset=UTF-8', "Reply-To: {$full_name} <{$email}>"];

    // Send email
    if (wp_mail($to, $subject, $body, $headers)) {
        $submissions = get_post_meta($member_id, '_contact_submissions', true);
        update_post_meta($member_id, '_contact_submissions', (int)$submissions + 1);

        wp_send_json_success(['message' => 'Your message has been sent successfully!']);
    } else {
        wp_send_json_error(['message' => 'Something went wrong. Please try again.']);
    }
}

}
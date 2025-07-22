<?php 
class MTMAssets{
    public function __construct(){
         // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }

     // Enqueue admin scripts and styles
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if (($post_type == 'member' || $post_type == 'team') && ($hook == 'post-new.php' || $hook == 'post.php')) {
            wp_enqueue_media();
            wp_enqueue_style('members-teams-admin', MTM_PLUGIN_URL.'assets/css/admin.css');
            wp_enqueue_script('members-teams-admin', MTM_PLUGIN_URL.'assets/js/admin.js', array('jquery'), '1.0', true);
        }
    }
    
    // Enqueue frontend scripts and styles
    public function enqueue_frontend_scripts() {
        wp_enqueue_style('members-teams-frontend', MTM_PLUGIN_URL.'assets/css/frontend.css');
        wp_enqueue_script('mtm-frontend-script', MTM_PLUGIN_URL.'assets/js/frontend.js', array('jquery'), '1.0', true);
        wp_localize_script('mtm-frontend-script', 'memberContactAjax', [
        'mtm_ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('submit_member_contact')
    ]);
    }

}
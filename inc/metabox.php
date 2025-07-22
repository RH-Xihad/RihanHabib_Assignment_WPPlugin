<?php
class MTMCreate_mteabox{
    public function __construct(){

          // Add custom fields
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_member_meta'));
        add_action('save_post', array($this, 'save_team_meta'));
    }



// Add meta boxes
    public function add_meta_boxes() {
        // Member meta boxes
        add_meta_box(
            'member_details',
            'Member Details',
            array($this, 'render_member_meta_box'),
            'member',
            'normal',
            'high'
        );
        
        // Team meta box (for short description)
        add_meta_box(
            'team_details',
            'Team Details',
            array($this, 'render_team_meta_box'),
            'team',
            'normal',
            'high'
        );
    }
    
    // Render member meta box
    public function render_member_meta_box($post) {
        wp_nonce_field('save_member_data', 'member_nonce');
        
        // Get existing values
        $first_name = get_post_meta($post->ID, '_first_name', true);
        $last_name = get_post_meta($post->ID, '_last_name', true);
        $email = get_post_meta($post->ID, '_email', true);
        $profile_image = get_post_meta($post->ID, '_profile_image', true);
        $cover_image = get_post_meta($post->ID, '_cover_image', true);
        $address = get_post_meta($post->ID, '_address', true);
        $favorite_color = get_post_meta($post->ID, '_favorite_color', true);
        $status = get_post_meta($post->ID, '_status', true);
        $teams = get_post_meta($post->ID, '_teams', true);
        if (!is_array($teams)) {
            $teams = array();
        }
        
        // Get all teams for the select box
        $all_teams = get_posts(array(
            'post_type' => 'team',
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        ?>
        <div class="member-fields">
            <div class="field-row">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($first_name); ?>" required>
            </div>
            
            <div class="field-row">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($last_name); ?>" required>
            </div>
            
            <div class="field-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo esc_attr($email); ?>" required>
            </div>
            
            <div class="field-row">
                <label for="profile_image">Profile Image:</label>
                <input type="text" id="profile_image" name="profile_image" value="<?php echo esc_attr($profile_image); ?>">
                <button class="upload-image-button button" data-target="profile_image">Upload Image</button>
                <?php if ($profile_image) : ?>
                    <div class="image-preview">
                        <img src="<?php echo esc_url($profile_image); ?>" style="max-width: 150px; height: auto;">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="field-row">
                <label for="cover_image">Cover Image:</label>
                <input type="text" id="cover_image" name="cover_image" value="<?php echo esc_attr($cover_image); ?>">
                <button class="upload-image-button button" data-target="cover_image">Upload Image</button>
                <?php if ($cover_image) : ?>
                    <div class="image-preview">
                        <img src="<?php echo esc_url($cover_image); ?>" style="max-width: 150px; height: auto;">
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="field-row">
                <label for="address">Address:</label>
                <textarea id="address" name="address"><?php echo esc_textarea($address); ?></textarea>
            </div>
            
            <div class="field-row">
                <label for="favorite_color">Favorite Color:</label>
                <input type="color" id="favorite_color" name="favorite_color" value="<?php echo esc_attr($favorite_color ? $favorite_color : '#ffffff'); ?>">
            </div>
            
            <div class="field-row">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="active" <?php selected($status, 'active'); ?>>Active</option>
                    <option value="draft" <?php selected($status, 'draft'); ?>>Draft</option>
                </select>
            </div>
            
            <div class="field-row">
                <label>Teams:</label>
                <div class="teams-checkboxes">
                    <?php foreach ($all_teams as $team) : ?>
                        <label>
                            <input type="checkbox" name="teams[]" value="<?php echo $team->ID; ?>" <?php checked(in_array($team->ID, $teams)); ?>>
                            <?php echo esc_html($team->post_title); ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    // Render team meta box
    public function render_team_meta_box($post) {
        wp_nonce_field('save_team_data', 'team_nonce');
        
        $short_description = get_post_meta($post->ID, '_short_description', true);
        
        ?>
        <div class="team-fields">
            <div class="field-row">
                <label for="short_description">Short Description:</label>
                <textarea id="short_description" name="short_description"><?php echo esc_textarea($short_description); ?></textarea>
            </div>
        </div>
        <?php
    }
    
    // Save member meta data
    public function save_member_meta($post_id) {
        if (!isset($_POST['member_nonce']) || !wp_verify_nonce($_POST['member_nonce'], 'save_member_data')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if ('member' !== $_POST['post_type']) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save all fields
        $fields = array(
            'first_name',
            'last_name',
            'email',
            'profile_image',
            'cover_image',
            'address',
            'favorite_color',
            'status'
        );
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Save teams
        $teams = isset($_POST['teams']) ? array_map('intval', $_POST['teams']) : array();
        update_post_meta($post_id, '_teams', $teams);
        
        // Update post title to first + last name
        $first_name = get_post_meta($post_id, '_first_name', true);
        $last_name = get_post_meta($post_id, '_last_name', true);
        $post_title = $first_name . ' ' . $last_name;
        
        // Unhook this function to prevent infinite loop
        remove_action('save_post', array($this, 'save_member_meta'));
        
        // Update the post
        wp_update_post(array(
            'ID' => $post_id,
            'post_title' => $post_title
        ));
        
        // Re-hook this function
        add_action('save_post', array($this, 'save_member_meta'));
    }
    
    // Save team meta data
    public function save_team_meta($post_id) {
        if (!isset($_POST['team_nonce']) || !wp_verify_nonce($_POST['team_nonce'], 'save_team_data')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if ('team' !== $_POST['post_type']) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (isset($_POST['short_description'])) {
            update_post_meta($post_id, '_short_description', sanitize_text_field($_POST['short_description']));
        }
    }






}
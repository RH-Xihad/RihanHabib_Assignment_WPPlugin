<?php
class MTMTeam_member_single{
    
    public function __construct(){
         add_action('template_include', array($this, 'single_member_template'));
    }


 // Single member template
    public function single_member_template($template) {
        if (get_query_var('member_name')) {
            $member_name = get_query_var('member_name');
            $members = get_posts(array(
                'post_type' => 'member',
                'meta_query' => array(
                    array(
                        'key' => '_status',
                        'value' => 'active',
                        'compare' => '='
                    )
                )
            ));
            
            foreach ($members as $member) {
                $first_name = get_post_meta($member->ID, '_first_name', true);
                $last_name = get_post_meta($member->ID, '_last_name', true);
                $current_name = sanitize_title($first_name . '_' . $last_name);
                
                if ($current_name === $member_name) {
                    // Found our member
                    $this->display_single_member($member);
                    exit;
                }
            }
            
            // Member not found or not active
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404);
            exit;
        }
        
        return $template;
    }
    
    // Display single member page
    public function display_single_member($member) {
        $member_id = $member->ID;
        $first_name = get_post_meta($member_id, '_first_name', true);
        $last_name = get_post_meta($member_id, '_last_name', true);
        $email = get_post_meta($member_id, '_email', true);
        $profile_image = get_post_meta($member_id, '_profile_image', true);
        $cover_image = get_post_meta($member_id, '_cover_image', true);
        $address = get_post_meta($member_id, '_address', true);
        $favorite_color = get_post_meta($member_id, '_favorite_color', true);
        $teams = get_post_meta($member_id, '_teams', true);
    
        
        get_header();
        ?>
        <div class="member-single">
            <?php if ($cover_image) : ?>
                <div class="member-cover" style="background-color: <?php echo esc_attr($favorite_color); ?>;">
                    <img src="<?php echo esc_url($cover_image); ?>" alt="<?php echo esc_attr($first_name . ' ' . $last_name); ?>">
                </div>
            <?php endif; ?>
            
            <div class="member-content">
                <div class="member-profile">
                    <?php if ($profile_image) : ?>
                        <div class="member-image">
                            <img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo esc_attr($first_name . ' ' . $last_name); ?>">
                        </div>
                    <?php endif; ?>
                    
                    <h1><?php echo esc_html($first_name . ' ' . $last_name); ?></h1>
                    
                    <div class="member-meta">
                        <p class="member-email"><?php echo esc_html($email); ?></p>
                        
                        <?php if ($address) : ?>
                            <p class="member-address"><?php echo nl2br(esc_html($address)); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($teams) && is_array($teams)) : ?>
                            <div class="member-teams">
                                <h3>Teams</h3>
                                <ul>
                                    <?php foreach ($teams as $team_id) : 
                                        $team = get_post($team_id);
                                        if ($team) : ?>
                                            <li><a href="<?php echo get_permalink($team_id); ?>"><?php echo esc_html($team->post_title); ?></a></li>
                                        <?php endif;
                                    endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div>
                            <p class="favourite-coor"><strong>Favourite color: </strong><?php echo esc_html($favorite_color); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="member-contact">
                    <h2>Contact <?php echo esc_html($first_name); ?></h2>
                        <form method="post" class="member-contact-form">
                        <input type="hidden" name="member_id" value="<?php echo esc_attr($member_id); ?>">
                        <input type="hidden" name="member_contact_nonce" value="<?php echo wp_create_nonce('submit_member_contact'); ?>">

                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" name="member_contact_submit" class="submit-button">Send Message</button>
                    </form>

                </div>
            </div>
        </div>
        <?php
        get_footer();
    }

}
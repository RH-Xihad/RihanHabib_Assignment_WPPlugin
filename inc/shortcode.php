<?php

class MTMDisplay_team_member{

    public function __construct(){
       // Register shortcodes
        add_shortcode('display_members', array($this, 'display_members_shortcode'));
    }


     // Display members shortcode
    public function display_members_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => -1,
            'team' => ''
        ), $atts);
        
        $args = array(
            'post_type' => 'member',
            'posts_per_page' => intval($atts['limit']),
            'meta_query' => array(
                array(
                    'key' => '_status',
                    'value' => 'active',
                    'compare' => '='
                )
            )
        );
        
        if (!empty($atts['team'])) {
            $args['meta_query'][] = array(
                'key' => '_teams',
                'value' => sanitize_text_field($atts['team']),
                'compare' => 'LIKE'
            );
        }
        
        $members = get_posts($args);
        
        if (empty($members)) {
            return '<p>No members found.</p>';
        }
        
        ob_start();
        echo '<div class="members-list">';
        foreach ($members as $member) {
            $member_id = $member->ID;
            $first_name = get_post_meta($member_id, '_first_name', true);
            $last_name = get_post_meta($member_id, '_last_name', true);
            $email = get_post_meta($member_id, '_email', true);
            $profile_image = get_post_meta($member_id, '_profile_image', true);
            $teams = get_post_meta($member_id, '_teams', true);
            
            echo '<div class="member-item">';
            
            if ($profile_image) {
                echo '<div class="member-profile-image">';
                echo '<img src="' . esc_url($profile_image) . '" alt="' . esc_attr($first_name . ' ' . $last_name) . '">';
                echo '</div>';
            }
            
            echo '<div class="member-details">';
            echo '<h3><a href="' . home_url('/' . sanitize_title($first_name . '_' . $last_name)) . '">' . esc_html($first_name . ' ' . $last_name) . '</a></h3>';
            echo '<p class="member-email">' . esc_html($email) . '</p>';
            
            if (!empty($teams) && is_array($teams)) {
                echo '<div class="member-teams">';
                echo '<strong>Teams:</strong> ';
                $team_links = array();
                foreach ($teams as $team_id) {
                    $team = get_post($team_id);
                    if ($team) {
                        $team_links[] = '<a href="' . get_permalink($team_id) . '">' . esc_html($team->post_title) . '</a>';
                    }
                }
                echo implode(', ', $team_links);
                echo '</div>';
            }
            
            echo '<a href="' . home_url('/' . sanitize_title($first_name . '_' . $last_name)) . '" class="view-profile">View Profile</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        
        return ob_get_clean();
    }

}

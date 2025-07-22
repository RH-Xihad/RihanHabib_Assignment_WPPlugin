<?php
class MTMCreate_post_column{

    public function __construct(){

         // Add custom columns
        add_filter('manage_member_posts_columns', array($this, 'add_member_custom_columns'));
        add_action('manage_member_posts_custom_column', array($this, 'fill_member_custom_columns'), 10, 2);

        // Add custom columns for teams
        add_filter('manage_team_posts_columns', array($this, 'add_team_custom_columns'));
        add_action('manage_team_posts_custom_column', array($this, 'fill_team_custom_columns'), 10, 2);

    }

      
    // Add custom columns to members list
public function add_member_custom_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => __('Name'),
        'email' => __('Email'),
        'profile_image' => __('Profile Image'),
        'status' => __('Status'),
        'teams' => __('Teams'),
        'submissions' => __('Contact Form Submission Count'),
        'date' => $columns['date']
    );
    return $new_columns;
}

// Fill custom columns for members
public function fill_member_custom_columns($column, $post_id) {
    switch ($column) {
        case 'email':
            echo esc_html(get_post_meta($post_id, '_email', true));
            break;
            
        case 'profile_image':
            $profile_image = get_post_meta($post_id, '_profile_image', true);
            if ($profile_image) {
                echo '<img src="' . esc_url($profile_image) . '" style="max-width: 50px; height: auto;">';
            } else {
                echo '—';
            }
            break;
            
        case 'status':
            $status = get_post_meta($post_id, '_status', true);
            echo ucfirst(esc_html($status));
            break;
            
        case 'teams':
            $teams = get_post_meta($post_id, '_teams', true);
            if (!empty($teams) && is_array($teams)) {
                $team_names = array();
                foreach ($teams as $team_id) {
                    $team = get_post($team_id);
                    if ($team) {
                        $team_names[] = $team->post_title;
                    }
                }
                echo esc_html(implode(', ', $team_names));
            } else {
                echo '—';
            }
            break;
            
        case 'submissions':
            $submissions = get_post_meta($post_id, '_contact_submissions', true);
            $count = $submissions ? $submissions : 0;
            echo $count;
            break;
    }
}



// Add custom columns to teams list
public function add_team_custom_columns($columns) {
    $new_columns = array(
        'cb' => $columns['cb'],
        'title' => __('Name'),
        'short_description' => __('Description'),
        'member_count' => __('Members'),
        'date' => $columns['date']
    );
    return $new_columns;
}

// Fill custom columns for teams
public function fill_team_custom_columns($column, $post_id) {
    switch ($column) {
        case 'short_description':
            $desc = get_post_meta($post_id, '_short_description', true);
            echo $desc ? esc_html($desc) : '—';
            break;
            
        case 'member_count':
            $members = get_posts(array(
                'post_type' => 'member',
                'meta_query' => array(
                    array(
                        'key' => '_teams',
                        'value' => $post_id,
                        'compare' => 'LIKE'
                    ),
                    array(
                        'key' => '_status',
                        'value' => 'active',
                        'compare' => '='
                    )
                ),
                'posts_per_page' => -1
            ));
            echo count($members);
            break;
    }
}

}
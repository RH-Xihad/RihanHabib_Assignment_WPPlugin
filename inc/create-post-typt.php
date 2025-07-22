<?php
// Register custom post types
class MTMCreate_post_type{
     public function __construct(){
        // Hook the registration function to 'init'
        add_action('init', [$this, 'register_custom_post_types']);
     }
    public function register_custom_post_types() {
        // Member post type
        register_post_type('member',
            array(
                'labels' => array(
                    'name' => __('Members'),
                    'singular_name' => __('Member'),
                    'add_new' => __('Add New Member'), // Changes the "Add New" text
                    'add_new_item' => __('Add New Member'), // Changes the "Add New Post" text in the post creation screen
                    'edit_item' => __('Edit Member'),
                    'new_item' => __('New Member'),
                    'view_item' => __('View Member'),
                    'search_items' => __('Search Members'),
                    'not_found' => __('No members found'),
                    'not_found_in_trash' => __('No members found in Trash')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'members'),
                'supports' => array('title'),
                'menu_icon' => 'dashicons-groups',
            )
        );
        
       // Team post type
        register_post_type('team',
            array(
                'labels' => array(
                    'name' => __('Teams'),
                    'singular_name' => __('Team'),
                    'add_new' => __('Add New Team'), // Changes "Add New" in the admin menu
                    'add_new_item' => __('Add New Team'), // Changes "Add New Post" in the post editor
                    'edit_item' => __('Edit Team'),
                    'new_item' => __('New Team'),
                    'view_item' => __('View Team'),
                    'search_items' => __('Search Teams'),
                    'not_found' => __('No teams found'),
                    'not_found_in_trash' => __('No teams found in Trash'),
                    'all_items' => __('All Teams'), // Changes "All Posts" in the admin menu
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'teams'),
                'supports' => array('title', 'editor'),
                'menu_icon' => 'dashicons-networking',
            )
        );
    }
}
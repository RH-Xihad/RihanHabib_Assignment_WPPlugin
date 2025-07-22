<?php
/*
Plugin Name: Members and Teams Manager
Description: A plugin to manage members, teams, and their relationships with contact form functionality.
Version: 1.0
Author: Rihan Habib
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('MTM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MTM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include files
require_once MTM_PLUGIN_DIR . "inc/functions.php";
require_once MTM_PLUGIN_DIR . "inc/create-post-typt.php";
require_once MTM_PLUGIN_DIR . "inc/metabox.php";
require_once MTM_PLUGIN_DIR . "inc/shortcode.php";
require_once MTM_PLUGIN_DIR . "inc/create-post-column.php";
require_once MTM_PLUGIN_DIR . "inc/team-member-single.php";
require_once MTM_PLUGIN_DIR . "inc/ajax.php";
require_once MTM_PLUGIN_DIR . "inc/assets.php";


class MembersTeamsManager {

    //public $post_type_creator;
    public function __construct() {
        //Core functions execute
        new MTMCore();
        // Load assets
        new MTMAssets();
        // Register post type
        new MTMCreate_post_type();
        //Register Metaboxes
        new MTMCreate_mteabox();
        //Generate shortcode
        new MTMDisplay_team_member();
        //Generate post list columns
        new MTMCreate_post_column();
       //Member single page
       new MTMTeam_member_single();
       //Email send
       new MTMEmail_send();   
    }
    
}

// Initialize the plugin
new MembersTeamsManager();
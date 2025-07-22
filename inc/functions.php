<?php
class MTMCore{
    public function __construct(){
        // Rewrite rules for single member page
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
    }

     // Add rewrite rules for single member page
    public function add_rewrite_rules() {
        add_rewrite_rule(
            '^([^/]+)_([^/]+)/?$',
            'index.php?member_name=$matches[1]_$matches[2]',
            'top'
        );
    }
    
    // Add custom query var
    public function add_query_vars($vars) {
        $vars[] = 'member_name';
        return $vars;
    }
}
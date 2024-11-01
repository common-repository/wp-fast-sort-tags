<?php

/*
Plugin Name: WP Fast Sort Tags
Version: 1.0
Plugin URI: Plugin URL goes here (e.g. http://yoursite.com/wordpress-plugins/plugin/)
Description: This plugin creates a page that sort your posts by tags. 
Author: Telecom Bretagne
Author URI: http://www.coyotte508.com
*/

class WPFastSortTags {
    var $version;
	
    /* Constructor */
    function WPFastSortTags() {
        $this->version =  '1.0.0';
        $this->installed = get_option('wpfst_version');

        /*
         * We specify all the hooks and filters for the plugin
        */
        register_activation_hook(__FILE__, array(&$this,'activate'));
        register_deactivation_hook(__FILE__, array(&$this,'deactivate'));

        /*
         * Admin menu.
        */
        add_action('admin_menu', array(&$this, 'adminMenu'));

        /*
         * To execute php in the display page
        */
        add_action('the_content', array(&$this, 'filterContent'));
		
    }

    /**
     * Adds the WP Fast Sort Tags item to menu.
     *
     */
    function adminMenu() {
        add_options_page("WP Fast Sort Tags", "WP Fast Sort Tags" , 8, __FILE__, array(&$this, 'admin'));
    }

    /*
     * The admin menu that's displayed.
     * The display of the menu occurs in display.php
     */
    function admin() {
        /* We process the menu and then display it */
        if (isset($_REQUEST['create_display_page'])) {
			if (isset ($_REQUEST['page_title']) && $_REQUEST['page_title']) {
			update_option('wpfst_pagetitle', $_REQUEST['page_title']);
			}
            $page_created = $this->createDisplayPage();
        } else if (isset ($_REQUEST['delete_display_page'])) {
            $page_deleted = $this->deleteDisplayPage();
			update_option('wpfst_pagetitle', 'Posts by Tags');

        } 

        include('display.php');

		return true;
	}
	
    /*
     * Tests if the display page is already created
    */
    function displayPageExists() {
        $pageid = get_option("wpfst_display_page");

        return get_post_type($pageid) == 'page' && get_page($pageid)->post_status == 'publish';
    }


    function createDisplayPage() {
        if ($this->displayPageExists())
            return false;

        if (!current_user_can('publish_pages')) {
            return false;
        }

        $post_args = array();

        $post_args['post_type'] = 'page';
        $post_args['post_status'] = 'publish';
        $post_args['post_title'] = get_option('wpfst_pagetitle');;
        $post_args['post_content'] = '[Content modified by WP Fast Sort on display]';

        $id = wp_insert_post($post_args);

        if (!$id)
            return false;

        /* So we know later if its the display by alphabetical order page */
        update_option("wpfst_display_page", $id);
        return true;
    }

    /*
     * Delete the page that displays tags by lexical order
    */
    function deleteDisplayPage() {
        if (!$this->displayPageExists())
            return false;

        /* The id of the display page */
        $pageid = get_option("wpfst_display_page");

        $page_deleted = current_user_can('delete_page', $pageid) &&wp_delete_post($pageid);
    }
	
    /**
     * To execute the display page
     *
     * @param string the content to change
     */
    function filterContent($content) {
        if (get_the_ID()) {
            /* Checking if it's the right page */
            if (!is_page() || get_option("wpfst_display_page") != get_the_ID())
                return $content;

            /* It's the right page, so we display its custom php code */
            $filename = "display-page.php";

            /* The custom php file is included, its output is captured
            * and then displayed.
            */
            ob_start();
            include $filename;
            $content = ob_get_contents();
            ob_end_clean();
            return $content;

        }
        return $content;
    }

    

    #Called at the activation of the plugin
    function activate() {
        global $wpdb;

        // only re-install if new version or uninstalled
        if(! $this->installed || $this->installed != $this->version) {
            /* use dbDelta() to create tables */
            add_option('wpfst_version', $this->version);

            $this->installed = true;
if (!get_option('wpfst_pagetitle')) {
            add_option('wpfs_pagetitle', "Posts by Tags");
        }
        }
    }

    #Called at the deactivation of the plugin
    function deactivate() {
    }
}

$wpfastsorttags = & new WPFastSortTags();

?>
<?php
class cnr_performanceSettings {

    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page() {
        // This page will be under "Settings"
        add_options_page(
            'CNR Performance', 
            'CNR Performance', 
            'manage_options', 
            'cnr-performance-settings', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page() {
        $this->options = get_option( 'cnr_performance' );
        ?>
        <div class="wrap">
            <h2 style="max-width: 600px; text-align: center; margin-bottom: 15px;">Code n' Roll Performance</h2>           
            <form method="post" action="options.php">
            <?php
                settings_fields( 'cnr_performance_group' );   
                do_settings_sections( 'cnr-performance-settings' );
                submit_button(); 
            ?>
            <p style="text-align: center;">Born to be wild.</p>
            </form>
        </div>
        <?php
    }

    public function page_init() {        
        register_setting(
            'cnr_performance_group',
            'cnr_performance',
            array( $this, 'sanitize' )
        );
		
		// sections
		
        add_settings_section(
            'core_wp',
            'Core',
            array( $this, 'print_section_info' ),
            'cnr-performance-settings'
        );
        
        add_settings_section(
            'compress',
            'Compress',
            array( $this, 'print_section_info' ),
            'cnr-performance-settings'
        );
        
        add_settings_section(
            'cache',
            'Cache (beta)',
            array( $this, 'print_section_info' ),
            'cnr-performance-settings'
        );
        
		// core fields
		
		add_settings_field(
            'remove_wp_generator', 
            'Remove WordPress generator meta tag', 
            array( $this, 'remove_wp_generator_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        
        if ( class_exists( 'WooCommerce' ) ) {
	        add_settings_field(
	            'remove_wc_generator',
	            'Remove WooCommerce generator meta tag',
	            array( $this, 'remove_wc_generator_callback' ),
	            'cnr-performance-settings'
	        );
        }
        if ( class_exists( 'visual_composer' ) ) {
	        add_settings_field(
	            'remove_vc_generator',
	            'Remove Visual Composer generator meta tag',
	            array( $this, 'remove_vc_generator_callback' ),
	            'cnr-performance-settings'
	        );
        }
        
        add_settings_field(
            'remove_wp_shortlink', 
            'Remove WordPress shortlink for defined pages', 
            array( $this, 'remove_wp_shortlink_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        
        add_settings_field(
            'remove_feed_links_extra', 
            'Remove comments feed links', 
            array( $this, 'remove_feed_links_extra_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        
        add_settings_field(
            'remove_feed_link', 
            'Remove feed links', 
            array( $this, 'remove_feed_links_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        
        add_settings_field(
            'remove_scripts_ver', 
            'Remove version parameter from scripts & styles', 
            array( $this, 'remove_scripts_ver_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        add_settings_field(
            'remove_rss_link', 
            'Remove the support of RSS from the head', 
            array( $this, 'remove_rss_link_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        add_settings_field(
            'remove_rsd_link', 
            'Remove the support of RSD from the head', 
            array( $this, 'remove_rsd_link_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        add_settings_field(
            'disable_wlwmanifest',
            'Remove the xml file to support Windows Live Writer',
            array( $this, 'disable_wlwmanifest_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        );
        add_settings_field(
            'disable_wp_toolbar', 
            'Disable WordPress toolbar from the front-end', 
            array( $this, 'disable_wp_toolbar_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        
        add_settings_field(
            'disable_wp_oembed', 
            'Disable WordPress oEmbed options', 
            array( $this, 'disable_wp_oembed_callback' ), 
            'cnr-performance-settings', 
            'core_wp'
        );
        add_settings_field(
            'disable_emoji',
            'Disable Emoji Support',
            array( $this, 'disable_emoji_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        );
        add_settings_field(
            'disable_xmlrpc',
            'Disable XML-RPC',
            array( $this, 'disable_xmlrpc_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        );
        add_settings_field(
            'disable_restapi',
            'Disable REST API',
            array( $this, 'disable_restapi_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        );
        add_settings_field(
            'disable_heartbeat',
            'Disable Heartbeat API',
            array( $this, 'disable_heartbeat_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        ); 
        add_settings_field(
            'disable_pingback',
            'Disable pingbacks',
            array( $this, 'disable_pingback_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        ); 
        
        add_settings_field(
            'reduce_heartbeat',
            'Reduce Heartbeat API (you can choose between 15 to 60 seconds)',
            array( $this, 'reduce_heartbeat_callback' ),
            'cnr-performance-settings',
            'core_wp'        
        );  
        
        // cache fields
        
        add_settings_field(
            'preserve_cache', 
            'Preserve .htaccess cache', 
            array( $this, 'preserve_cache_callback' ), 
            'cnr-performance-settings', 
            'cache'
        );
        
        // compress fields
        
        add_settings_field(
            'compress_html', 
            'Compress html output', 
            array( $this, 'compress_html_callback' ), 
            'cnr-performance-settings', 
            'compress'
        );
        add_settings_field(
            'compress_html_display', 
            'Display compression comment with the reduced size at the bottom of the document', 
            array( $this, 'compress_html_display_callback' ), 
            'cnr-performance-settings', 
            'compress'
        );
          
    }

    public function sanitize( $input ) {
        $new_input = array();
        
        // disable emoji
        if( isset( $input['disable_emoji'] ) ) $new_input['disable_emoji'] = absint( $input['disable_emoji'] );
        
        // disable wlwmanifest
        if( isset( $input['disable_wlwmanifest'] ) ) $new_input['disable_wlwmanifest'] = absint( $input['disable_wlwmanifest'] );
        
        // disable oembed
        if( isset( $input['disable_wp_oembed'] ) ) $new_input['disable_wp_oembed'] = absint( $input['disable_wp_oembed'] );
        
        // disable xml-rpc
        if( isset( $input['disable_xmlrpc'] ) ) $new_input['disable_xmlrpc'] = absint( $input['disable_xmlrpc'] );
        
        // disable rest api
        if( isset( $input['disable_restapi'] ) ) $new_input['disable_restapi'] = absint( $input['disable_restapi'] );
        
        // disable heartbeat
        if( isset( $input['disable_heartbeat'] ) ) $new_input['disable_heartbeat'] = absint( $input['disable_heartbeat'] );
        
        // disable pingback
        if( isset( $input['disable_pingback'] ) ) $new_input['disable_pingback'] = absint( $input['disable_pingback'] );
        
        // reduce heartbeat
        if( isset( $input['reduce_heartbeat'] ) ) $new_input['reduce_heartbeat'] = absint( $input['reduce_heartbeat'] );
        
        // remove wp meta generator
        if( isset( $input['remove_wp_generator'] ) ) $new_input['remove_wp_generator'] = sanitize_text_field( $input['remove_wp_generator'] );
        
        // remove woocomerce meta generator
        if( isset( $input['remove_wc_generator'] ) ) $new_input['remove_wc_generator'] = sanitize_text_field( $input['remove_wc_generator'] );
        
        // remove visual composer meta generator
        if( isset( $input['remove_vc_generator'] ) ) $new_input['remove_vc_generator'] = sanitize_text_field( $input['remove_vc_generator'] );
        
        // remove wp shortlink
        if( isset( $input['remove_wp_shortlink'] ) ) $new_input['remove_wp_shortlink'] = sanitize_text_field( $input['remove_wp_shortlink'] );
        
        // remove feed_links
        if( isset( $input['remove_feed_links'] ) ) $new_input['remove_feed_links'] = sanitize_text_field( $input['remove_feed_links'] );
        
        // remove comments feed_links
        if( isset( $input['remove_feed_links_extra'] ) ) $new_input['remove_feed_links_extra'] = sanitize_text_field( $input['remove_feed_links_extra'] );
        
        // remove rsd link
        if( isset( $input['remove_rss_link'] ) ) $new_input['remove_rss_link'] = sanitize_text_field( $input['remove_rss_link'] );
        
        // remove rsd link
        if( isset( $input['remove_rsd_link'] ) ) $new_input['remove_rsd_link'] = sanitize_text_field( $input['remove_rsd_link'] );
        
        // remove assets parameters
        if( isset( $input['remove_scripts_ver'] ) ) $new_input['remove_scripts_ver'] = sanitize_text_field( $input['remove_scripts_ver'] );
        
        // disable wp toolbar
        if( isset( $input['disable_wp_toolbar'] ) ) $new_input['disable_wp_toolbar'] = sanitize_text_field( $input['disable_wp_toolbar'] );
        
        // preserve htaccess cache
        if( isset( $input['preserve_cache'] ) ) $new_input['preserve_cache'] = sanitize_text_field( $input['preserve_cache'] );
        
        // compress html
        if( isset( $input['compress_html'] ) ) $new_input['compress_html'] = sanitize_text_field( $input['compress_html'] );
        
        // compress html comment
        if( isset( $input['compress_html_display'] ) ) $new_input['compress_html_display'] = sanitize_text_field( $input['compress_html_display'] );

        return $new_input;
    }

    public function print_section_info() {
        // print 'Enter your settings below:';
    }

    public function disable_emoji_callback() {
        printf(
            '<input type="checkbox" id="disable_emoji" name="cnr_performance[disable_emoji]" value="1" ' . checked( '1', isset( $this->options['disable_emoji'] ), false ) . '>',
            isset( $this->options['disable_emoji'] ) ? esc_attr( $this->options['disable_emoji']) : ''
        );
    }
    public function disable_wlwmanifest_callback() {
	    printf(
            '<input type="checkbox" id="disable_wlwmanifest" name="cnr_performance[disable_wlwmanifest]" value="1" ' . checked( '1', isset( $this->options['disable_wlwmanifest'] ), false ) . '>',
            isset( $this->options['disable_wlwmanifest'] ) ? esc_attr( $this->options['disable_wlwmanifest']) : ''
        );
    }
    public function disable_pingback_callback() {
	    printf(
            '<input type="checkbox" id="disable_pingback" name="cnr_performance[disable_pingback]" value="1" ' . checked( '1', isset( $this->options['disable_pingback'] ), false ) . '>',
            isset( $this->options['disable_pingback'] ) ? esc_attr( $this->options['disable_pingback']) : ''
        );
    }
    public function disable_xmlrpc_callback() {
	    printf(
            '<input type="checkbox" id="disable_xmlrpc" name="cnr_performance[disable_xmlrpc]" value="1" ' . checked( '1', isset( $this->options['disable_xmlrpc'] ), false ) . '>',
            isset( $this->options['disable_xmlrpc'] ) ? esc_attr( $this->options['disable_xmlrpc']) : ''
        );
    }
    public function disable_restapi_callback() {
	    printf(
            '<input type="checkbox" id="disable_restapi" name="cnr_performance[disable_restapi]" value="1" ' . checked( '1', isset( $this->options['disable_restapi'] ), false ) . '>',
            isset( $this->options['disable_restapi'] ) ? esc_attr( $this->options['disable_restapi']) : ''
        );
    }
    public function disable_heartbeat_callback() {
        printf(
            '<input type="checkbox" id="disable_heartbeat" name="cnr_performance[disable_heartbeat]" value="1" ' . checked( '1', isset( $this->options['disable_heartbeat'] ), false ) . '>',
            isset( $this->options['disable_heartbeat'] ) ? esc_attr( $this->options['disable_heartbeat']) : ''
        );
    }
    public function reduce_heartbeat_callback() {
	    
	    $fieldValue = ( '' != $this->options['reduce_heartbeat'] ) ? $this->options['reduce_heartbeat'] : '15';
	    printf(
            '<input type="number" id="reduce_heartbeat" min="15" max="60" name="cnr_performance[reduce_heartbeat]" value="' . $fieldValue . '">',
            isset( $this->options['reduce_heartbeat'] ) ? esc_attr( $this->options['reduce_heartbeat']) : ''
        );
    }
    
    public function remove_rss_link_callback() {
        printf(
            '<input type="checkbox" id="remove_rss_link" name="cnr_performance[remove_rss_link]" value="1" ' . checked( '1', isset( $this->options['remove_rss_link'] ), false ) . '>',
            isset( $this->options['remove_rss_link'] ) ? esc_attr( $this->options['remove_rss_link']) : ''
        );
    }
    public function remove_rsd_link_callback() {
        printf(
            '<input type="checkbox" id="remove_rsd_link" name="cnr_performance[remove_rsd_link]" value="1" ' . checked( '1', isset( $this->options['remove_rsd_link'] ), false ) . '>',
            isset( $this->options['remove_rsd_link'] ) ? esc_attr( $this->options['remove_rsd_link']) : ''
        );
    }
    
    public function remove_wp_generator_callback() {
        printf(
            '<input type="checkbox" id="remove_wp_generator" name="cnr_performance[remove_wp_generator]" value="1" ' . checked( '1', isset( $this->options['remove_wp_generator'] ), false ) . '>',
            isset( $this->options['remove_wp_generator'] ) ? esc_attr( $this->options['remove_wp_generator']) : ''
        );
    }
    public function remove_wc_generator_callback() {
        printf(
            '<input type="checkbox" id="remove_wc_generator" name="cnr_performance[remove_wc_generator]" value="1" ' . checked( '1', isset( $this->options['remove_wc_generator'] ), false ) . '>',
            isset( $this->options['remove_wc_generator'] ) ? esc_attr( $this->options['remove_wc_generator']) : ''
        );
    }
    public function remove_vc_generator_callback() {
        printf(
            '<input type="checkbox" id="remove_vc_generator" name="cnr_performance[remove_vc_generator]" value="1" ' . checked( '1', isset( $this->options['remove_vc_generator'] ), false ) . '>',
            isset( $this->options['remove_vc_generator'] ) ? esc_attr( $this->options['remove_vc_generator']) : ''
        );
    }
    
    public function remove_wp_shortlink_callback() {
	    printf(
            '<input type="checkbox" id="remove_wp_shortlink" name="cnr_performance[remove_wp_shortlink]" value="1" ' . checked( '1', isset( $this->options['remove_wp_shortlink'] ), false ) . '>',
            isset( $this->options['remove_wp_shortlink'] ) ? esc_attr( $this->options['remove_wp_shortlink']) : ''
        );
    }
    
    public function remove_feed_links_callback() {
	    printf(
            '<input type="checkbox" id="remove_feed_links" name="cnr_performance[remove_feed_links]" value="1" ' . checked( '1', isset( $this->options['remove_feed_links'] ), false ) . '>',
            isset( $this->options['remove_feed_links'] ) ? esc_attr( $this->options['remove_feed_links']) : ''
        );
    }
    public function remove_feed_links_extra_callback() {
	    printf(
            '<input type="checkbox" id="remove_feed_links_extra" name="cnr_performance[remove_feed_links_extra]" value="1" ' . checked( '1', isset( $this->options['remove_feed_links_extra'] ), false ) . '>',
            isset( $this->options['remove_feed_links_extra'] ) ? esc_attr( $this->options['remove_feed_links_extra']) : ''
        );
    }
    
    public function remove_scripts_ver_callback() {
        printf(
            '<input type="checkbox" id="remove_scripts_ver" name="cnr_performance[remove_scripts_ver]" value="1" ' . checked( '1', isset( $this->options['remove_scripts_ver'] ), false ) . '>',
            isset( $this->options['remove_scripts_ver'] ) ? esc_attr( $this->options['remove_scripts_ver']) : ''
        );
    }
    
    public function disable_wp_toolbar_callback() {
        printf(
            '<input type="checkbox" id="disable_wp_toolbar" name="cnr_performance[disable_wp_toolbar]" value="1" ' . checked( '1', isset( $this->options['disable_wp_toolbar'] ), false ) . '>',
            isset( $this->options['disable_wp_toolbar'] ) ? esc_attr( $this->options['disable_wp_toolbar']) : ''
        );
    }
    
    public function disable_wp_oembed_callback() {
        printf(
            '<input type="checkbox" id="disable_wp_oembed" name="cnr_performance[disable_wp_oembed]" value="1" ' . checked( '1', isset( $this->options['disable_wp_oembed'] ), false ) . '>',
            isset( $this->options['disable_wp_oembed'] ) ? esc_attr( $this->options['disable_wp_oembed']) : ''
        );
    }
    
    public function preserve_cache_callback() {
        printf(
            '<input type="checkbox" id="preserve_cache" name="cnr_performance[preserve_cache]" value="1" ' . checked( '1', isset( $this->options['preserve_cache'] ), false ) . '>',
            isset( $this->options['preserve_cache'] ) ? esc_attr( $this->options['preserve_cache']) : ''
        );
    }
    public function compress_html_callback() {
        printf(
            '<input type="checkbox" id="compress_html" name="cnr_performance[compress_html]" value="1" ' . checked( '1', isset( $this->options['compress_html'] ), false ) . '>',
            isset( $this->options['compress_html'] ) ? esc_attr( $this->options['compress_html']) : ''
        );
    }
    
    public function compress_html_display_callback() {
	    printf(
            '<input type="checkbox" id="compress_html_display" name="cnr_performance[compress_html_display]" value="1" ' . checked( '1', isset( $this->options['compress_html_display'] ), false ) . '>',
            isset( $this->options['compress_html_display'] ) ? esc_attr( $this->options['compress_html_display']) : ''
        );
    }

}

if( is_admin() )
    $cnr_performance = new cnr_performanceSettings();
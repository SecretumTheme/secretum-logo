<?php
/**
 * Plugin Functions
 *
 * @package Secretum
 * @subpackage SecretumLogo
 */


namespace SecretumLogo\Functions {
	/**
	 * Manage Shortcode Attributes & Display Nav Menu
	 * 
	 * @param array $atts Shortcode Attributes
	 *  @param brand_class (string)
	 *  @param container_class (string)
	 *  @param custom_logo_class (string)
	 * @param string $content Not Used
	 * @return wp_nav_menu()
	 *
	 */
	function shortcode($atts = array(), $content = false) {
	    // Get Attributes
	    $atts = shortcode_atts(array(
	        "brand_class"       => false,
	        "container_class"   => false,
	        "custom_logo_class" => false
	   ), $atts);

		$custom_logo_id = get_theme_mod('custom_logo');

	    if ($custom_logo_id) {
	        $custom_logo_attr = array(
	            'class'    => 'custom-logo',
	            'itemprop' => 'logo',
	       );

	        // If no alt tag, get site title
	        $image_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);

	        if (empty($image_alt)) {
	            $custom_logo_attr['alt'] = get_bloginfo('name', 'display');
	        }
	    }

    	// Set Primary Logo Container Class
    	$brand_class = !empty($atts['brand_class']) ? sanitize_html_class($atts['brand_class']) : 'site-title';

        // Set Container Class
        $container_class = !empty($atts['container_class']) ? sanitize_html_class($atts['container_class']) : '';

        // Set Custom Logo Class
        $custom_logo_class = !empty($atts['custom_logo_class']) ? sanitize_html_class($atts['custom_logo_class']) : '';

		$html = '';

		// Container Class
		if (!empty($container_class)) {
			$html .= '<div class="' . $container_class . '">';
		}

		// Has Custom Logo
		if (has_custom_logo() && $custom_logo_id) {
			// Custom Logo Container
			if (!empty($custom_logo_class)) {
				$html .= '<div class="' . $custom_logo_class . '">';
			}

			// Custom Logo
			$html = sprintf('<a href="%1$s" class="custom-logo-link %2$s" rel="home" itemprop="url">%3$s</a>',
	            esc_url(home_url('/')),
	            $brand_class,
	            wp_get_attachment_image($custom_logo_id, 'full', false, $custom_logo_attr)
	       	);

			// Close Custom Logo Container
			if (!empty($custom_logo_class)) {
				$html .= '</div>';
			}

		// Front-page or Home & No Custom Logo
		} elseif(is_front_page() && is_home()) {
			// Homepage
			$html = sprintf('<h1 class="%1$s"><a href="%2$s" class="custom-logo-link" title="%3$s" rel="home" itemprop="url">%4$s</a></h1>',
	            $brand_class,
	            esc_url(home_url('/')),
	            esc_attr(get_bloginfo('name', 'display')),
	            get_bloginfo('name')
	       	);

		// No Custom Logo
		} else {
			$html = sprintf('<a href="%1$s" class="custom-logo-link %2$s" title="%3$s" rel="home" itemprop="url">%4$s</a>',
	            esc_url(home_url('/')),
	            $brand_class,
	            esc_attr(get_bloginfo('name', 'display')),
	            get_bloginfo('name')
	       	);
		}

		// Close Container Class
		if (!empty($container_class)) {
			$html .= '</div>';
		}

		return $html;
	}


    /**
     * Add Shortcode Field To Customizer
     */
    function customize($wp_customize)
    {
    	// If Secretum Theme Installed
    	if(defined('SECRETUM_THEME_VERSION')) {
			// Setting :: Secretum Logo Shortcode
			$wp_customize->add_setting('secretum[logo_shortcode_theme]' , array(
				'default' 			=> '[secretum_logo brand_class=&#x22;navbar-brand&#x22;]',
				'type'       		=> 'option',
				'transport' 		=> 'refresh',
				'sanitize_callback' => function() { return '[secretum_logo brand_class=&#x22;navbar-brand&#x22;]'; }
			));

			// Control :: Secretum Logo Shortcode
			$wp_customize->add_control('secretum[logo_shortcode_theme]', array(
				'label' 		=> __('Secretum Logo Shortcode', 'secretum-logo'),
				'section' 		=> 'secretum_header_site_identity',
	    		'priority' 		=> 15,
				'type' 			=> 'text'
			));

			// Setting :: Logo Shortcode With Args
			$wp_customize->add_setting('secretum[logo_shortcode_args]' , array(
				'default' 			=> '[secretum_logo brand_class=&#x22;navbar-brand&#x22; container_class=&#x22;&#x22; custom_logo_class=&#x22;&#x22;]',
				'type'       		=> 'option',
				'transport' 		=> 'refresh',
				'sanitize_callback' => function() { return '[secretum_logo brand_class=&#x22;navbar-brand&#x22; container_class=&#x22;&#x22; custom_logo_class=&#x22;&#x22;]'; }
			));

			// Control :: Logo Shortcode With Args
			$wp_customize->add_control('secretum[logo_shortcode_args]', array(
				'label' 		=> __('Logo Shortcode With Args', 'secretum-logo'),
				'section' 		=> 'secretum_header_site_identity',
	    		'priority' 		=> 15,
				'type' 			=> 'text'
			));

		// Other Themes
    	} else {
			// Setting :: Default Logo Shortcode
			$wp_customize->add_setting('secretum[logo_shortcode]' , array(
				'default' 			=> '[secretum_logo]',
				'type'       		=> 'option',
				'transport' 		=> 'refresh',
				'sanitize_callback' => function() { return '[secretum_logo]'; }
			));

			// Control :: Default Logo Shortcode
			$wp_customize->add_control('secretum[logo_shortcode]', array(
				'label' 		=> __('Default Logo Shortcode', 'secretum-logo'),
				'section' 		=> 'title_tagline',
	    		'priority' 		=> 15,
				'type' 			=> 'text'
			));

			// Setting :: Logo Shortcode With Args
			$wp_customize->add_setting('secretum[logo_shortcode_args]' , array(
				'default' 			=> '[secretum_logo brand_class=&#x22;&#x22; container_class=&#x22;&#x22; custom_logo_class=&#x22;&#x22;]',
				'type'       		=> 'option',
				'transport' 		=> 'refresh',
				'sanitize_callback' => function() { return '[secretum_logo brand_class=&#x22;&#x22; container_class=&#x22;&#x22; custom_logo_class=&#x22;&#x22;]'; }
			));

			// Control :: Logo Shortcode With Args
			$wp_customize->add_control('secretum[logo_shortcode_args]', array(
				'label' 		=> __('Logo Shortcode With Args', 'secretum-logo'),
				'section' 		=> 'title_tagline',
	    		'priority' 		=> 15,
				'type' 			=> 'text'
			));
    	}
    }


    /**
     * Activate Plugin
     */
    function activate()
    {
        // Wordpress Version Check
        global $wp_version;

        // Version Check
        if(version_compare($wp_version, SECRETUM_LOGO_WP_MIN_VERSION, "<")) {
            wp_die(__('Activation Failed: The ' . SECRETUM_LOGO_PAGE_NAME . ' plugin requires WordPress version ' . SECRETUM_LOGO_WP_MIN_VERSION . ' or higher. Please Upgrade Wordpress, then try activating this plugin again.', 'secretum-logo'));
        }
    }


    /**
     * Add Links
     *
     * @param array $links Default links for this plugin
     * @param string $file The name of the plugin being displayed
     * @return array $links The links to inject
     */
    function links($links, $file)
    {
        // Get Current URL
        $request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        // Only this plugin and on plugins.php page
        if ($file == SECRETUM_LOGO_PLUGIN_BASE && strpos($request_uri, "plugins.php") !== false) {
            // Links To Inject
            $links[] = '<a href="https://github.com/SecretumTheme/secretum-logo/issues" target="_blank">'. __('Report Issues', 'secretum-logo') .'</a>';
        }

        return $links;
    }
}

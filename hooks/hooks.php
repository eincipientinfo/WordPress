<?php

/*******************    Wordpress Hooks - Start      ******************************/


// Force slug auto generate For Pending Posts
add_filter( 'wp_insert_post_data', function ( $data, $postarr ) {
    global $post_ID;

    if (in_array($postarr['post_type'], array('pom_work', 'pom_group')) && in_array($postarr['post_status'], array('pending'))) {
        if (empty($data['post_name'])) {
            $data['post_name'] = sanitize_title($data['post_title'], $post_ID);
        }
    }
    return $data;
}, 99, 2 );

/* -------------------------------------------------------------------------------- */

/* Add User To Second subSite also with Multisite Registration with Ultimate Member Plugin on First subSite */
add_action( 'um_registration_set_extra_data', function ( $user_id, $args ) {
    
    $role = $args['role'];
    add_user_to_blog( 2, $user_id, $role );

}, 10, 2 );

/* -------------------------------------------------------------------------------- */

// Add Custom Class to li in Primary location Menu
add_filter('nav_menu_css_class', 'link_menu_classes', 1, 3);
function link_menu_classes($classes, $item, $args) {
    if ($args->theme_location == 'primary') {
        if (array_search('current-menu-item', $item->classes) != 0) {
            $classes[] = 'nav-item active';
        } else {
            $classes[] = 'nav-item';
        }
    }
    return $classes;
}

/* -------------------------------------------------------------------------------- */

// Add Custom Class to a tag of Primary location Menu 
add_filter('wp_nav_menu', 'add_link_class', 10, 2);
function add_link_class($args, $int) {

    if ($int->theme_location == 'primary') {
        return preg_replace('/<a /', '<a class="nav-link" ', $args);
    } else {
        return $args;
    }

}

/* -------------------------------------------------------------------------------- */

// Add Custom theme options in Theme Customizer
add_action('customize_register', 'theme_customize_register', 11);
function theme_customize_register($wp_customize) {

    $wp_customize->add_section('custom_theme_section', array(
        'title' => __('Woocommerce Theme Option', 'ctm'),
        'priority' => 1,
    ));


    // Add custom header and sidebar text color setting and control.
    $wp_customize->add_setting('single_product_img', array(
        'transport' => 'refresh ',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'single_product_img', array(
        'label' => __('Footer Single Product Image', 'ctm'),
        'description' => __('Upload image for footer single product', 'ctm'),
        'section' => 'custom_theme_section',
    )));

    $wp_customize->add_setting('product_img', array(
        'transport' => 'refresh ',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'product_img', array(
        'label' => __('Footer Product Image', 'ctm'),
        'description' => __('Upload image for footer product', 'ctm'),
        'section' => 'custom_theme_section',
    )));


    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'calltoaction_link', array(
        'label' => __('Button Link', 'ctm'),
        'description' => __('Applied to the topar on Call to action button.', 'ctm'),
        'section' => 'custom_theme_section',
        "type" => "url",
    )));

}

/*******************    Wordpress Hooks - End      ******************************/


/*******************    ACF Plugin Hooks - Start      ******************************/

/*
* Add Custom ACF Fields For POM Groups Post Type
*/
add_action('acf/init', function () {
  acf_add_local_field_group(array(
    'key' => 'group_pom_groups_fields',
    'title' => 'POM Groups Fields',
    'fields' => array (
      array (
        'key' => 'field_group_resources',
        'label' => 'Necessary Resources',
        'name' => 'group_resources',
        'type' => 'wysiwyg',
      ),
      array (
        'key' => 'field_group_admins',
        'label' => 'Group Admins',
        'name' => 'group_admin',
        'type' => 'user',
        'role' => array(
          0 => 'um_pom-group-admin',
        ),
        'return_format' => 'id',
      ),
      array (
        'key' => 'field_group_workers',
        'label' => 'Group Workers',
        'name' => 'group_workers',
        'type' => 'user',
        'role' => array(
          0 => 'um_pom-worker',
        ),
        'multiple' => 1,
        'return_format' => 'id',
      ),
      array (
        'key' => 'field_group_active_since',
        'label' => 'Active Since',
        'name' => 'group_active_since',
        'type' => 'date_picker',
        'wrapper' => array (
            'width' => 50,
            'class' => '',
            'id' => 'start_date_field',
        ),
      ),
      array (
        'key' => 'field_group_expires',
        'label' => 'Expires',
        'name' => 'group_expires',
        'type' => 'date_picker',
        'wrapper' => array (
            'width' => 50,
            'class' => '',
            'id' => 'end_date_field',
        ),
      )

    ),
      
    'location' => array (
      array (
        array (
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'pom_group',
        ),
      ),
    ),
  )); 
});

/* -------------------------------------------------------------------------------- */

// Add Option Page To Wordpress With ACF
add_action('acf/init', 'ctm_acf_init');
function ctm_acf_init() {

    if (function_exists('acf_add_options_page')) {

        // add parent
        $CTMparentOptionPage = acf_add_options_page(array(
            'page_title' => __('WAD Indstillinger', 'ctm'),
            'menu_title' => __('WAD Indstillinger', 'ctm'),
            'menu_slug' => __('wad-indstillinger', 'ctm'),
            'icon_url' => '',
            'redirect' => false
        ));

        // add sub page - HEADER
        acf_add_options_sub_page(array(
            'page_title' => __('Header', 'ctm'),
            'menu_title' => __('Header', 'ctm'),
            'parent_slug' => $CTMparentOptionPage['menu_slug'],
            'redirect' => false
        ));

        // add sub page - FOOTER
        acf_add_options_sub_page(array(
            'page_title' => __('Footer', 'ctm'),
            'menu_title' => __('Footer', 'ctm'),
            'parent_slug' => $CTMparentOptionPage['menu_slug'],
            'redirect' => false
        ));

        // add sub page - WooCommerce
        acf_add_options_sub_page(array(
            'page_title' => __('WooCommerce', 'ctm'),
            'menu_title' => __('WooCommerce', 'ctm'),
            'parent_slug' => $CTMparentOptionPage['menu_slug'],
            'redirect' => false
        ));
    }
}

/* -------------------------------------------------------------------------------- */

/*
* Update POM Work Post Status After Edit from Frontend
*/
add_filter('acf/pre_save_post' , function ($post_id) {
    // check for numerical post_id and check post_type
    if (!is_numeric($post_id) || get_post_type($post_id) != 'pom_work') {
      return $post_id;
    }

    // update status to draft
    $post = array(
      'ID' => $post_id,
      'post_status'  => 'pending' ,
    );  

    // update the post
    wp_update_post($post);
    
    return $post_id;
}, 10, 1 );


/*******************    ACF Plugin Hooks - End      ******************************/


/*******************    Gravity Form Plugin Hooks - Start      ******************************/

// Populate Dynamic Posts Data in Select Field of gForm ID 3
add_filter('gform_pre_render_3',  __NAMESPACE__ . '\\populate_posts');
add_filter('gform_pre_validation_3',  __NAMESPACE__ . '\\populate_posts');
add_filter('gform_pre_submission_filter_3',  __NAMESPACE__ . '\\populate_posts');
add_filter('gform_admin_pre_render_3',  __NAMESPACE__ . '\\populate_posts');
function populate_posts( $form ) {
 
    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'populate-pom_group' ) === false ) {
            continue;
        }
 
        $posts = get_posts( 'post_type=pom_group&numberposts=-1&post_status=publish' );
        $choices = array();
        foreach ( $posts as $post ) {
            $choices[] = array( 'text' => $post->post_title, 'value' => $post->post_title );
        }
        $field->choices = $choices;
    }
    return $form;
}

/* -------------------------------------------------------------------------------- */

// Check For Duplicate Entry of Email For Form ID 1
add_filter('gform_field_validation_1', 'validate_dulicate_email', 10, 4);
function validate_dulicate_email($result, $value, $form, $field) {
    if ($field->type == 'email') {
        $search_criteria = array();
        $search_criteria['field_filters'][] = array('key' => $field->id, 'value' => $value);
        $entries = GFAPI::get_entries($form['id'], $search_criteria);
        if (!empty($entries)) {
            $result['is_valid'] = false;
            $result['message'] = "Email id is already registered with us. Please Try different email-id";
        }
    }
    return $result;
}

/* -------------------------------------------------------------------------------- */

// Change Validation Error Message
add_filter('gform_validation_message', 'change_message', 10, 2);
function change_message($message, $form) {
    return "<div class='validation_error'>" . esc_html__('Oops... Something went wrong,', 'gravityforms') . ' ' . esc_html__('Please check an error below to submit your form.', 'gravityforms') . '</div>';
}

/* -------------------------------------------------------------------------------- */

// Change 'Username' Field Label to 'Email' of Gravity Login Form
add_filter('gform_userregistration_login_form', 'change_form_label', 10, 1);
function change_form_label($form) {
    $fields = $form['fields'];
    foreach ($fields as &$field) {
        if ($field->label == 'Username') {
            $field->label = 'Email';
        }
    }
    return $form;
}

/* -------------------------------------------------------------------------------- */

// After login redirect their perticuler page
add_filter('gform_user_registration_login_redirect_url', 'redirect_after_login', 10, 2);
function redirect_after_login($login_redirect, $sign_on) {

    $id = $sign_on->ID;
    $user_last = get_user_meta($id, 'registration_status', true);
    $home_url = get_home_url();
    $user_role = $sign_on->roles[0];
    if ($user_last == "approved") {

        if ($user_role == 'um_applicant') {
            $url = $home_url . "/applicant/";
        } else if ($user_role == 'um_agent') {
            $url = $home_url . "/travel-agent/";
        } else if ($user_role == 'um_employee') {
            $url = $home_url . "/employer/";
        }else{
            $url = $home_url;
        }
    } else {
        $url = $home_url . "/status-pending/";
    }

    return $url;
}

/* -------------------------------------------------------------------------------- */

// Set Role to Frontend Registered User
add_action('gform_user_updated', 'frontend_register_add_2nd_user_role', 10, 4);
add_action('gform_user_registered', 'frontend_register_add_2nd_user_role', 10, 4);
function frontend_register_add_2nd_user_role( $user_id, $user_config, $entry, $user_pass ) {

    global $wp_roles;
    $user = new WP_User($user_id);
    $role = $entry[15];
    $user->set_role($role);

}

/*******************    Gravity Form Plugin Hooks - End      ******************************/



/*******************    Woocommerce Plugin Hooks - Start      ******************************/

/**
* Woocommerce Checkout Fields - Change Label & PlaceHolder, Required , Set Priority of fields & Unset Fields 
**/
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {

    // Change Label & Placeholder of below Fields
    $fields['order']['order_comments']['placeholder'] = 'Ask questions or place comments here';  
    $fields['order']['order_comments']['label'] = 'Gabion questions/comments';
    $fields['billing']['billing_address_2']['label'] = 'Address';
    $fields['billing']['billing_address_2']['placeholder'] = 'Town';
    $fields['billing']['billing_company']['label'] = 'House or building name';
    $fields['billing']['billing_city']['label'] = 'City';
    $fields['billing']['billing_city']['placeholder'] = 'City';
    
    // Required False to below Fields
    $fields['billing']['billing_phone']['required'] = false;
    $fields['billing']['billing_city']['required'] = false;
    $fields['billing']['billing_address_1']['required'] = false;
    
    // Remove Fields From Checkout
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);

    // Change Priority of Fields      
    $fields['billing']['billing_email']['priority'] = 4;
    $fields['billing']['billing_postcode']['priority'] = 5;
    $fields['billing']['billing_company']['priority'] = 6;
    $fields['billing']['billing_address_1']['priority'] = 7;
    $fields['billing']['billing_phone']['priority'] = 8;
    $fields['billing']['billing_address_2']['priority'] = 9;
    $fields['billing']['billing_city']['priority'] = 10;

    // Add Class to Fields
    $fields['billing_company']['class'] = array( 'form-row-first' );
    $fields['billing_address_1']['class'] = array( 'form-row-first' );
    $fields['billing_address_2']['class'] = array( 'form-row-first' );
    $fields['billing_city']['class'] = array( 'form-row-last' );
    
    return $fields;
}



/* -------------------------------------------------------------------------------- */

// Add New Column 'Weight' in order Admin Display
add_filter( 'manage_edit-shop_order_columns', 'custom_order_weight_column', 20 );
function custom_order_weight_column( $columns ) {

    $offset = 8;
    $updated_columns = array_slice( $columns, 0, $offset, true) +
    array( 'total_weight' => esc_html__( 'Weight', 'woocommerce' ) ) +
    array_slice($columns, $offset, NULL, true);

    return $updated_columns;
}

// Add Data to Custom 'Weight' column in order Admin Display
add_action( 'manage_shop_order_posts_custom_column', 'custom_order_weight_column_data', 2 );
function woo_custom_order_weight_column_data( $column ) {
    global $post;
 
    if ( $column == 'total_weight' ) {
        $weight = get_post_meta( $post->ID, '_cart_weight', true );
        if ( $weight > 0 )
            print $weight . ' ' . esc_attr( get_option('woocommerce_weight_unit' ) );
        else print 'N/A';
    }
}

/* -------------------------------------------------------------------------------- */

/**
 * Add a custom product data tab
 */
add_filter('woocommerce_product_tabs', 'washing_guide_product_tab');
function washing_guide_product_tab($tabs) {

    // Add the new tab
    $tabs['washing_guide_tab'] = array(
        'title' => __('Washing Guide', 'woocommerce'),
        'priority' => 50,
        'callback' => 'washing_guide_product_tab_content'
    );

    return $tabs;
}

// Display Data to Custom Tab
function washing_guide_product_tab_content() {

    // The new tab content
    echo get_post_meta(get_the_ID(), 'washing_guide', true);
}

/* -------------------------------------------------------------------------------- */

// WooCommerce set currency based on Country ip
function site_wcml_custom_currency($current) {
    
    $currency = 'EUR';
    $geo_locate = new WC_Geolocation();
    $location = $geo_locate->geolocate_ip();
    if($location['country'] == 'DK') {
        $currency = 'DKK';
    }
    return $currency;

}
add_filter('wcml_client_currency', 'site_wcml_custom_currency');

/* -------------------------------------------------------------------------------- */

//change text of add to cart button for out of stock products 
add_filter('woocommerce_product_add_to_cart_text', 'wc_product_add_to_cart_text_out_of_stock',50);
function wc_product_add_to_cart_text_out_of_stock() {
    global $product;
    if ($product->get_stock_status() == 'outofstock') {
        return __('Out of Stock', 'woocommerce');
    } else {
        $product_type = $product->product_type;
        switch ($product_type) {
            case 'external':
                return __('Buy product', 'woocommerce');
                break;
            case 'grouped':
                return __('View products', 'woocommerce');
                break;
            case 'simple':
                return __('Add to cart', 'woocommerce');
                break;
            case 'variable':
                return __('Select options', 'woocommerce');
                break;
            default:
                return __('Read more', 'woocommerce');
        }
        /* end switch */
    }
}

/* -------------------------------------------------------------------------------- */

// Add Custom Field for only 'DK' Country on Cart Page
add_action('woocommerce_review_order_before_order_total', 'add_financing', 10);
function add_financing() {
    global $woocommerce;

    $geo_locate = new WC_Geolocation();
    $location = $geo_locate->geolocate_ip();

    $checked = $woocommerce->checkout()->get_value( 'financing' ) ? $woocommerce->checkout()->get_value( 'financing' ) : 0;

    if($location['country'] == 'DK') : ?>
        <tr class="order-financing">
            <th>Interest free loan</th>
            <td data-title="Interest free loan"><?php woocommerce_form_field('financing', array(
                    'type'          => 'checkbox',
                    'class'         => array('input-checkbox'),
                    'value'         => true,
                    'label'         => __('I applied for a SparXpres loan')
                ), $checked); ?></td>
        </tr><?php
    endif;
}

// Update Data For Financing Field
add_action('woocommerce_checkout_update_order_meta', 'checkout_field_update_order_meta');
function checkout_field_update_order_meta($order_id) {
    if ($_POST['financing']) {
        update_post_meta( $order_id, '_financing', esc_attr($_POST['financing']));
    }
}

// Email Data of Financing Field
add_action( "woocommerce_email_after_order_table", "woocommerce_email_after_order_table", 10, 1);
function woocommerce_email_after_order_table($order) {
    $financing = get_post_meta($order->id, "_financing", true);
    if ($financing) {
        echo '<div style="margin: 20px 0"><p><strong>Applied for financing:</strong> Yes</p></div>';
    }
}

// Display Data For Financing Field in Admin
add_action( 'woocommerce_admin_order_data_after_billing_address', 'checkout_field_display_admin_order_meta', 10, 1 );
function checkout_field_display_admin_order_meta($order){
    global $post;
    $financing = get_post_meta($post->id, "_financing", true);
    if ($financing) {
        echo '<div style="margin: 20px 0"><p><strong>Applied for financing:</strong> Yes</p></div>';
    }
}

/* -------------------------------------------------------------------------------- */

// Checkout Page Customization

/* Add additional shipping fields (email, phone) in FRONT END (i.e. My Account and Order Checkout) */
add_filter( 'woocommerce_shipping_fields' , 'additional_shipping_fields' );
function additional_shipping_fields( $fields ) {

    $fields['shipping_email'] = array(
        'label'         => __( 'Email', 'woocommerce' ),
        'required'      => true,
        'class'         => array( 'form-row-first' ),
        'clear'         => true,
        'validate'      => array( 'email' ),
    );
    $fields['shipping_phone'] = array(
        'label'         => __( 'Phone', 'woocommerce' ),
        'required'      => true,
        'class'         => array( 'form-row-last' ),
        'clear'         => true,
        'validate'      => array( 'phone' ),
    );
    return $fields;

}

/* Display additional shipping fields (email, phone) in ADMIN area (i.e. Order display ) */
add_filter( 'woocommerce_admin_shipping_fields' , 'additional_admin_shipping_fields' );
function additional_admin_shipping_fields( $fields ) {

        $fields['email'] = array(
            'label' => __( 'Order Ship Email', 'woocommerce' ),
        );
        $fields['phone'] = array(
            'label' => __( 'Order Ship Phone', 'woocommerce' ),
        );
        return $fields;

}

/* Display additional shipping fields (email, phone) in USER area (i.e. Admin User/Customer display ) */
add_filter( 'woocommerce_customer_meta_fields' , 'additional_customer_meta_fields' );
function additional_customer_meta_fields( $fields ) {

        $fields['shipping']['fields']['shipping_phone'] = array(
            'label' => __( 'Telephone', 'woocommerce' ),
            'description' => '',
        );
        $fields['shipping']['fields']['shipping_email'] = array(
            'label' => __( 'Email', 'woocommerce' ),
            'description' => '',
        );
        return $fields;

}

// define the woocommerce_checkout_update_order_meta callback 
add_action( 'woocommerce_checkout_update_order_meta', 'action_woocommerce_checkout_update_order_meta', 10, 2 );
function action_woocommerce_checkout_update_order_meta( $order_id, $data ) { 
    
    $sFormData = $_POST['s_form_data'];

    if( !empty($sFormData) && $sFormData == 1 ){

        $user_id = get_current_user_id();       

        // Order Details Fields
        update_post_meta($order_id, '_billing_email', $_POST['shipping_email']);
        update_post_meta($order_id, '_billing_phone', $_POST['shipping_phone']);

        // Register Customer Fields 
        update_user_meta($user_id, 'billing_email', $_POST['shipping_email']);
        update_user_meta($user_id, 'billing_phone', $_POST['shipping_phone']);

    }else{
        update_post_meta($order_id, '_billing_email', $_POST['shipping_email']);
        update_user_meta($user_id, 'billing_email', $_POST['shipping_email']);
    }

};  


/*******************    Woocommerce Plugin Hooks - End      ******************************/
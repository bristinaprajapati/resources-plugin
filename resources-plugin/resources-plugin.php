<?php
/*
* Plugin Name: Resources Plugin
* Description: Adds a resources custom post type and shortcode to show latest resources.
* Version: 1.0
* Author: Bristina Prajapati
*/

//custom post type for resources
function resource_custom_post_type(){
    $labels = array(
        'name'               => __( 'Resources' ),
        'singular_name'      => __( 'Resource' ),
        'add_new'            => __( 'Add New Resource' ),
        'add_new_item'       => __( 'Add New Resource' ),
        'edit_item'          => __( 'Edit Resource' ),
        'new_item'           => __( 'New Resource' ),
        'all_items'          => __( 'All Resources' ),
        'view_item'          => __( 'View Resource' ),
        'search_items'       => __( 'Search Resource' ),
        'featured_image'     => 'Featured Image',
        'set_featured_image' => 'Add Featured Image'

    );

    $args = array(
        'labels'            => $labels,
        'description'       => 'Holds our custom article post specific data',
        'public'            => true,
        'menu_position'     => 5,
        'supports'          => array( 'title', 'thumbnail', 'excerpt' ),
        'has_archive'       => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'query_var'         => true,
    );
   
    register_post_type('resources', $args);
}

add_action('init', 'resource_custom_post_type');

//shortcode to display latest resources
function latest_resources( $atts ){
    $atts = shortcode_atts(array(
        'limit' => 5,
    ), $atts, 'latest_resources' );

    $query = new WP_Query( array(
        'post_type'      => 'resources',
        'posts_per_page' => intval( $atts['limit'] ),
    ) );

    ob_start();

    if ( $query->have_posts()){
        echo '<div class="resources_grid">';
        while($query->have_posts()){
            $query->the_post();
            ?>
            
            <div class="resource_item">
            <h3><?php echo esc_html( get_the_title() ); ?></h3>
            <?php if ( has_post_thumbnail() ) {
                    the_post_thumbnail( 'medium', array( 'class' => 'resources-img' ) );
                } ?>
            <p><?php echo esc_html( get_the_excerpt() ); ?></p>
            <a href="<?php the_permalink(); ?>">Read More</a>
            </div>  
            <?php

        }
        echo '</div>';
    } else{
        echo '<p>No resources found.</p>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}

add_shortcode( 'latest_resources', 'latest_resources' );

//enqueueing plugin stylesheet
function resources_plugin_enqueue_styles() {
    wp_enqueue_style( 'resources-plugin-style', plugin_dir_url( __FILE__ ) . 'resources-style.css' );
}
add_action( 'wp_enqueue_scripts', 'resources_plugin_enqueue_styles' );
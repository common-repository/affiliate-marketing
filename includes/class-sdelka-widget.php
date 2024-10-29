<?php

/**
 * Sdelka Widget class
 *
 * @since      1.1.17
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 */

/**
 * Sdelka Widget class
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Sdelka
 * @subpackage Sdelka/includes
 * @author     sdelka.biz
 */
class Sdelka_Widget extends WP_Widget {
	    // Main constructor
    public function __construct() {
    	    parent::__construct(
    	        'sdelka_widget',
    	        __( 'widget_name', 'sdelka' ),
    	        array('description' =>  __('widget_description', 'sdelka'),
    	            'classname' => 'Sdelka_Widget',
    	            'customize_selective_refresh' => true,) );
	}
        public function form( $instance ) {
            $termlink_anchor = !empty( $instance['termlink_anchor'] ) ? $instance['termlink_anchor'] : __('affiliate program', 'sdelka'); ?>
            <p>
            <label for="<?php echo $this->get_field_id( 'termlink_anchor' ); ?>"><?php _e('termlink_anchor_label', 'sdelka'); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'termlink_anchor' ); ?>" name="<?php echo $this->get_field_name( 'termlink_anchor' ); ?>" value="<?php echo esc_attr( $termlink_anchor ); ?>" style="width:100%;"  />
            </p><?php
        }

	    // Update widget settings
        public function update( $new_instance, $old_instance ) {
            $instance = $old_instance;
            $instance[ 'termlink_anchor' ] = strip_tags( $new_instance[ 'termlink_anchor' ] );
            return $instance;
        }
	    // Display the widget
        public function widget( $args, $instance ) {

            $termlink_anchor = isset($instance['termlink_anchor']) ? $instance['termlink_anchor'] : ''; 
            $sdelka_termlink = get_option('sdelka_termlink');
            $sdelka_termlink = $sdelka_termlink ? $sdelka_termlink : 'https://sdelka.biz/';
            ?> <a href="<?php _e($sdelka_termlink); ?>" class="sdelka_termlink" rel="nofollow" target="_blank"><?php _e($termlink_anchor); ?></a><?php
        }
	}

<?php 
/*
Plugin Name: Svenska Temadagar Plugin
Contributors: 
Description: Adds a widget to show swedish themes days
Tags: Svenska temadagar, swedish, temadagar
Tested up to: 4.7
Version: 1.0
Author: temadagarna
Author URI: http://
License: GPLv2
*/


class Temadagar_Widget extends WP_Widget {
     
    function __construct() {
        parent::__construct(
         
            // base ID of the widget
            'temadagar_widget',
             
            // name of the widget
            __('Tema Dagar', 'Temadagar' ),
             
            // widget options
            array (
                'description' => __( 'Temadagar widget.', 'Temadagar' )
            )
             
        );
    }
     
       
    function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];
        if ( ! empty( $title ) )
            echo $args['before_title'] . $title . $args['after_title'];

       
        
        $words = explode("\n",file_get_contents(plugins_url('temadagar.txt', __FILE__)));
        $day = date('z')-1;
        $today = date("d.m.y");  
        $word = explode("|",$words[$day]);

    
        echo '<h4>Idag den '.$today.' är det:</a></h4>';

        foreach($word as $value){
            echo "&#9679;".$value."<br>";
              }
        echo $args['after_widget'];
    }
     


  function form( $instance ) {
            if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'New title', 'text_domain' );
        }
        ?>
     
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:' , 'temadagar'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php 
    }
     
    function update( $new_instance, $old_instance ) { 
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;      
    }
}

function tema_dagar_widget() {
 
    register_widget( 'Temadagar_Widget' );
 
}
add_action( 'widgets_init', 'tema_dagar_widget' );



function tema_dagar_widget_shortcode($atts) {
    
    global $wp_widget_factory;
        
    $widget_name = 'Temadagar_Widget';
       if (!is_a($wp_widget_factory->widgets[$widget_name], 'WP_Widget')):
        $wp_class = 'WP_Widget_'.ucwords(strtolower($class));
        
        if (!is_a($wp_widget_factory->widgets[$wp_class], 'WP_Widget')):
            return '<p>'.sprintf(__("%s: Widget class not found. Make sure this widget exists and the class name is correct"),'<strong>'.$class.'</strong>').'</p>';
        else:
            $class = $wp_class;
        endif;
    endif;
    
    ob_start();
    the_widget($widget_name, array(), array('widget_id'=>'arbitrary-instance-temadagar_widget',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    ));
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    
}
add_shortcode('tema_dagar','tema_dagar_widget_shortcode'); 

?>
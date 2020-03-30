<?php
/*
Plugin name: Wp-learn
Plugin URI: http://github.com/no1-bigcrab/wp
Description: Plugin the first
Version: 0.1 version
Author: bigCrab
Author URI: github.com/no1-bigcrab/wp
*/
add_action( 'init', 'register_send_data_order_12_hours_event');

// Function which will register the event
function register_send_data_order_12_hours_event() {
	// Make sure this event hasn't been scheduled
	if( !wp_next_scheduled( 'send_data_order_12_hours' ) ) {
		// Schedule the event
		wp_schedule_event( time(), '12*HOUR_IN_SECONDS', 'send_data_order_12_hours' );
	}
}
function send_data_order_12_hours(){
  
  $args = array(
    'date_created' => '>'.(time() - DAY_IN_SECONDS*0.5),
  );

  $query = new WC_Order_Query( $args );
  $order = $query->get_orders();
  $i = 0;

  foreach ($order as $key => $value) {
    $order_id = $value->id;
    $data['order_id_'.$i+=1] += $order_id;
  }

	$url = 'http://localhost/wordpress/create.php';
    $response = wp_remote_post( $url, array(
      'method' => 'POST',
      'timeout' => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking' => true,
      'headers' => array(),
	    'body' => $data,
      'cookies' => array()
      )
    );
    if ( is_array( $response ) && isset( $response['body'] ) ) {
      echo '<pre>';
      print_r( json_decode( $response['body'], true ) );
      echo '</pre>';
    }
}

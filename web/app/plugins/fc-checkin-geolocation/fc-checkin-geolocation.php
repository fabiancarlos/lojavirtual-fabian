<?php

/**
 * Plugin name: Usuário faz check in do produto
 * Plugin uri: http://lojavirtualfabian.com/
 * Description: Usuário faz checkin no produto e salva no metadata do usuário
 * Version: 1.0
 * Author: Fabian
 * Author uri: http://github.com/fabiancarlos
 * License: GPLv2 or later
 */

require_once( ABSPATH . '/wp-includes/pluggable.php' );

if($_POST){
  print_r($_POST);
  $current_user = wp_get_current_user();
  $id = $current_user->ID;
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];

  if($id){
    print_r("User ID: ". $id);
    add_user_meta($id, 'latitude', $lat);
    add_user_meta($id, 'longitude', $lat);
    die;
  }else{
    http_response_code(404);die;
  }
}

function fc_call_geolocation() {
  ?>
  <style>
    .fc_call_geolocation{
      width: 100% !important;
      background-color: #c02e2e;
      border: 0;
      -webkit-border-radius: 2px;
      border-radius: 2px;
      -webkit-box-shadow: none;
      box-shadow: none;
      color: #fff;
      cursor: pointer;
      display: inline-block;
      font-size: 17px;
      font-size: 1.5rem;
      font-weight: 800;
      line-height: 1;
      padding: 1em 2em;
      text-shadow: none;
      -webkit-transition: background 0.2s;
      transition: background 0.2s;
      text-align: center;
    }
  </style>
  <script>
    function callGeolocation(){
      if(navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(savePosition);
      }else{
        alert("Geolocation não é suportado, tente outro navegador.");
      }
    }
    function savePosition(pos){
      jQuery.post( '/' ,{'lat':pos.coords.latitude,'lng':pos.coords.longitude},function(res){
        console.log(res);
        alert( "Checkin foi realizado!" );
      }).fail(function() {
        alert( "Faça login antes de fazer o Checkin" );
      });
    }
  </script>
  <?php
}

function fc_add_btn_checkin(){
  global $woocommerce;
  global $product;
  global $user;
  $result = '<hr>';

  if (is_user_logged_in()) {
    $result .= '<a href="#"
                  name="add-to-cart"
                  value="9"
                  class="single_add_to_cart_button fc_call_geolocation"
                  onclick="callGeolocation(); return false;">
                  Check-In</a>';
  } else {
    $result .= '<a href="#"
                 name="add-to-cart"
                 value="9"
                 class="single_add_to_cart_button fc_call_geolocation"
                 onclick="callGeolocation(); return false;">
                 Check-In(Desabilitado, faça login)</a>';
  }
  $result .= '<hr>';
  echo $result;
}

add_action( 'wp_footer', 'fc_call_geolocation' );
add_filter( 'woocommerce_after_add_to_cart_button', 'fc_add_btn_checkin');

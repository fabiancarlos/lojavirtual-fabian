<?php

/**
 * Plugin name: Total Vendido do Produto
 * Plugin uri: http://lojavirtualfabian.com/
 * Description: Agrupar a totalidade das vendas e mostrar abaixo do preço
 * Version: 1.0
 * Author: Fabian
 * Author uri: http://github.com/fabiancarlos
 * License: GPLv2 or later
 */

function get_orders_total_saled_by_product( $product_id ){
  $orders = wc_get_orders( array(
      'numberposts' => -1,
      'post_type' => 'shop_order',
      'post_status' => array('wc-completed') // completed status only
  ) );

  $total_saled = 0;
  foreach($orders as $order){
      if($order->has_status('completed') ){
        foreach($order->get_items() as $item_values)
          if( $item_values['product_id'] == $product_id ){
            $total = $order->get_total();
            $total_saled = $total_saled + $total;
            continue;
          }  
      }
  }
  return $total_saled;
}

function fc_agroup_total_sales_product($price){
  global $woocommerce;
  global $product;
  $id = $product->get_id();
  $total_sales = $product->get_total_sales();

  $another_total = get_orders_total_saled_by_product($id);

  $total = '<h5>';
  $total .= 'Total Vendidos: <br>';
  $total .= wc_price( $another_total );
  $total .= '</h5>';
  return $price . $total;
}

add_filter( 'woocommerce_get_price_html', 'fc_agroup_total_sales_product');

<?php

/* -------------------- VISUAL COMPOSER ----------------------- */
if ( ! class_exists( 'vcCalculator' ) ) {
	class vcCalculator {
		public function __construct() {
			add_shortcode( 'calculator-shortcode', array( 'vcCalculator', 'output' ) );
			if ( function_exists( 'vc_lean_map' ) ) {
				vc_lean_map( 'calculator-shortcode', array( 'vcCalculator', 'map' ) );
			}
		}
    public static function map() {
			return array(
				'name' => esc_html__( 'Calculadora', 'bizkaibus-calculadora' ),
				'description' => esc_html__( 'Calculadora de ofertas', 'bizkaibus-calculadora' ),
				'base' => 'vc_calculator',
				'category' => __('Bloques Especiales', 'bizkaibus-calculadora'),
				'icon' => 'dt_vc_ico_blog_posts',
				'params' => array(
					array(
					    'type' => 'textfield',
					    'heading' => __( 'Email de aviso', 'bizkaibus-calculadora' ),
					    'param_name' => 'email',
					    'admin_label' => true,
					    'group' => 'Email',
		 			), array(
					    'type' => 'textfield',
					    'heading' => __( 'Asunto email de aviso', 'bizkaibus-calculadora' ),
					    'param_name' => 'asunto_email',
					    'admin_label' => true,
					    'group' => 'Email',
		 			), array(
					    'type' => 'textfield',
					    'heading' => __( 'From email de presupuesto', 'bizkaibus-calculadora' ),
					    'param_name' => 'from_email_presupuesto',
					    'admin_label' => true,
					    'group' => 'Email',
		 			), array(
					    'type' => 'textfield',
					    'heading' => __( 'Asunto email de presupuesto', 'bizkaibus-calculadora' ),
					    'param_name' => 'asunto_email_presupuesto',
					    'admin_label' => true,
					    'group' => 'Email',
		 			), array(
					    'type' => 'textarea_html',
					    'heading' => __( 'Texto email de presupuesto', 'bizkaibus-calculadora' ),
					    'param_name' => 'content',
					    'admin_label' => true,
					    'group' => 'Email',
					)
				),
			);
		}

		public static function output( $atts, $content = null ) {
			$html = do_shortcode("[bizkaibus_calculadora email='".$atts['email']."' asunto_email='".$atts['asunto_email']."' asunto_email_presupuesto='".$atts['asunto_email_presupuesto']."' from_email_presupuesto='".$atts['from_email_presupuesto']."']".$content."[/bizkaibus_calculadora]");
			return $html;
		}
	}
}
new vcCalculator;

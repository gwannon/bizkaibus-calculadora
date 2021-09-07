<?php
/**
 * Plugin Name: Calculadora Bizkaibus
 * Plugin URI:  https://github.com/gwannon/bizkaibus-calculadora/
 * Description: Formulario para realizar un presupuesto de publicidad en los autobuses de Bizkaibus
 * Version:     1.0
 * Author:      Gwannon
 * Author URI:  https://github.com/gwannon/
 * License:     GNU General Public License v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bizkaibus-calculadora
 *
 * PHP 7.3
 * WordPress 5.5.3
 */

//ini_set("display_errors", 1);
define('BC_VER', '1.0');

//Cargamos el multi-idioma
function bizkaibus_calculadora_plugins_loaded() {
  load_plugin_textdomain('bizkaibus-calculadora', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}
add_action('plugins_loaded', 'bizkaibus_calculadora_plugins_loaded', 0);

//Elementos JS Composer personalizados ------------------------------
add_action( 'vc_before_init', 'bizkaibus_vc_before_init_actions' );
 
function bizkaibus_vc_before_init_actions() {
  // Require New Custom Element
  require_once( dirname(__FILE__).'/vc-elements/vc-calculator.php' );
}

//Shortcodes
function bizkaibus_calculadora_forms_shortcode($params = array(), $content = null) {
  $upload_dir = wp_upload_dir();
  $prices = [
    "standard" => [
      "circuitociudad" => 1300,
      "circuitocomarca" => 900,
      "oferta" => 570,
      "instalacion" => 360
    ], "expo" => [
      "circuitociudad" => 3500,
      "circuitocomarca" => 2500,
      "oferta" => 617,
      "instalacion" => 500
    ], "expo-xl" => [
      "circuitociudad" => 3500,
      "circuitocomarca" => 2500,
      "oferta" => 683,
      "instalacion" => 700
    ] 
  ];

  global $post;
  ob_start(); ?>

<div class="pop-up-mapa-bg">
  <div class="pop-up-mapa">
    <div class="pop-up-mapa-close">&#10005;</div>
    <img src="<?php echo plugin_dir_url(__FILE__); ?>/img/mapa.jpg">
  </div>
</div>
<form id="presupuestoform" method="post" action="<?php echo get_the_permalink(); ?>#presupuesto">

  <?php echo do_shortcode('[vc_row]
  [vc_column width="1/4"]
    [ultimate_heading main_heading="'.__("Comarca", "bizkaibus-calculadora").'" alignment="left" spacer="line_only" spacer_position="bottom" line_height="1" spacer_margin="margin-top:10px;margin-bottom:20px;"]
    [/ultimate_heading]
    <select name="circuito" required>
      <option value="circuitociudad">'.__("Gran Bilbao", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Arratia"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Arratia' ? " selected='selected'" : "").'>'.__("Arratia", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Ayala y Alto nervión"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Ayala y Alto nervión' ? " selected='selected'" : "").'>'.__("Ayala y Alto nervión", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Busturialdea"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Busturialdea' ? " selected='selected'" : "").'>'.__("Busturialdea", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Duranguesado"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitomarca-Duranguesado' ? " selected='selected'" : "").'>'.__("Duranguesado", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Encartaciones"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Encartaciones' ? " selected='selected'" : "").'>'.__("Encartaciones", "bizkaibus-calculadora").'</option>  
      <option value="circuitocomarca-Gorbeialdea"'. (isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Gorbeialdea' ? " selected='selected'" : "").'>'.__("Gorbeialdea", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Lea-Artibai"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Lea-Artibai' ? " selected='selected'" : "").'>'.__("Lea-Artibai", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Medio y Bajo Deba"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Medio y Bajo Deba' ? " selected='selected'" : "").'>'.__("Medio y Bajo Deba", "bizkaibus-calculadora").'</option>
      <option value="circuitocomarca-Uribe"'.(isset($_REQUEST['circuito']) && $_REQUEST['circuito'] == 'circuitocomarca-Uribe' ? " selected='selected'" : "").'>'.__("Uribe", "bizkaibus-calculadora").'</option>    
    </select><br/>
    <a href="#" class="pop-up-mapa-open">'.__("Ver mapa", "bizkaibus-calculadora").'</a>
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="20" height_on_mob="20"]
    [ultimate_heading main_heading="Datos" alignment="left" spacer="line_only" spacer_position="bottom" line_height="1" spacer_margin="margin-top:10px;margin-bottom:20px;"]
    [/ultimate_heading]
    <input type="text" name="nombre" value="'.strip_tags($_REQUEST['nombre']).'" placeholder="'.__("Nombre", "bizkaibus-calculadora").'" required/><br/>
    <input type="email" name="email" value="'.strip_tags($_REQUEST['email']).'" placeholder="'.__("Email", "bizkaibus-calculadora").'" required/><br/>
    <input type="text" name="telefono" value="'.strip_tags($_REQUEST['telefono']).'" placeholder="'.__("Teléfono", "bizkaibus-calculadora").'" required/><br/>
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="20" height_on_mob="20"]
    <button type="submit" class="hidden-mobile default-btn-shortcode dt-btn dt-btn-m rubberBand animate-element animation-builder link-hover-off btn-inline-left" name="send"><i class="icomoon-the7-font-the7-arrow-29"></i> '.__("Solicitar presupuesto", "bizkaibus-calculadora").'</button>
    <p class="hidden-mobile" style="margin-top: 10px; font-size: 12px; line-height: 14px;">'.__("Se enviará un email con el presupuesto<br/>al email que nos des.", "bizkaibus-calculadora").'</p>




    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20"]
  [/vc_column]
  [vc_column width="3/4"]
    [ultimate_heading main_heading="'.__("Posición", "bizkaibus-calculadora").'" alignment="left" spacer="line_only" spacer_position="bottom" line_height="1" spacer_margin="margin-top:10px;margin-bottom:20px;"]
    [/ultimate_heading]
    [ultimate_spacer height="10" height_on_tabs="10" height_on_tabs_portrait="10" height_on_mob_landscape="0" height_on_mob="0"]
    [vc_row_inner content_placement="top"]
      <label'.((isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'standard') || !isset($_REQUEST['posicion']) ? " class='checked'" : "").'>
        [vc_column_inner width="1/4"]
          [ultimate_heading main_heading="'.__("STANDARD", "bizkaibus-calculadora").'" heading_tag="h1" main_heading_color="#6b6c70" sub_heading_color="#7c287c" alignment="left" main_heading_margin="margin-bottom:10px;" sub_heading_font_size="desktop:20px;" sub_heading_line_height="desktop:26px;" sub_heading_margin="margin-bottom:20px;"]'.__("Hasta <strong>56,15%</strong> de descuento", "bizkaibus-calculadora").'[/ultimate_heading]
        [/vc_column_inner]
        [vc_column_inner width="3/4"]
          [just_icon icon_type="custom" icon_img="url^'.plugin_dir_url(__FILE__).'/img/autobus-standard.jpg|caption^null|alt^null|title^standard|description^null" img_width="700" icon_animation="fadeInRight"]
        [/vc_column_inner]
        <input type="radio" name="posicion" value="standard"'.(isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'standard' ? " checked='checked'" : "").' required />
        
      </label>  
    [/vc_row_inner]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20"]
    [vc_separator color="custom" style="dotted" border_width="3" accent_color="#898989"]
    [vc_row_inner equal_height="yes" content_placement="top"]
      <label'.(isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'expo' ? " class='checked'" : "").'>
        [vc_column_inner width="1/4"]
          [ultimate_heading main_heading="'.__("EXPO", "bizkaibus-calculadora").'" heading_tag="h1" main_heading_color="#6b6c70" sub_heading_color="#7c287c" alignment="left" main_heading_margin="margin-bottom:10px;" sub_heading_font_size="desktop:20px;" sub_heading_line_height="desktop:26px;" sub_heading_margin="margin-bottom:20px;"]'.__("Hasta <strong>82,37%</strong> de descuento", "bizkaibus-calculadora").'[/ultimate_heading]
        [/vc_column_inner]
        [vc_column_inner width="3/4"]
          [just_icon icon_type="custom" icon_img="url^'.plugin_dir_url(__FILE__).'/img/autobus-expo.jpg|caption^null|alt^null|title^autobus-expo|description^null" img_width="700" icon_animation="fadeInRight"]
        [/vc_column_inner]
        <input type="radio" name="posicion" value="expo"'.((isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'expo') || !isset($_REQUEST['posicion']) ? " checked='checked'" : "").' required />
      </label>
    [/vc_row_inner]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20"]
    [vc_separator color="custom" style="dotted" border_width="3" accent_color="#898989"]
    [vc_row_inner content_placement="top"]
      <label'.(isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'expo-xl' ? " class='checked'" : "").'>
        [vc_column_inner width="1/4"]
          [ultimate_heading main_heading="'.__("EXPO XL", "bizkaibus-calculadora").'" heading_tag="h1" main_heading_color="#6b6c70" sub_heading_color="#7c287c" alignment="left" main_heading_margin="margin-bottom:10px;" sub_heading_font_size="desktop:20px;" sub_heading_line_height="desktop:26px;" sub_heading_margin="margin-bottom:20px;"]'.__("Hasta <strong>80,49%</strong> de descuento", "bizkaibus-calculadora").'[/ultimate_heading]
        [/vc_column_inner]
        [vc_column_inner width="3/4"]
          [just_icon icon_type="custom" icon_img="url^'.plugin_dir_url(__FILE__).'/img/autobus-expo-xl.jpg|caption^null|alt^null|title^autobus-expo-xl|description^null" img_width="700" icon_animation="fadeInRight"]
        [/vc_column_inner]
        <input type="radio" name="posicion" value="expo-xl"'.(isset($_REQUEST['posicion']) && $_REQUEST['posicion'] == 'expo-xl' ? " checked='checked'" : "").' required />
      </label>
    [/vc_row_inner]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20"]
    [vc_separator color="custom" style="dotted" border_width="3" accent_color="#898989"]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="0" height_on_mob="0"]
    <button type="submit" class="hidden-desktop default-btn-shortcode dt-btn dt-btn-m rubberBand animate-element animation-builder link-hover-off btn-inline-left" name="send"><i class="icomoon-the7-font-the7-arrow-29"></i> '.__("Solicitar presupuesto", "bizkaibus-calculadora").'</button>
    <p class="hidden-desktop" style="margin-top: 10px; font-size: 12px; line-height: 14px;">'.__("Se enviará un email con el presupuesto<br/>al email que nos des.", "bizkaibus-calculadora").'</p>
  [/vc_column]
[/vc_row]'); ?>
  </form>
  <?php if(isset($_REQUEST['send'])) {

  if ($_REQUEST['circuito'] == 'circuitociudad') $circuito = 'circuitociudad';
  else $circuito = 'circuitocomarca'; 

  $price = $prices[$_REQUEST['posicion']][$circuito];
  $sale = $prices[$_REQUEST['posicion']]['oferta'];
  $discount = round(100 - (($sale * 100) / $price), 2);
  $instalation = $prices[$_REQUEST['posicion']]['instalacion'];
  $total = $instalation + ($sale * 3);

  echo do_shortcode('<div id="presupuesto" class="presupuesto">[vc_row content_placement="middle" css_animation="flipInX" bg_type="bg_color" css=".vc_custom_1628670383116{margin-right: 3% !important;margin-bottom: 20px !important;margin-left: 3% !important;background-color: rgba(124,40,124,0.19) !important;*background-color: rgb(124,40,124) !important;border-radius: 30px !important;}"]
  [vc_column]
    [ultimate_spacer height="10" height_on_tabs="10" height_on_tabs_portrait="10" height_on_mob_landscape="10" height_on_mob="10"]
    [ultimate_heading main_heading="'.__("Presupuesto", "bizkaibus-calculadora").'" sub_heading_color="#7c287c" spacer="line_with_icon" spacer_position="bottom" line_style="dotted" line_height="3" line_color="#ffffff" icon="icomoon-the7-font-the7-arrow-05" icon_size="20" main_heading_margin="margin-bottom:10px;" sub_heading_font_size="desktop:20px;" sub_heading_line_height="desktop:26px;" sub_heading_margin="margin-bottom:20px;" spacer_margin="margin-bottom:30px;" line_width="200" main_heading_style="font-weight:bold;"]
    [/ultimate_heading]
    [ultimate_heading main_heading="'.sprintf(__("Hemos enviado una versión digital del presupuesto a tu email (%s).", "bizkaibus-calculadora"), $_REQUEST['email']).'" main_heading_color="#7c287c" main_heading_font_size="desktop:25px;" main_heading_line_height="desktop:25px;" sub_heading_font_size="desktop:20px;" sub_heading_line_height="desktop:20px;" main_heading_margin="margin-top:10px;margin-bottom:10px;"][/ultimate_heading]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="20" height_on_mob="20"]
    
    [vc_row_inner equal_height="yes" content_placement="middle"]
      [vc_column_inner width="1/4"]
        [ultimate_heading main_heading="'.__("Tarifa al mes", "bizkaibus-calculadora").'" main_heading_font_size="desktop:22px;" main_heading_line_height="desktop:28px;" sub_heading_font_size="desktop:34px;" sub_heading_line_height="desktop:40px;" main_heading_margin="margin-bottom:10px;"]
          <p style="text-align: center;"><span style="text-decoration: line-through;">'.number_format($price, 0, ",",".").'€</span></p>
        [/ultimate_heading]
      [/vc_column_inner]
      [vc_column_inner width="1/4" css=".vc_custom_1628594734659{margin-right: 20px !important;margin-left: 20px !important;padding-top: 20px !important;padding-right: 5px !important;padding-bottom: 20px !important;padding-left: 5px !important;background-color: #ffffff !important;border-radius: 10px !important;}"]
        [ultimate_heading main_heading="'.__("Oferta del mes*", "bizkaibus-calculadora").'" sub_heading_color="#7c287c" main_heading_font_size="desktop:22px;" main_heading_line_height="desktop:28px;" sub_heading_font_size="desktop:34px;" sub_heading_line_height="desktop:40px;" sub_heading_style="font-weight:bold;"]
          <p style="text-align: center;">'.number_format($sale, 0, ",",".").'€</p>
        [/ultimate_heading]
      [/vc_column_inner]
      [vc_column_inner width="1/4"]
        [ultimate_heading main_heading="'.__("Descuento del", "bizkaibus-calculadora").'" main_heading_font_size="desktop:22px;" main_heading_line_height="desktop:28px;" sub_heading_font_size="desktop:34px;" sub_heading_line_height="desktop:40px;" main_heading_margin="margin-bottom:10px;"]
          <p style="text-align: center;">'.number_format($discount, 2, ",",".").'%</p>
        [/ultimate_heading]
      [/vc_column_inner]
      [vc_column_inner width="1/4"]
        [ultimate_heading main_heading="'.__("Producción e instalación", "bizkaibus-calculadora").'" main_heading_font_size="desktop:22px;" main_heading_line_height="desktop:28px;" sub_heading_font_size="desktop:34px;" sub_heading_line_height="desktop:40px;" main_heading_margin="margin-bottom:10px;"]
          <p style="text-align: center;">'.number_format($instalation, 0, ",",".").'€</p>
        [/ultimate_heading]
      [/vc_column_inner]
    [/vc_row_inner]
    [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="20" height_on_mob="20"]
    [ultimate_heading main_heading="'.__("Total por 3 meses + Producción + Instalación:", "bizkaibus-calculadora").' '.number_format($total, 0, ",",".").'€" main_heading_color="#7c287c" main_heading_font_size="desktop:30px;" main_heading_line_height="desktop:36px;" sub_heading_font_size="desktop:30px;" sub_heading_line_height="desktop:38px;" main_heading_margin="margin-top:10px;margin-bottom:10px;"]
    [/ultimate_heading]
    [vc_column_text]
      <p class="p1" style="text-align: center;"><em><span class="s1">'.__("* Oferta especial para un periodo de contratación de 3 meses. IVA no incluido.", "bizkaibus-calculadora").'</span></em></p>
    [/vc_column_text]
    [vc_btn title="'.__("Nuevo presupuesto", "bizkaibus-calculadora").'" style="custom" custom_background="#7c287c" custom_text="#ffffff" shape="square" size="lg" align="center" i_icon_fontawesome="fas fa-calculator" css_animation="slideInUp" add_icon="true" link="url:%23contacto"]
  [ultimate_spacer height="20" height_on_tabs="20" height_on_tabs_portrait="20" height_on_mob_landscape="20" height_on_mob="20"]
[/vc_column]
[/vc_row]</div>');

    //Guardamos log
    $f = fopen(dirname(__FILE__)."/leads.csv", "a+");
    $line = date("Y-m-d H:i:s").',"'.$_REQUEST['nombre'].'","'.$_REQUEST['email'].'","'.$_REQUEST['telefono'].'","'.$_REQUEST['posicion'].'","'.$_REQUEST['circuito'].'"'."\n";
    fwrite ($f, $line);
    fclose($f);

    //Mandamios email al admin
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Comunitac <'.$params['from_email_presupuesto'].'>', 'Reply-To: '.$params['email']);
    $message = "<b>Nombre:</b> ".$_REQUEST['nombre']."<br/>";
    $message .= "<b>Email:</b> ".$_REQUEST['email']."<br/>";
    $message .= "<b>Teléfono:</b> ".$_REQUEST['telefono']."<br/>";
    $message .= "<b>Tipo publicidad:</b> ".$_REQUEST['posicion']."<br/>";
    $message .= "<b>Comarca:</b> ".$_REQUEST['circuito']."<br/>";
    $message .= sprintf(__("<br/><br/>Puedes descargar todo los leads <a href='%s'>aquí</a>.", "bizkaibus-calculadora"), plugin_dir_url(__FILE__)."leads.csv");
    wp_mail ($params['email'], $params['asunto_email'], $message, $headers);

    //Mandamos el email del resupuesto
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Comunitac <'.$params['from_email_presupuesto'].'>', 'Reply-To: '.$params['from_email_presupuesto']);
    $message = str_replace("*|domain|*", plugin_dir_url(__FILE__), file_get_contents(dirname(__FILE__)."/templates/email.html"));
    $message = str_replace("*|precioalmes|*", number_format($price, 0, ",","."), $message);
    $message = str_replace("*|preciooferta|*", number_format($sale, 0, ",","."), $message);
    $message = str_replace("*|descuento|*", number_format($discount, 2, ",","."), $message);
    $message = str_replace("*|instalacion|*", number_format($instalation, 0, ",","."), $message);
    $message = str_replace("*|total|*", number_format($total, 0, ",","."), $message);
    $message = str_replace("*|nombre|*", strip_tags($_REQUEST['nombre']), $message);
    $message = str_replace("*|texto|*", $content, $message);
    if ($_REQUEST['circuito'] == 'circuitociudad') $message = str_replace("*|comarca|*", __("Gran Bilbao", "bizkaibus-calculadora"), $message);
    else $message = str_replace("*|comarca|*", strip_tags(__(str_replace("circuitocomarca-", "", $_REQUEST['circuito']), "bizkaibus-calculadora")), $message);
    
    if($_REQUEST['posicion'] == 'standard') {
    	$message = str_replace("*|posicion_img|*", plugin_dir_url(__FILE__).'/img/autobus-standard.jpg', str_replace("*|posicion|*", __("Standard", "bizkaibus-calculadora"), $message));
    } else if($_REQUEST['posicion'] == 'expo') {
    	$message = str_replace("*|posicion_img|*", plugin_dir_url(__FILE__).'/img/autobus-expo.jpg', str_replace("*|posicion|*", __("Expo", "bizkaibus-calculadora"), $message));
    } else if($_REQUEST['posicion'] == 'expo-xl') {
    	$message = str_replace("*|posicion_img|*", plugin_dir_url(__FILE__).'/img/autobus-expo-xl.jpg', str_replace("*|posicion|*", __("Expo XL", "bizkaibus-calculadora"), $message));
    }
    wp_mail ($_REQUEST['email'], $params['asunto_email_presupuesto'], $message, $headers);
    
  } ?>
  <script>  
    jQuery("label").click(function() {
      jQuery("label").removeClass("checked");
      jQuery(this).addClass("checked");
    });

    jQuery(".pop-up-mapa-open, .pop-up-mapa-close").click(function(e) {
      e.preventDefault();
      jQuery(".pop-up-mapa-bg").toggleClass("opened");
    });
  </script>
  <style>
  label {
    border: 2px dashed #fff;
    padding: 25px 0px 15px 25px;
    cursor: pointer;
        width: calc(100% - 25px);
  }
  #presupuestoform label.checked {
    border: 2px dashed #6f236f;

  }

  #presupuestoform label input[type=radio] {
    display: none;
  }

  .presupuesto > div > div > .vc_column-inner {
    background-color: rgba(124,40,124,0.19) !important;
    border-radius: 30px;
    padding: 30px;
    max-width: 1130px;
    margin: auto;
  }

  .pop-up-mapa-bg {
    position: fixed;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100vh;
    z-index: 1000;
    background-color: #4a4a4abf;
    display: none;
  }

  .pop-up-mapa-bg.opened {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .pop-up-mapa-bg .pop-up-mapa {
    background-color: #fff;
    padding: 30px 20px 30px 30px;
    border-radius: 10px;
    position: relative;
  }

  .pop-up-mapa-close {
    right: 15px;
    position: absolute;
    color: #6f236f;
    font-size: 35px;
    width: 41px;
    cursor: pointer;
    top: 24px;
  }

  #presupuestoform input[type="text"],
  #presupuestoform input[type="email"],
  #presupuestoform select {
    width: 100%;
    max-width: 211px;
  }
  
  @media (max-width: 767px) {
    .hidden-mobile {
  	display: none;
    }
    
  }

  @media (min-width: 768px) {
    .hidden-desktop {
  	display: none;
    }
    
  }
  </style>
  <?php $html = ob_get_clean(); 
  return $html;
}
add_shortcode('bizkaibus_calculadora', 'bizkaibus_calculadora_forms_shortcode');


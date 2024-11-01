<?php
/*
Plugin Name: WP Notas
Plugin URI: https://tuche.me/wpnotas
Description: Faça notas ou avisos de modo simples e pratico em seus posts e paginas no WordPress.
Version: 3.1.1
Author: Tuchê
Author URI: https://www.twitch.tv/tuche
*/

defined('ABSPATH') or die('Eu já sabia!');

$wpntsDefault = "3";
$wpntsOpts = array(
	"Clássico" => "classic",
	"Novo (3.0)" => "3"
);

function wpnts_setup_menu(){
	add_submenu_page('options-general.php', 'Opções do WP Notas', 'WP Notas', 'manage_options', 'wp-notas', 'wpnts_init');
}
 
function wpnts_init() {
	global $wpntsOpts;
?>
<div class="wrap">
	<h1>Opções do WP Notas</h1>
	<hr>
	<div style="margin: 9px 15px 4px 0; padding: 5px 30px; text-align: center; float: left; clear:left; border: solid 3px #cccccc; width: 600px;">
		<p>
		Me ajude a desenvolver mais plugins para a comunidade e adicionar novos recursos aos atuais!<br>
		<a href="https://streamlabs.com/tuche" target="blank">Doe qualquer quantia ;)</a> e se precisar de ajuda com o plugin, conte comigo!
		</p>
	</div>
	<form method="post" action="options.php">
		<?php settings_fields( 'wpnts-settings' ); ?>
		<?php do_settings_sections( 'wpnts-settings' ); ?>
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Tema</th>
				<td>
					<select id='wpnts-theme' name='wpnts-theme'>
					<?php
					foreach ($wpntsOpts as $name => $value) {
						$selected = '';
						if ($value == get_option('wpnts-theme')) {
							$selected = ' selected ';
						}
						echo '<option value="'.$value.'"'.$selected.'>'.$name."</option>";
					}
					?>

					</select>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>

	</form>
	
</div>
<?php
}

function wpnts_setting() {
	add_option('wpnts-theme', '3');
	register_setting('wpnts-settings', 'wpnts-theme'); 
}

if (is_admin()){
	add_action('admin_menu', 'wpnts_setup_menu');
	add_action('admin_init', 'wpnts_setting');
}

add_filter("plugin_action_links_".plugin_basename(__FILE__), "wpnts_actions");

function wpnts_actions($links) {
	$links[] = "<a href=\"https://streamlabs.com/tuche\">Doar</a>";
	$links[] = "<a href=\"".menu_page_url('wp-notas', false)."\">Configurar</a>";
	return $links;
}

function wpnts_dashicons() {
	wp_enqueue_style('dashicons');
}

function wpnts_head(){
	global $wpntsOpts, $wpntsDefault;
	
	$theme = (in_array(get_option('wpnts-theme'), $wpntsOpts))?get_option('wpnts-theme'):$wpntsDefault;
	if (!defined('WP_CONTENT_URL')) define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
	$plugin_url = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__));
	echo '<link rel="stylesheet" href="'.$plugin_url.'/style-'.$theme.'.css"'.' type="text/css" media="screen" />';
}

function wpnts_nota($atts, $content=''){
	return "<div class=\"wpnts wpnts-nota\"><p>$content</p></div>";
}

function wpnts_aviso($atts, $content=''){
	return "<div class=\"wpnts wpnts-aviso\"><p>$content</p></div>";
}

function wpnts_importante($atts, $content=''){
	return "<div class=\"wpnts wpnts-importante\"><p>$content</p></div>";
}

function wpnts_dica($atts, $content=''){
	return "<div class=\"wpnts wpnts-dica\"><p>$content</p></div>";
}

function wpnts_ajuda($atts, $content=''){
	return "<div class=\"wpnts wpnts-ajuda\"><p>$content</p></div>";
}

add_shortcode('note', 'wpnts_nota');
add_shortcode('nota', 'wpnts_nota');
add_shortcode('warning', 'wpnts_aviso');
add_shortcode('aviso', 'wpnts_aviso');
add_shortcode('important', 'wpnts_importante');
add_shortcode('importante', 'wpnts_importante');
add_shortcode('tip', 'wpnts_dica');
add_shortcode('dica', 'wpnts_dica');
add_shortcode('help', 'wpnts_ajuda');
add_shortcode('ajuda', 'wpnts_ajuda');

add_action('wp_enqueue_scripts', 'wpnts_dashicons' );
add_action('wp_head', 'wpnts_head');
?>
<?php
use Automattic\WooCommerce\Utilities\NumberUtil;
/**
 * Storefront automatically loads the core CSS even if using a child theme as it is more efficient
 * than @importing it in the child theme style.css file.
 *
 * Uncomment the line below if you'd like to disable the Storefront Core CSS.
 *
 * If you don't plan to dequeue the Storefront Core CSS you can remove the subsequent line and as well
 * as the sf_child_theme_dequeue_style() function declaration.
 */
//add_action( 'wp_enqueue_scripts', 'sf_child_theme_dequeue_style', 999 );

/**
 * Dequeue the Storefront Parent theme core CSS
 */
/*function sf_child_theme_dequeue_style() {
    wp_dequeue_style( 'storefront-style' );
    wp_dequeue_style( 'storefront-woocommerce-style' );
}*/

/**
 * Note: DO NOT! alter or remove the code above this text and only add your custom PHP functions below this text.
 */

function stmartin_load_theme_textdomain() {
	load_child_theme_textdomain( 'stmartin', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'stmartin_load_theme_textdomain' );


/**
 * Ajout d'un fichier de style pour les pages administrateur
 */
add_action( 'admin_enqueue_scripts', 'load_admin_style' );
function load_admin_style() {
    wp_enqueue_style( 'admin_css', get_stylesheet_directory_uri() . '/assets/css/admin.css', false, null);
}

/* Ne pas afficher l'UGS des pages produits */
add_filter( 'wc_product_sku_enabled', 'stmartin_remove_sku' );

function stmartin_remove_sku( $enabled ) {
	// Si on est pas dans l'admin et si on est sur la page produit
    if ( !is_admin() && is_product() ) {
        return false;
    }
    return $enabled;
}

/* Ajouter la police Exo */
function stmartin_add_google_fonts() {
	wp_register_style( 'googleFonts', 'https://fonts.googleapis.com/css2?family=Exo');
	wp_enqueue_style( 'googleFonts');
   }
add_action( 'wp_enqueue_scripts', 'stmartin_add_google_fonts' );

/* Ajouter mon fichier javascript */
function stmartin_add_scripts() {
	wp_enqueue_script('nav', get_stylesheet_directory_uri() . '/assets/js/nav.js');
   }
add_action( 'wp_enqueue_scripts', 'stmartin_add_scripts' );

/* Personnaliser ou supprimer le message pour dire que le produit a bien été ajouté au panier */
add_filter('wc_add_to_cart_message_html', 'stmartin_handler_function_name', 10, 2);
function stmartin_handler_function_name($message, $product_id) {
	return "";
}


/*
	* Ajouter dans la fiche produit au niveau des meta-données (avant la catégorie)
	* -- La liste des ingrédients
	*
	*/
add_action( 'woocommerce_product_meta_start', 'stmartin_add_product_meta' ,10);
function stmartin_add_product_meta () {
	global $product,$post;

	$list_of_ingredients = get_post_meta($post->ID,'listofingredients', true);
	if ($list_of_ingredients != '') {
		echo '<span class="posted_in">Composition: ' . esc_attr($list_of_ingredients) . '</span>';
	}
}

/*
 * Modification de la valeur proposée dans les notes de commande
 */
add_filter( 'woocommerce_checkout_fields', 'wc_change_order_comments_placeholder' );
function wc_change_order_comments_placeholder($fields){
	$fields['order']['order_comments']['placeholder'] = esc_attr__(
		'Example: My daughter Eléonore DUPONT 1SAPAT2 will collect my order for me',
		'stmartin'
	);
	return $fields;
}

/*
 * Changement du champ "reply to" lors de l'envoi de mail pour une nouvelle commande
 * On y met le nom le prénom et l'adresse mail du client pour permettre le "répondre à"
 * (j'ai ajouté un 's' à 'woocommerce_email_header' pour cibler le bon hook mais ça fonctionnait déjà ???)
 * https://gist.github.com/spigotdesign/a2735d18b8da7d101fe2fefa8b2df28d
 */
add_filter('woocommerce_email_headers', 'stmartin_reply_to_mail_filter', 11, 3);

function stmartin_reply_to_mail_filter($headers = '', $ordertype = 'new_order', $order = '') {

	if(!is_object($order) || empty($order) || $ordertype !== 'new_order') { return $headers; }

	$name = $order->get_billing_first_name().' '.$order->get_billing_last_name();
	$email = $order->get_billing_email();

	if(is_array($headers)) {
		$headers['Reply-To'] = "{$name} <{$email}>";
	} else {
		$headers .= "Reply-To: {$name} <{$email}>\r\n";
	}
	
	return $headers;
}



/* *
 * Mise en place des couleurs par défaut du customizer
 * Filtre de storefront/inc/customizer/class-storefront-customizer.php
 * */
add_filter('storefront_setting_default_values', 'stmartin_set_default_colors', 10, 1);

function stmartin_set_default_colors($args){
	$args = array(
		'storefront_heading_color'           => '#333333',
		'storefront_text_color'              => '#6d6d6d',
		'storefront_accent_color'            => '#1996A4',//avant modification : 7f54b3
		'storefront_hero_heading_color'      => '#000000',
		'storefront_hero_text_color'         => '#000000',
		'storefront_header_background_color' => '#ffffff',
		'storefront_header_text_color'       => '#404040',
		'storefront_header_link_color'       => '#645435ff',//avant modification : 333333
		'storefront_footer_background_color' => '#f0f0f0',
		'storefront_footer_heading_color'    => '#333333',
		'storefront_footer_text_color'       => '#6d6d6d',
		'storefront_footer_link_color'       => '#333333',
		'storefront_button_background_color' => '#dadcde',//avant modification : eeeeee
		'storefront_button_text_color'       => '#333333',
		'storefront_button_alt_background_color' => '#D6CEB5',//avant modification : 333333
		'storefront_button_alt_text_color'   => '#262626',//avant modification : ffffff
		'storefront_layout'                  => 'right',
		'background_color'                   => 'ffffff',
	);
	return $args;
}

/*
 * Ajout de la vidéo sur la homepage
 */
add_action( 'storefront_header', 'stmartin_add_homepage_background', 2);

function stmartin_add_homepage_background(){
	echo '<div id="homepagebackground">';
	echo '<video id="homepagevideo" data-type="mp4" src="http://localhost/lecomptoirdesaintmartin/site/wp-content/uploads/video-header-stmartin.mp4" poster="http://localhost/lecomptoirdesaintmartin/site/wp-content/uploads/stmartin-header.jpg" loop muted="" playsinline autoplay></video>';
	echo '<div class="blacklayer"></div>';
	echo '</div>';
}

/*
 * Ajout du titre (en dur à remplacer par le titre du site) et du bouton "Boutique" (en dur à remplacer par le nom de la page)
 */
add_action( 'storefront_header', 'stmartin_add_homepage_hero', 70);

function stmartin_add_homepage_hero(){
echo '
<div class="col-full">
	<div id="hero">
		<div class="hero-cache"><h2>le magasin pédagogique</h2></div>
		<div class="hero-cache"><h2>du lycée</h2></div>
		<a href="http://localhost/lecomptoirdesaintmartin/site/boutique/" class="button">
			<svg id="Groupe_14369" data-name="Groupe 14369" xmlns="http://www.w3.org/2000/svg" width="54.159" height="51.918" viewBox="0 0 54.159 51.918">
				<path id="Tracé_5411" data-name="Tracé 5411" d="M15.671,29A4.671,4.671,0,1,1,11,33.671,4.676,4.676,0,0,1,15.671,29Z" transform="translate(6.33 13.576)"></path>
				<path id="Tracé_5412" data-name="Tracé 5412" d="M32.171,29A4.671,4.671,0,1,1,27.5,33.671,4.676,4.676,0,0,1,32.171,29Z" transform="translate(7.753 13.576)"></path>
				<path id="Tracé_5413" data-name="Tracé 5413" d="M44.25,38.973H22.4a6.9,6.9,0,0,1-6.885-5.564l-3.742-18.7q-.024-.1-.04-.2L9.9,5.36H2.93A2.43,2.43,0,1,1,2.93.5h8.963a2.43,2.43,0,0,1,2.383,1.953L16.128,11.7h36.1a2.43,2.43,0,0,1,2.387,2.885L51.03,33.39l0,.018A6.9,6.9,0,0,1,44.25,38.973ZM22.381,34.113H44.248a2.05,2.05,0,0,0,2.01-1.645l3.033-15.905H17.1l3.181,15.894a2.051,2.051,0,0,0,2.053,1.655Z" transform="translate(-0.5 -0.5)"></path>
			</svg>
			Boutique
		</a>
	</div>
</div>
';
}

/* Pour déplacer le titre "Boutique" de la page des produits tout en haut donc au dessus de la sidebar
 * à corriger car il apparaît aussi sur la page produit du coup
 * On commente car pas d'intérêt si on a pas de filtrage à gauche
 */
/*add_filter( 'woocommerce_show_page_title', '__return_false');
add_action( 'woocommerce_before_main_content', 'stmartin_show_page_title',10);
function stmartin_show_page_title(){
	?>
	<h1 class="woocommerce-products-header__title page-title">
		<?php
			woocommerce_page_title();
		?>
	</h1>
	<?php
}*/


/* augmenter au maximum le facteur d'assombrissement des boutons au survol pour obtenir du noir */
add_filter( 'storefront_darken_factor', 'stmartin_set_darken_factor', 10);
function stmartin_set_darken_factor(){
	return -255;
}


/* Supprimer l'affichage des filtre actifs avant la liste des produits si utilisation de l'extension WCAPF */
/*remove_action( 'woocommerce_before_shop_loop', array( WCAPF_Hooks::instance(), 'active_filters_before_shop_loop' ), - 10 );
remove_action( 'woocommerce_before_template_part', array( WCAPF_Hooks::instance(), 'active_filters_before_no_products' ), - 10 );*/

/*
 * Pour ajouter le prix au kg ou au litre si disponible
 */
add_action( 'woocommerce_single_product_summary', 'stmartin_add_price_per_unitofmeasure', 11 );

function stmartin_add_price_per_unitofmeasure() {
	global $product, $post;
	$priceperunitofmeasure = get_post_meta($post->ID,'priceperunitofmeasure', true);
	if ($priceperunitofmeasure && !is_wp_error($priceperunitofmeasure)) {
		echo '<span class="pricePerUnitOfMeasure">' . __( 'or', 'stmartin' ) .' ' . $priceperunitofmeasure . '</span>';
	}
}

/**
 * Product page tabs.
 * Pour supprimer l'affichage des onglets (description et info complémentaires) dans la fiche produit
 */
/*remove_filter( 'woocommerce_product_tabs', 'woocommerce_default_product_tabs' );*/


/*
 * Remonter l'indication de promotion 
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 6 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

/*
 * Afficher le pourcentage de réduction des promotions WooCommerce
 */
add_filter('woocommerce_sale_flash', 'stmartin_display_sale_percentage', 10, 3);
function stmartin_display_sale_percentage($html, $post, $product) {
	$percentage = '';
	if ( $product->is_type( 'simple' ) ) {
		$percentage = ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100;
	} elseif ( $product->is_type( 'variable' ) ) {
        $max_percentage = 0;
        foreach ( $product->get_children() as $child_id ) {
           $variation = wc_get_product( $child_id );
           $price = $variation->get_regular_price();
           $sale = $variation->get_sale_price();
           if ( $price != 0 && ! empty( $sale ) ) {
            $percentage = ( $price - $sale ) / $price * 100;
           }
           else {
            return $html;
           }
           if ($max_percentage != 0) {
                if ($max_percentage != $percentage) {
                    return $html;
                }
            }
            else {
                $max_percentage = $percentage;
            }
        }
     }
	return '<span class="onsale">' . esc_html__( 'Sale', 'woocommerce' ) . ' -' . round($percentage) . '%</span>';
}
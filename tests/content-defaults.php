<?php
/** Standalone regression checks: php tests/content-defaults.php */
if ( 'cli' !== PHP_SAPI ) {
	exit;
}
define( 'ABSPATH', __DIR__ );
define( 'DAY_IN_SECONDS', 86400 );
function add_action( ...$args ) { $GLOBALS['actions'][ $args[0] ][] = $args[1]; }
function add_shortcode( ...$args ) {}
function plugin_dir_url( $file ) { return ''; }
function __( $value, $domain = '' ) { return $value; }
function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES ); }
function esc_attr( $value ) { return esc_html( $value ); }
function esc_html_e( $value, $domain = '' ) { echo esc_html( $value ); }
function sanitize_text_field( $value ) { return trim( strip_tags( $value ) ); }
function sanitize_textarea_field( $value ) { return sanitize_text_field( $value ); }
function esc_url_raw( $value ) { return preg_match( '/^javascript:/i', $value ) ? '' : $value; }
function esc_url( $value ) { return esc_attr( esc_url_raw( $value ) ); }
function get_option( $key, $default = false ) { return $GLOBALS['options'][ $key ] ?? $default; }
function get_post_meta( $id, $key, $single = false ) { return $GLOBALS['meta'][ $id ][ $key ] ?? ''; }
function get_post_field( $key, $id, $context = '' ) { return $GLOBALS['posts'][ $id ][ $key ] ?? ''; }
function wp_strip_all_tags( $value ) { return strip_tags( $value ); }
function strip_shortcodes( $value ) { return $value; }
function sanitize_hex_color( $value ) { return preg_match( '/^#[a-f0-9]{6}$/i', $value ) ? $value : ''; }
function shortcode_atts( $defaults, $atts, $shortcode ) { return array_merge( $defaults, $atts ); }
function absint( $value ) { return abs( (int) $value ); }
function wp_unique_id( $prefix = '' ) { static $id = 0; return $prefix . ++$id; }
function get_post_type( $id ) { return $GLOBALS['post_types'][ $id ] ?? false; }
function delete_transient( $key ) { $GLOBALS['deleted_transients'][] = $key; }
function get_transient( $key ) { return array( 1 ); }
function wp_enqueue_style( ...$args ) {}
function wp_enqueue_script( ...$args ) {}
function get_the_ID() { return 1; }
function get_post_thumbnail_id( $id = 1 ) { return $GLOBALS['image_id']; }
function wp_get_attachment_caption( $id ) { return $GLOBALS['captions'][ $id ] ?? ''; }
function wp_trim_words( $text, $count, $more ) { return $text; }
function wpautop( $text ) { return '<p>' . $text . '</p>'; }
function wp_json_encode( $value ) { return json_encode( $value ); }
function has_post_thumbnail() { return (bool) get_post_thumbnail_id(); }
function wp_get_attachment_image( ...$args ) { return '<img alt="">'; }
function wp_kses_post( $value ) { return $value; }
function wp_reset_postdata() {}
class WP_Query {
	private $pending = true;
	public function __construct( $args ) {}
	public function have_posts() { return $this->pending; }
	public function the_post() { $this->pending = false; }
}
require dirname( __DIR__ ) . '/gallery-rendom.php';

function check( $condition, $message ) {
	if ( ! $condition ) { throw new RuntimeException( $message ); }
	echo 'PASS: ' . $message . PHP_EOL;
}

$GLOBALS['options']['gallery_rendom_content_defaults'] = array(
	'title' => 'Shared title', 'description' => 'Shared description',
	'primary_label' => 'Explore', 'primary_url' => 'https://example.com',
	'secondary_label' => 'About', 'secondary_url' => 'https://example.com/about',
	'image_position' => 'center top',
);
$content = gallery_rendom_get_item_content( 1 );
check( $content === $GLOBALS['options']['gallery_rendom_content_defaults'], 'Blank fields inherit all defaults' );
$GLOBALS['posts'][1] = array( 'post_title' => 'Image title', 'post_content' => '<p>Image description</p>' );
$GLOBALS['meta'][1]['_gallery_rendom_primary_label'] = 'Custom label';
$GLOBALS['meta'][1]['_gallery_rendom_image_position'] = 'left center';
$content = gallery_rendom_get_item_content( 1 );
check( 'Image title' === $content['title'] && 'Image description' === $content['description'], 'Item title and content override defaults' );
check( 'Custom label' === $content['primary_label'] && 'https://example.com' === $content['primary_url'], 'Button fields inherit independently' );
check( 'left center' === $content['image_position'], 'Explicit focal position overrides default' );
$GLOBALS['meta'][1]['_gallery_rendom_use_default_title'] = '1';
check( 'Shared title' === gallery_rendom_get_item_content( 1 )['title'], 'Checked item title override uses the shared default title' );
$GLOBALS['meta'][1]['_gallery_rendom_use_default_title'] = '';
$GLOBALS['posts'][1]['post_excerpt'] = 'Item excerpt';
check( 'Item excerpt' === gallery_rendom_get_item_content( 1 )['description'], 'Excerpt takes priority over content' );
$GLOBALS['posts'][1] = array( 'post_title' => '  ', 'post_content' => '<!-- wp:paragraph --><p></p><!-- /wp:paragraph -->' );
check( 'Shared description' === gallery_rendom_get_item_content( 1 )['description'], 'Empty block markup inherits description' );
check( 'Shared title' === gallery_rendom_get_item_content( 1 )['title'], 'Whitespace title inherits default' );
check( '' === gallery_rendom_sanitize_optional_position( '' ), 'Blank focal position remains blank for storage' );
$clean = gallery_rendom_sanitize_content_defaults( array( 'title' => array(), 'primary_url' => 'javascript:alert(1)', 'image_position' => 'bad', 'caption' => 'Not allowed' ) );
check( '' === $clean['title'] && '' === $clean['primary_url'] && 'center center' === $clean['image_position'] && ! isset( $clean['caption'] ), 'Malformed values and unknown settings are rejected' );
check( 'center center' === gallery_rendom_sanitize_content_defaults( 'invalid' )['image_position'], 'Malformed option has safe defaults' );
$GLOBALS['image_id'] = 10;
$GLOBALS['captions'][10] = 'Media caption <script>alert(1)</script>';
$GLOBALS['meta'][1]['_gallery_rendom_caption'] = 'Legacy caption';
$html = gallery_rendom_render_shortcode( array() );
check( false !== strpos( $html, 'Media caption &lt;script&gt;' ) && false === strpos( $html, 'Legacy caption' ), 'Caption comes from attachment and is escaped' );
check( false !== strpos( $html, ' hidden>' ) && false !== strpos( $html, 'aria-expanded="false"' ), 'Caption starts hidden with collapsed button' );
check( false !== strpos( $html, 'Shared title' ) && false !== strpos( $html, 'Shared description' ), 'Renderer uses inherited content' );
$second_html = gallery_rendom_render_shortcode( array() );
preg_match_all( '/\bid="([^"]+)"/', $html . $second_html, $ids );
check( count( $ids[1] ) === count( array_unique( $ids[1] ) ), 'Repeated gallery items have unique HTML IDs' );
foreach ( array( $html, $second_html ) as $instance ) {
	preg_match_all( '/aria-(?:controls|labelledby|describedby)="([^"]+)"/', $instance, $references );
	foreach ( $references[1] as $reference ) {
		check( false !== strpos( $instance, 'id="' . $reference . '"' ), 'Accessibility reference targets its own gallery instance' );
	}
}
$GLOBALS['post_types'][1] = 'gallery_rendom_item';
$GLOBALS['deleted_transients'] = array();
foreach ( $GLOBALS['actions']['before_delete_post'] ?? array() as $callback ) {
	$callback( 1 );
}
check( array( GALLERY_RENDOM_ITEM_IDS_TRANSIENT ) === $GLOBALS['deleted_transients'], 'Permanent deletion clears cache while the post still exists' );
$GLOBALS['deleted_transients'] = array();
$GLOBALS['post_types'][2] = 'post';
gallery_rendom_clear_item_ids_cache( 2 );
check( array() === $GLOBALS['deleted_transients'], 'Unrelated post deletion leaves gallery cache intact' );
$GLOBALS['captions'][10] = '';
$html = gallery_rendom_render_shortcode( array() );
check( false === strpos( $html, 'gallery-rendom__info' ), 'Empty attachment caption hides info button despite legacy caption' );
$GLOBALS['image_id'] = 0;
check( false === strpos( gallery_rendom_render_shortcode( array() ), 'gallery-rendom__info' ), 'Missing featured image has no caption control' );
$GLOBALS['options'] = array();
$html = gallery_rendom_render_shortcode( array() );
check( false === strpos( $html, 'aria-labelledby=' ) && false === strpos( $html, 'class="gallery-rendom__title"' ), 'Missing title omits empty heading and label reference' );
echo "All checks passed. These isolated stubs do not replace WordPress browser testing.\n";

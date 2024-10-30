<?php
/*
Plugin Name: Kau-Boy's OpenSearch
Plugin URI: http://kau-boys.de/wordpress/kau-boys-opensearch/
Description: Integrates an OpenSearchDescription so that users can add your blog as a search location to their browsers.
Version: 0.1.2
Author: Bernhard Kau
Author URI: http://kau-boys.de/
*/

define('OPENSEARCH_URL', WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));

function opensearch_parse_request($request){
	if($request->request == 'opensearchdescription.xml'){
		header("Content-type: text/xml; charset=utf-8");
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		?>
		<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
			<ShortName><?php bloginfo('name'); ?></ShortName>
			<Description><?php echo sprintf(__('Use %s to search the blog.', 'opensearch'), get_bloginfo('name')) ?></Description>
			<LongName><?php bloginfo('description'); ?></LongName>
			<Contact><?php bloginfo('description'); ?></Contact>
			<Image height="16" width="16" type="image/x-icon"><?php echo get_option('template_url') ?>/favicon.ico</Image>
			<Image height="64" width="64" type="image/png"><?php echo get_option('template_url') ?>/favicon.png</Image>
			<Query role="example" searchTerms="tag" />
			<Url type="text/html" template="<?php echo get_option('home') ?>/?s={searchTerms}"/>
			<Url type="application/x-suggestions+json" method="get" template="<?php echo OPENSEARCH_URL ?>opensearch_suggest.php?q={searchTerms}" />
			<Url type="application/opensearchdescription+xml" rel="self" template="<?php echo get_option('home') ?>/opensearchdescription.xml" />
			<Developer>Bernhard Kau (http://kau-boys.de)</Developer>
			<Attribution>
				Search data Copyright <?php echo date('Y') ?>, <?php bloginfo('name'); ?>, All Rights Reserved
			</Attribution>
			<SyndicationRight>open</SyndicationRight>
			<AdultContent>false</AdultContent>
			<Language><?php bloginfo('language'); ?></Language>
			<OutputEncoding><?php bloginfo('charset'); ?></OutputEncoding>
			<InputEncoding><?php bloginfo('charset'); ?></InputEncoding>
			<moz:SearchForm><?php echo get_option('home') ?>/?s={searchTerms}</moz:SearchForm>
		</OpenSearchDescription>
		<?php
		exit();
	}
}

function opensearch_header() {
	?>
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo get_option('home') ?>/opensearchdescription.xml" title="<?php echo get_option('blogname') ?>" />
	<?php
}

/**
 * Add deprecation notice in WP Admin.
 */
function opensearch_deprecation_notice() {
	// Only show notice for users who can actually uninstall or update plugins.
	if ( ! current_user_can( 'delete_plugins' ) && ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	global $wp_version;
	?>
	<div class="notice notice-warning">
		<p>
			<?php echo wp_kses(
				__( 'Kau-Boy\'s OpenSearch <b>is deprecated and will be removed</b> from the plugin directory on <b>July 29, 2023</b>, 13 years after its first release. If you still want to use OpenSearch on your site, I recommend switching to <a href="https://de.wordpress.org/plugins/open-search-document/">Open Search Document</a> which is still actively supported.', 'kau-boys-opensearch' ),
				array( 'a' => array( 'href' => array() ), 'b' => array() )
			); ?>
		</p>
	</div>
	<?php
}

add_action('wp_head', 'opensearch_header', 1);
add_action('parse_request', 'opensearch_parse_request', 1);
add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', 'opensearch_deprecation_notice' );
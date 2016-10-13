<?php

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

use WP_CLI\Utils;

/**
 * Generate a Movefile for Wordmove.
 *
 * @subpackage commands/community
 * @maintainer Takayuki Miyauchi
 */
class WP_CLI_Scaffold_Movefile extends WP_CLI_Command
{
	private $exclude = array(
		'.git/',
		'.gitignore',
		'.sass-cache/',
		'bin/',
		'tmp/*',
		'Gemfile*',
		'Movefile',
		'wp-config.php',
		'wp-content/*.sql',
	);

	/**
	 * Generate a Movefile for Wordmove.
	 *
	 * ## OPTIONS
	 *
	 * [--environment=<environment>]
	 * : The environment such as local, production, staging, etc.
	 * ---
	 * default: production
	 * ---
	 *
	 * [--movefile=<movefile>]
	 * : Path to the Movefile.
	 *
	 * ## EXAMPLES
	 *
	 *     wp scaffold movefile
	 *
	 *     wp scaffold movefile --environment=production
	 *
	 *     wp scaffold movefile --movefile=/path/to/Movefile
	 *
	 */
	function __invoke( $args, $assoc_args ) {
		$vars = array(
			'site_url' => site_url(),
			'wordpress_path' => WP_CLI::get_runner()->config['path'],
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASSWORD,
			'db_host' => DB_HOST,
			'db_charset' => DB_CHARSET,
		);

		$movefile = Utils\mustache_render(
			dirname( __FILE__ ) . '/templates/Movefile.mustache',
			$vars
		);

		WP_CLI::line( $movefile );
	}
}

WP_CLI::add_command( 'scaffold movefile', 'WP_CLI_Scaffold_Movefile'  );

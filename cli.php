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
	/**
	 * Generate a Movefile for Wordmove.
	 *
	 * ## OPTIONS
	 *
	 * [--output]
	 * : Output the contents of the Movefile.
	 *
	 * [--force]
	 * : Overwrite Movefile that already exist.
	 *
	 * ## EXAMPLES
	 *
	 *     # Basic usage
	 *     $ wp scaffold movefile
	 *     Success: /path/to/Movefile
	 *
	 *     # Overwrite when Movefile exists
	 *     $ wp scaffold movefile
	 *     Warning: File already exists.
	 *     Do you want to overwrite? [y/N]y
	 *     Success: /path/to/Movefile
	 *
	 */
	function __invoke( $args, $assoc_args )
	{
		$vars = array(
			'home_url'       => home_url(),
			'wordpress_path' => untrailingslashit( WP_CLI::get_runner()->config['path'] ),
			'db_name'        => DB_NAME,
			'db_user'        => DB_USER,
			'db_pass'        => DB_PASSWORD,
			'db_host'        => DB_HOST,
			'db_charset'     => DB_CHARSET,
		);

		$movefile = WP_CLI\Utils\mustache_render(
			self::get_template(),
			$vars
		);

		if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'output' ) ) {
			WP_CLI::line( $movefile );
		} else {
			if ( empty( $args[0] ) ) {
				$filename = getcwd() . "/Movefile";
			} else {
				$filename = $args[0];
			}

			$force = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force' );
			$result = $this->create_file( $filename, $movefile, $force );

			if ( $result ) {
				WP_CLI::success( $filename );
			} else {
				WP_CLI::success( "Movefile wasn't overwrited." );
			}
		}
	}

	private function create_file( $filename, $contents, $force )
	{
		$wp_filesystem = $this->init_wp_filesystem();

		$should_write_file = $this->prompt_if_files_will_be_overwritten( $filename, $force );
		if ( $should_write_file ) {
			$wp_filesystem->mkdir( dirname( $filename ) );
			if ( ! $wp_filesystem->put_contents( $filename, $contents ) ) {
				WP_CLI::error( "Error creating file: $filename" );
			}
			return true;
		}

		return false;
	}

	/**
	 * Initialize WP Filesystem
	 */
	private function init_wp_filesystem()
	{
		global $wp_filesystem;
		WP_Filesystem();
		return $wp_filesystem;
	}

	private function prompt_if_files_will_be_overwritten( $filename, $force )
	{
		$should_write_file = false;
		if ( ! file_exists( $filename ) ) {
			return true;
		}
		WP_CLI::warning( 'File already exists.' );
		if ( $force ) {
			$should_write_file = true;
		} else {
			$should_write_file = cli\confirm( 'Do you want to overwrite', false );
		}

		return $should_write_file;
	}

	/**
	 * Get path to the mustache template.
	 *
	 * @return string Path to the template.
	 */
	private static function get_template()
	{
		$home = getenv( 'HOME' );
		if ( !$home ) {
			// sometime in windows $HOME is not defined
			$home = getenv( 'HOMEDRIVE' ) . getenv( 'HOMEPATH' );
		}

		$config = $home . '/.wp-cli';

		if ( is_file( $config . '/Movefile.mustache' ) ) {
			return $config . '/Movefile.mustache';
		} else {
			return dirname( __FILE__ ) . '/templates/Movefile.mustache';
		}
	}
}

WP_CLI::add_command( 'scaffold movefile', 'WP_CLI_Scaffold_Movefile'  );

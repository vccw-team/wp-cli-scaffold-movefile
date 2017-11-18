<?php

use WP_CLI\Utils;

/**
 * Generate a movefile.yml for Wordmove.
 *
 * @subpackage commands/community
 * @maintainer Takayuki Miyauchi
 */
class WP_CLI_Scaffold_Movefile extends WP_CLI_Command
{
	/**
	 * Generate a movefile.yml for Wordmove.
	 *
	 * ## OPTIONS
	 *
	 * [--output]
	 * : Output the contents of the movefile.yml.
	 *
	 * [--force]
	 * : Overwrite movefile.yml that already exist.
	 *
	 * ## EXAMPLES
	 *
	 *     # Basic usage
	 *     $ wp scaffold movefile
	 *     Success: /path/to/movefile.yml
	 *
	 *     # Overwrite when movefile.yml exists
	 *     $ wp scaffold movefile
	 *     Warning: File already exists.
	 *     Do you want to overwrite? [y/N]y
	 *     Success: /path/to/movefile.yml
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

		$movefile = self::mustache_render(
			$movefile_template = $this->get_movefile_template(),
			$vars
		);

		if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'output' ) ) {
			WP_CLI::line( $movefile );
		} else {
			if ( empty( $args[0] ) ) {
				$filename = getcwd() . "/movefile.yml";
			} else {
				$filename = $args[0];
			}

			$force = \WP_CLI\Utils\get_flag_value( $assoc_args, 'force' );
			$result = $this->create_file( $filename, $movefile, $force );

			if ( $result ) {
				WP_CLI::success( $filename );
			} else {
				WP_CLI::success( "movefile.yml wasn't overwritten." );
			}
		}
	}

	private function create_file( $filename, $contents, $force )
	{
		/**
		 * @var WP_Filesystem_Base $wp_filesystem
		 */
		$wp_filesystem = $this->init_wp_filesystem();

		$should_write_file = $this->prompt_if_files_will_be_overwritten( $filename, $force );
		if ( $should_write_file ) {
			$wp_filesystem->mkdir( dirname( $filename ) );
			if ( ! $wp_filesystem->put_contents( $filename, $contents ) ) {
				WP_CLI::error( "Could not create file: $filename" );
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
	private function get_movefile_template()
	{
		$home = getenv( 'HOME' );
		if ( !$home ) {
			// sometime in windows $HOME is not defined
			$home = getenv( 'HOMEDRIVE' ) . getenv( 'HOMEPATH' );
		}

		$config = $home . '/.wp-cliss';

		if ( is_file( $config . '/movefile.mustache' ) ) {
			return $config . '/movefile.mustache';
		} else {
			return 'movefile.mustache';
		}
	}

	/**
	 * Localize path to template
	 */
	private static function mustache_render( $template, $data = array() ) {
		return Utils\mustache_render( dirname( dirname( __FILE__ ) ) . '/templates/' . $template, $data );
	}
}

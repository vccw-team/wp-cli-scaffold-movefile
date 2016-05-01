<?php

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

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
		$yaml = spyc_load_file( dirname( __FILE__ ) . '/templates/Movefile' );
		unset( $yaml['production']['ssh'] );

		if ( ! empty( $assoc_args['movefile'] ) ) {
			if ( file_exists( realpath( $assoc_args['movefile'] ) ) ) {
				$yaml = spyc_load_file( $assoc_args['movefile'] );
			}
		}

		if ( empty( $assoc_args['environment'] ) ) {
			$assoc_args['environment'] = 'production';
		}

		$yaml[ $assoc_args['environment'] ]['vhost'] = home_url();
		$yaml[ $assoc_args['environment'] ]['wordpress_path'] = WP_CLI::get_runner()->config['path'];
		$yaml[ $assoc_args['environment'] ]['database']['name'] = DB_NAME;
		$yaml[ $assoc_args['environment'] ]['database']['user'] = DB_USER;
		$yaml[ $assoc_args['environment'] ]['database']['password'] = DB_PASSWORD;
		$yaml[ $assoc_args['environment'] ]['database']['host'] = DB_HOST;
		$yaml[ $assoc_args['environment'] ]['database']['charset'] = DB_CHARSET;
		if ( 'local' !== $assoc_args['environment'] ) {
			if ( empty( $yaml[ $assoc_args['environment'] ]['exclude'] ) ) {
				$yaml[ $assoc_args['environment'] ]['exclude'] = $this->exclude;
			}
			if ( empty( $yaml[ $assoc_args['environment'] ]['ssh'] ) ) {
				$yaml[ $assoc_args['environment'] ]['ssh'] = array(
					'host' => preg_replace( "#https?://#", "", home_url() ),
					'user' => get_current_user()
				);
			}
		}

		$yaml = Spyc::YAMLDump( $yaml, 2, 0 );
		$yaml = substr( $yaml, 4 ); // Spyc::YAMLDump() prepends "---\n" :(
		WP_CLI::line( $yaml );
	}
}

WP_CLI::add_command( 'scaffold movefile', 'WP_CLI_Scaffold_Movefile'  );

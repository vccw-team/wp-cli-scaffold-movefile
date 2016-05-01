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
	/**
	 * Generate a Movefile for Wordmove.
	 *
	 * ## OPTIONS
	 *
	 * [--environment=<environment>]
	 * : The environment such as production, staging, etc.
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
	function __invoke( $args ) {

	}
}

WP_CLI::add_command( 'scaffold movefile', 'WP_CLI_Scaffold_Movefile'  );

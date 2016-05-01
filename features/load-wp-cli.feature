Feature: Test that WP-CLI loads.

  Scenario: WP-CLI loads for your tests
    Given a WP install

    When I run `wp help scaffold movefile`
    Then the return code should be 0

    When I run `wp scaffold movefile`
    Then the return code should be 0

    When I run `wp scaffold movefile`
    Then STDOUT should contain:
      """
      wp_cli_test
      """

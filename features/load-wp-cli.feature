Feature: Test that WP-CLI loads.

  Scenario: WP-CLI loads for your tests
    Given a WP install

    When I run `wp help scaffold movefile`
    Then the return code should be 0

    When I run `wp scaffold movefile`
    Then the return code should be 0
    And the Movefile file should exist

    When I run `wp scaffold movefile`
    Then the return code should be 0
    And the Movefile file should exist

    When I run `wp scaffold movefile /tmp/Movefile`
    Then the return code should be 0
    And the /tmp/Movefile file should exist

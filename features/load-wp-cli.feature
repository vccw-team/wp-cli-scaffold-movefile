Feature: Test that WP-CLI loads.

  Scenario: Scaffold a Movefile
    Given a WP install

    When I run `wp help scaffold movefile`
    Then the return code should be 0

    When I run `wp scaffold movefile`
    Then the return code should be 0
    And the Movefile file should exist
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Overwrite Movefile by prompting yes
    Given a WP install
    And a Movefile file:
      """
      Hello
      """
    And a session file:
      """
      y
      """

    When I try `wp scaffold movefile < session`
    Then the return code should be 0
    And the Movefile file should exist
    And the Movefile file should contain:
      """
      local:
      """
    And STDOUT should contain:
      """
      Success:
      """
    And STDERR should be:
      """
      Warning: File already exists.
      """

  Scenario: Don't overwrite Movefile
    Given a WP install
    And a Movefile file:
      """
      Hello
      """
    And a session file:
      """
      n
      """

    When I try `wp scaffold movefile < session`
    Then the return code should be 0
    And the Movefile file should exist
    And the Movefile file should contain:
      """
      Hello
      """
    And STDOUT should contain:
      """
      Success:
      """
    And STDERR should be:
      """
      Warning: File already exists.
      """

  Scenario: Force overwrite Movefile
    Given a WP install
    And a Movefile file:
      """
      Hello
      """

    When I try `wp scaffold movefile --force`
    Then the return code should be 0
    And the Movefile file should exist
    And the Movefile file should contain:
      """
      local:
      """
    And STDERR should be:
      """
      Warning: File already exists.
      """

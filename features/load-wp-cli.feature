Feature: Test that WP-CLI loads.

  Scenario: Scaffold a Movefile.yml
    Given a WP install

    When I run `wp help scaffold Movefile`
    Then the return code should be 0

    When I run `wp scaffold Movefile`
    Then the return code should be 0
    And the Movefile.yml file should exist
    And the Movefile.yml file should contain:
      """
      local:
      """
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Overwrite Movefile.yml by prompting yes
    Given a WP install
    And a Movefile.yml file:
      """
      Hello
      """
    And a session file:
      """
      y
      """

    When I try `wp scaffold Movefile < session`
    Then the return code should be 0
    And the Movefile.yml file should exist
    And the Movefile.yml file should contain:
      """
      local:
      """
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Don't overwrite Movefile.yml
    Given a WP install
    And a Movefile.yml file:
      """
      Hello
      """
    And a session file:
      """
      n
      """

    When I try `wp scaffold Movefile < session`
    Then the return code should be 0
    And the Movefile.yml file should exist
    And the Movefile.yml file should contain:
      """
      Hello
      """
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Force overwrite Movefile.yml
    Given a WP install
    And a Movefile.yml file:
      """
      Hello
      """

    When I try `wp scaffold Movefile --force`
    Then the return code should be 0
    And the Movefile.yml file should exist
    And the Movefile.yml file should contain:
      """
      local:
      """

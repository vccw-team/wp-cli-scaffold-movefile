Feature: Test that WP-CLI loads.

  Scenario: Scaffold a movefile.yml
    Given a WP install

    When I run `wp help scaffold movefile`
    Then the return code should be 0

    When I run `wp scaffold movefile`
    Then the return code should be 0
    And the movefile.yml file should exist
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Overwrite movefile.yml by prompting yes
    Given a WP install
    And a movefile.yml file:
      """
      Hello
      """
    And a session file:
      """
      y
      """

    When I run `wp scaffold movefile < session`
    Then the return code should be 0
    And the movefile.yml file should exist
    And the movefile.yml file should be:
      """
      local:
      """
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Don't overwrite movefile.yml
    Given a WP install
    And a movefile.yml file:
      """
      Hello
      """
    And a session file:
      """
      n
      """

    When I run `wp scaffold movefile < session`
    Then the return code should be 0
    And the movefile.yml file should exist
    And the movefile.yml file should contain:
      """
      Hello
      """
    And STDOUT should contain:
      """
      Success:
      """

  Scenario: Force overwrite movefile.yml
    Given a WP install
    And a movefile.yml file:
      """
      Hello
      """

    When I run `wp scaffold movefile --force`
    Then the return code should be 0
    And the movefile.yml file should exist
    And the movefile.yml file should contain:
      """
      local:
      """

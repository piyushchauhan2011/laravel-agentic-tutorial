Feature: Login page with Playwright
  In order to validate browser behavior with Gherkin syntax
  As a developer
  I want to test the login page using Behat and Playwright

  Scenario: Login form renders and accepts email input
    Given I open "/login" in Playwright
    Then I should be on "/login" in Playwright
    And I should see "Sign in" in Playwright
    And I should see "Forgot your password?" in Playwright
    And I should see "Log in" in Playwright
    And the "Email" field should have value "" in Playwright
    When I fill "Email" with "candidate@example.com" in Playwright
    Then the "Email" field should have value "candidate@example.com" in Playwright

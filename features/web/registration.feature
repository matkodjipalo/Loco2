Feature: Registration
  In order to be able to access site for managing todo list
  As a user
  I need to be able to register for a new account

  Scenario: Enter data needed
    Given I am on "/"
    When I click "sign up"
    When I fill in "First Name" with "Matko"
    When I fill in "Last Name" with "Đipalo"
    When I fill in "Email" with "matkodjipalo@gmail.com"
    When I fill in "Password" with "p@ssword"
    When I fill in "Repeat Password" with "p@ssword"
    When I press "Register"
    Then I should be on "/login"
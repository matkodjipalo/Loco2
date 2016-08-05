Feature: ToDo List Dashboard
  In order to maintain my todo lists
  As a user
  I need to be able to add/edit/delete todo lists

  Background:
    Given I am logged in as an user

  Scenario: List current todo lists
    Given there are 3 todo lists
    And I am on "/"
    Then I should see 3 todo lists

  Scenario: Add a new todo list
    Given I am on "/"
    When I click "New ToDo List"
    And I fill in "Name" with "Very new todo list"
    And I press "Save"
    Then I should see "ToDoList created!"
    And I should see "Very new todo list"

  @javascript
  Scenario: Delete one todo list
    Given there are 3 todo lists
    And I am on "/"
    #And show last response
    When I click "Delete"
    And I wait for a 3 seconds
    #And show last response
    Then I should see 2 todo lists
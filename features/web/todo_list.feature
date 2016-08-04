Feature: ToDo List Dashboard
  In order to maintain my todo lists
  As a user
  I need to be able to add/edit/delete todo lists

  Scenario: List current todo lists
    Given I am logged in as an user
    And there are 3 todo lists
    And I am on "/"
    Then I should see 3 todo lists

  Scenario: Add a new todo list
    Given I am logged in as an user
    And I am on "/"
    When I click "New ToDo List"
    And I fill in "Name" with "Veloci-chew toy"
    And I press "Save"
    Then I should see "ToDoList created!"
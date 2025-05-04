# Test Structure Summary

This document summarizes the test structure created for the Laravel blog application.

## Unit Tests

1. **PostTest.php**
   - `test_post_can_be_created`: Tests post creation and slug generation
   - `test_post_has_relationships`: Tests post relationships with users, categories, comments, and translations
   - `test_post_translations`: Tests post translation functionality

## Feature Tests

1. **BlogPostsTest.php**
   - `test_posts_page_can_be_viewed`: Tests the blog listing page
   - `test_post_details_page_can_be_viewed`: Tests viewing a post's details
   - `test_draft_posts_are_not_shown_to_public`: Tests that draft posts don't appear in the public listing
   - `test_user_can_search_posts`: Tests post search functionality

2. **AuthenticationTest.php**
   - `test_login_page_can_be_rendered`: Tests login page rendering
   - `test_users_can_authenticate`: Tests successful login
   - `test_users_cannot_authenticate_with_invalid_password`: Tests failed login
   - `test_users_can_logout`: Tests logout functionality
   - `test_registration_page_can_be_rendered`: Tests registration page rendering
   - `test_new_users_can_register`: Tests user registration

3. **TrainerTest.php**
   - `test_trainers_list_page_can_be_viewed`: Tests listing all trainers
   - `test_trainer_details_page_can_be_viewed`: Tests viewing a trainer's details
   - `test_authenticated_user_can_create_reservation`: Tests reservation creation
   - `test_user_must_be_authenticated_to_create_reservation`: Tests authentication requirement for reservations
   - `test_user_can_view_their_reservations`: Tests viewing user's reservations

4. **AdminTest.php**
   - `test_admin_can_access_dashboard`: Tests admin dashboard access
   - `test_non_admin_cannot_access_dashboard`: Tests that regular users can't access admin areas
   - `test_admin_can_manage_posts`: Tests post management functionality
   - `test_admin_can_manage_categories`: Tests category management functionality
   - `test_admin_can_manage_users`: Tests user management functionality

5. **NutritionTest.php**
   - `test_nutrition_calculator_page_can_be_viewed`: Tests nutrition calculator page
   - `test_meal_planner_page_can_be_viewed`: Tests meal planner page
   - `test_user_can_create_nutritional_profile`: Tests creating nutritional profiles
   - `test_user_can_view_nutritional_profile`: Tests viewing nutritional profiles
   - `test_user_can_view_meal_plans`: Tests viewing meal plans

## Execution Scripts

Two scripts have been created to run the tests:

1. **run-tests.sh** - For Linux/Mac users
   ```bash
   ./run-tests.sh
   ```

2. **run-tests.ps1** - For Windows users
   ```powershell
   .\run-tests.ps1
   ```

## Known Issues

There are currently database issues when running the tests:

1. SQLite driver issues if you try to use the SQLite in-memory database
2. Foreign key constraint issues with the `category_translations` table when using the MySQL database

To fix these issues, either:

1. Enable SQLite by uncommenting these lines in `phpunit.xml` and ensuring the SQLite driver is enabled:
   ```xml
   <env name="DB_CONNECTION" value="sqlite"/>
   <env name="DB_DATABASE" value=":memory:"/>
   ```

2. Fix the database migration issue with the `category_translations` table foreign key constraint

The tests are ready to run once these database issues are resolved. 
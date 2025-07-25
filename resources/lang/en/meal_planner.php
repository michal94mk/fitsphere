<?php

return [
    'title' => 'Meal Planner',
    'subtitle' => 'Plan your meals and track nutritional values',
    'weekly_calendar' => 'Weekly Calendar',
    'previous_week' => 'Previous Week',
    'next_week' => 'Next Week',
    'previous' => 'Previous',
    'next' => 'Next',
    'prev_week' => '← Previous Week',
    'selected_day' => 'Selected Day',
    'saved_meals' => 'Saved Meals',
    'no_saved_meals' => 'No saved meals for this day.',
    'generate_plan' => 'Generate Meal Plan',
    'select_day' => 'Select day for plan',
    'generate' => 'Generate Plan',
    'generating' => 'Generating...',
    'generated_plan' => 'Generated Plan',
    'save_on' => 'Save on',
    'search_recipes' => 'Search Recipes',
    'search_placeholder' => 'Enter recipe name...',
    'search' => 'Search',
    'searching' => 'Searching...',
    'see_details' => 'See details',
    'back_to_list' => '← Back to list',
    'translate_to_polish' => '🌐 Translate to Polish',
    'translating' => 'Translating...',
    'basic_info' => 'Basic Information',
    'prep_time' => 'Preparation Time',
    'servings' => 'Servings',
    'calories' => 'Calories',
    'protein' => 'Protein',
    'carbs' => 'Carbohydrates',
    'fat' => 'Fat',
    'actions' => 'Actions',
    'description' => 'Description',
    'ingredients' => 'Ingredients',
    'instructions' => 'Preparation Instructions',
    'breakfast' => 'Breakfast',
    'lunch' => 'Lunch',
    'dinner' => 'Dinner',
    'snack' => 'Snack',
    'snacks' => 'Snacks',
    'meal' => 'Meal',
    'time_min' => 'min',
    'delete' => 'Delete',
    'confirm_delete' => 'Are you sure you want to delete the plan for this day?',
    'plan_saved' => 'Meal plan has been saved for',
    'plan_loaded' => 'Loaded saved plan for',
    'plan_deleted' => 'Meal plan has been deleted.',
    'meals_count' => 'meals',
    
    // API Key
    'api_key_missing_message' => 'To use the meal planner, configure the Spoonacular API key in the .env file:',
    
    // Dietary preferences
    'dietary_preferences' => 'Dietary Preferences',
    'vegetarian' => 'Vegetarian',
    'vegan' => 'Vegan',
    'gluten_free' => 'Gluten Free',
    'dairy_free' => 'Dairy Free',
    'keto' => 'Ketogenic',
    
    // Ingredients
    'excluded_ingredients' => 'Excluded Ingredients',
    'excluded_ingredients_placeholder' => 'e.g. onion, garlic, nuts',
    'comma_separated' => 'Separate with commas',
    
    // Recipe search
    'recipe_search' => 'Recipe Search',
    'search_button' => 'Search',
    'search_loading' => 'Searching recipes...',
    'search_results' => 'Search Results',
    'search_results_for' => 'Results for',
    'max_calories_per_serving' => 'Max calories per serving',
    'click_to_see_details' => 'Click to see details',
    'no_recipes_found' => 'No recipes found',
    
    // Recipe details
    'show_original' => 'Show Original',
    'preparation_time' => 'Preparation Time',
    'nutritional_info' => 'Nutritional Information',
    'nutrition_note' => 'per serving',
    'no_nutrition_info' => 'No nutritional information available',
    'translating_ingredients' => 'Translating ingredients...',
    'no_ingredients' => 'No ingredients list available',
    'translating_instructions' => 'Translating instructions...',
    'no_instructions' => 'No preparation instructions available',
    
    // Add to plan
    'add_to_plan' => 'Add to Plan',
    'meal_type' => 'Meal Type',
    'serving_size_adjustment' => 'Serving Size Adjustment',
    'servings_default' => 'Default',
    'serving_size_note' => 'Nutritional values will be calculated proportionally',
    'notes' => 'Notes',
    'cancel' => 'Cancel',
    
    // Daily meals
    'meals_for' => 'Meals for',
    'total_calories' => 'Total Calories',
    'kcal' => 'kcal',
    'actual_serving' => 'Actual serving',
    'delete_meal' => 'Delete meal',
    'no_meals_planned' => 'No meals planned for this day.',
    'use_generator' => 'Use the generator above to create a meal plan.',
    
    // Login modal
    'login_required_title' => 'Login Required',
    'login_required' => 'You must be logged in to use the meal planner.',
    'login' => 'Login',
    
    // Delete modal
    'delete_meal_title' => 'Delete Meal',
    'delete_meal_description' => 'Are you sure you want to remove this meal from the plan?',
    'recipe' => 'Recipe',
    
    'days' => [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ],
    'errors' => [
        'login_required' => 'You must be logged in to generate meal plans.',
        'no_profile' => 'You don\'t have a nutritional profile. Go to settings to create one.',
        'generation_failed' => 'Failed to generate meal plan. Please try again.',
        'past_date' => 'Cannot plan meals for past days.',
        'past_week' => 'Cannot display calendar for past weeks.',
        'save_past_date' => 'Cannot save plans for past days.',
        'select_day_and_plan' => 'Select a day and generate a meal plan.',
        'recipe_load_failed' => 'Failed to load recipe details.',
        'translation_failed' => 'An error occurred while translating the recipe.',
        'save_failed' => 'An error occurred while saving the plan.',
        'delete_failed' => 'An error occurred while deleting the plan.',
        'search_failed' => 'An error occurred during search',
        'no_recipes_found' => 'No recipes found',
    ],
    
    // Additional messages used in component
    'profile_required' => 'You don\'t have a nutritional profile. Go to settings to create one.',
    'calories_required' => 'Cannot determine daily caloric requirements.',
    'plan_generated_success' => 'Meal plan has been generated successfully.',
    'success' => [
        'recipes_found' => 'Found :count recipes',
    ],
    
    // New keys for SimpleMealPlanner
    'select_day_first' => 'Please select a day first.',
    'plan_generated' => 'Meal plan has been generated!',
    'generation_failed' => 'Failed to generate meal plan.',
    'api_error' => 'API error. Please try again later.',
    'no_plan_to_save' => 'No plan to save.',
    'recipe_load_error' => 'Error loading recipe.',
    'search_error' => 'Error during search.',
    'translation_error' => 'Error during translation.',
    'delete_whole_plan' => 'Delete entire plan',
    'add_to_plan' => 'Add to Plan',
    'adding' => 'Adding...',
    'recipe_added_to_plan' => 'Recipe has been added to the plan!',
    'select_date_to_add' => 'Select a date in the calendar above to be able to add recipes to the plan',
    'remove' => 'Remove',
    'removing' => 'Removing...',
    'meal_removed' => 'Meal has been removed from the plan.',
    'loading' => 'Loading...',
    'saving' => 'Saving...',
    'login_required' => 'You must be logged in to use the meal planner.',
    'login_required_info' => 'Log in to generate personalized meal plans based on your nutritional profile.',
    'profile_required' => 'You must have a nutritional profile. Go to Nutrition Calculator to create one.',
    'plan_generated_with_profile' => 'Meal plan has been generated based on your profile!',
]; 
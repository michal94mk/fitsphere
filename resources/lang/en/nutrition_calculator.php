<?php

return [
    'title' => 'Nutrition Calculator',
    'subtitle' => 'Calculate your daily caloric needs and macronutrient balance',
    'personal_info' => 'Personal Information',
    'age' => 'Age',
    'gender' => 'Gender',
    'male' => 'Male',
    'female' => 'Female',
    'weight' => 'Weight (kg)',
    'height' => 'Height (cm)',
    'activity_level' => 'Activity Level',
    'activity_level_1' => 'Sedentary (little or no exercise)',
    'activity_level_2' => 'Light exercise (1-3 days per week)',
    'activity_level_3' => 'Moderate exercise (3-5 days per week)',
    'activity_level_4' => 'Heavy exercise (6-7 days per week)',
    'activity_level_5' => 'Athlete (twice per day, extra heavy workouts)',
    'goal' => 'Goal',
    'goal_lose' => 'Lose weight',
    'goal_maintain' => 'Maintain weight',
    'goal_gain' => 'Gain weight',
    'calculate' => 'Calculate',
    'results' => 'Your Results',
    'bmr' => 'Basal Metabolic Rate (BMR)',
    'tdee' => 'Total Daily Energy Expenditure (TDEE)',
    'calories' => 'Daily Caloric Intake',
    'protein' => 'Protein',
    'carbs' => 'Carbohydrates',
    'fat' => 'Fat',
    'grams_per_day' => 'g/day',
    'calories_per_day' => 'calories/day',
    'reset' => 'Reset',
    'disclaimer' => 'Disclaimer: This calculator provides an estimate only. Consult with a healthcare professional for personalized advice.',
    'profile_error' => 'To calculate nutritional values, please fill in all required profile fields.',
    'profile_saved' => 'Your nutritional profile has been saved successfully!',
    'search_error' => 'Please enter a recipe name to search for.',
    'search_api_error' => 'An error occurred while communicating with the API. Please try again.',
    'search_error_general' => 'An unexpected error occurred while searching. Please try again.',
    'select' => 'Select',
    'macros' => 'Macronutrients',
    'allergies' => 'Dietary Restrictions',
    'gluten_free' => 'Gluten-Free',
    'dairy_free' => 'Dairy-Free',
    'peanut_free' => 'Peanut-Free',
    'vegetarian' => 'Vegetarian',
    'vegan' => 'Vegan',
    'sugar_free' => 'Sugar-Free',
    'save_profile' => 'Save Profile',
    
    // API configuration message
    'api_key_missing_title' => 'Note: API key not configured',
    'api_key_missing_message' => 'Recipe search and meal planning features require a Spoonacular API key. To activate them:',
    'api_key_step1' => 'Create a free account at',
    'api_key_step2' => 'Generate API key in developer panel',
    'api_key_step3' => 'Add the API key to SPOONACULAR_API_KEY variable in the .env file',
    
    // Recipe search related translations
    'recipe_search' => 'Recipe Search',
    'search_recipes' => 'Search Recipes',
    'search_placeholder' => 'e.g. chicken, pasta, salad...',
    'search_button' => 'Search',
    'diet_optional' => 'Diet (optional)',
    'any_diet' => 'Any',
    'max_calories' => 'Maximum calories per serving',
    'search_results' => 'Search Results',
    'add_to_plan' => 'Add to Meal Plan',
    'login_required' => 'You need to be logged in to use this feature. Please login or create an account.',
    'login_required_title' => 'Login Required',
    'login' => 'Login',
    'create_account' => 'Create Account',
    'cancel' => 'Cancel',
    'translation_note' => 'Note: The search has been translated from Polish to English to find the best results.',
    'translation_detail' => 'Note: Your search was translated from ":original" to ":translated" for better results.',
    
    // Recipe details
    'view_recipe' => 'View Recipe',
    'nutrition_info' => 'Nutrition Information',
    'ingredients' => 'Ingredients',
    'instructions' => 'Instructions',
    'servings' => 'Servings',
    'preparation_time' => 'Preparation Time',
    'no_instructions' => 'No instructions available for this recipe.',
    'no_ingredients' => 'No ingredients list available for this recipe.',
    'close' => 'Close',
    'recipe_fetch_error' => 'There was an error loading the recipe details. Please try again.',
    'translate_to_polish' => 'Translate to Polish',
    'show_original' => 'Show original text',
    'translating' => 'Translating...',
    'please_wait' => 'Please wait while we translate the recipe',
    'translating_title' => 'Translating title',
    'translating_ingredients' => 'Translating ingredients...',
    'translating_instructions' => 'Translating instructions...',
    'auto_translation_enabled' => 'Automatic translation is enabled. Translations are provided by free API and may contain errors. You can switch back to the original text.',
    
    // Translation service information
    'translation_info_title' => 'Information: Recipe translation',
    'translation_info_message' => 'The recipe translation feature uses the free DeepL API:',
    'translation_info_point1' => 'Translations may not be perfect or may contain errors',
    'translation_info_point2' => 'Translation time may be longer for extensive recipes',
    'translation_info_point3' => 'Problems may occur with longer texts due to limitations of the free API',
    'translation_info_point4' => 'In case of translation issues, you can always switch to the original version',
    
    // Meal planning redirect
    'meal_planning' => 'Meal Planning',
    'meal_planning_description' => 'Use the dedicated Meal Planner to search for recipes, create meal plans, and track your nutrition goals.',
    'go_to_meal_planner' => 'Go to Meal Planner',
]; 
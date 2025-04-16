<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class NutritionCalculator extends Component
{
    public $age;
    public $gender;
    public $weight;
    public $height;
    public $activityLevel;
    public $goal;
    public $dietaryRestrictions = [];
    
    public $bmi;
    public $dailyCalories;
    public $protein;
    public $carbs;
    public $fat;
    
    public $searchQuery = '';
    public $searchResults = [];
    public $loading = false;
    public $dietFilters = null;
    public $intolerances = null;
    public $maxCalories = null;
    public $showDietaryInfo = false;
    
    protected $spoonacularService;
    
    public function boot(SpoonacularService $spoonacularService)
    {
        $this->spoonacularService = $spoonacularService;
    }
    
    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $profile = $user->nutritionalProfile;
        
        if ($profile) {
            $this->age = $profile->age;
            $this->gender = $profile->gender;
            $this->weight = $profile->weight;
            $this->height = $profile->height;
            $this->activityLevel = $profile->activity_level;
            $this->goal = $profile->goal;
            $this->dietaryRestrictions = $profile->dietary_restrictions ?? [];
            
            $this->calculateNutrition();
        }
    }
    
    public function calculateNutrition()
    {
        if (!$this->weight || !$this->height || !$this->age || !$this->gender || !$this->activityLevel) {
            $this->addError('profile', 'Aby obliczyć wartości odżywcze, wypełnij wszystkie wymagane pola profilu.');
            return;
        }
        
        // Tworzenie tymczasowego obiektu profilu do obliczeń
        $profile = new NutritionalProfile([
            'age' => $this->age,
            'gender' => $this->gender,
            'weight' => $this->weight,
            'height' => $this->height,
            'activity_level' => $this->activityLevel,
            'goal' => $this->goal,
        ]);
        
        $this->bmi = $profile->calculateBMI();
        $this->dailyCalories = $profile->calculateDailyCalories();
        
        // Obliczanie makroskładników
        $this->protein = round(($this->dailyCalories * 0.30) / 4, 0); // 4 kalorie na gram białka
        $this->carbs = round(($this->dailyCalories * 0.40) / 4, 0);   // 4 kalorie na gram węglowodanów
        $this->fat = round(($this->dailyCalories * 0.30) / 9, 0);     // 9 kalorii na gram tłuszczu
        
        $this->showDietaryInfo = true;
    }
    
    public function saveProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $profile = $user->nutritionalProfile;
        
        if (!$profile) {
            $profile = new NutritionalProfile(['user_id' => $user->id]);
        }
        
        $profile->age = $this->age;
        $profile->gender = $this->gender;
        $profile->weight = $this->weight;
        $profile->height = $this->height;
        $profile->activity_level = $this->activityLevel;
        $profile->goal = $this->goal;
        $profile->dietary_restrictions = $this->dietaryRestrictions;
        
        if ($this->dailyCalories) {
            $profile->target_calories = $this->dailyCalories;
            $profile->target_protein = $this->protein;
            $profile->target_carbs = $this->carbs;
            $profile->target_fat = $this->fat;
        }
        
        $profile->save();
        
        session()->flash('message', 'Profil żywieniowy został zapisany pomyślnie!');
    }
    
    public function searchRecipes()
    {
        if (empty($this->searchQuery)) {
            session()->flash('error', 'Wprowadź wyszukiwaną frazę.');
            return;
        }
        
        $this->loading = true;
        
        $params = [];
        
        if ($this->dietFilters) {
            $params['diet'] = $this->dietFilters;
        }
        
        if ($this->intolerances) {
            $params['intolerances'] = $this->intolerances;
        }
        
        if ($this->maxCalories) {
            $params['maxCalories'] = $this->maxCalories;
        }
        
        // Jeśli użytkownik ma profil z ograniczeniami dietetycznymi
        $user = Auth::user();
        if ($user && $user->nutritionalProfile && !empty($user->nutritionalProfile->dietary_restrictions)) {
            if (!isset($params['intolerances'])) {
                $params['intolerances'] = implode(',', $user->nutritionalProfile->dietary_restrictions);
            }
        }
        
        $results = $this->spoonacularService->searchRecipes($this->searchQuery, $params);
        
        $this->searchResults = $results;
        $this->loading = false;
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.nutrition-calculator');
    }
}

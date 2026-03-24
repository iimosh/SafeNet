<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Questionnaire;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $student = Questionnaire::where('target_role', 'student')->first();
        $parent = Questionnaire::where('target_role', 'parent')->first();

        if (!$student || !$parent) {
            $this->command->warn('Questionnaires not found!');
            return;
        }

        $categories = [
            [
                'name' => 'Време на користење',
                'slug' => 'screen-time',
                'description' => 'Прашања поврзани со времето поминато на дигитални уреди.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Приватност и безбедност',
                'slug' => 'privacy-safety',
                'description' => 'Прашања поврзани со приватност и лични податоци.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Онлајн комуникација',
                'slug' => 'online-communication',
                'description' => 'Прашања поврзани со комуникација и контакти онлајн.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Самоконтрола и зависност',
                'slug' => 'self-control-addiction',
                'description' => 'Прашања поврзани со прекумерна употреба и зависност.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Емоционално влијание',
                'slug' => 'emotional-impact',
                'description' => 'Прашања поврзани со емоционално влијание.',
                'sort_order' => 5,
            ],
        ];

        // 🔹 Student categories
        foreach ($categories as $cat) {
            Category::updateOrCreate(
                [
                    'questionnaire_id' => $student->id,
                    'name' => $cat['name'],
                ],
                $cat
            );
        }

        // 🔹 Parent categories
        foreach ($categories as $cat) {
            Category::updateOrCreate(
                [
                    'questionnaire_id' => $parent->id,
                    'name' => $cat['name'],
                ],
                $cat
            );
        }
    }
}

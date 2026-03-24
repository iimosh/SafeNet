<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use App\Models\Question;
use App\Models\Questionnaire;
use Illuminate\Database\Seeder;

class QuestionnaireContentSeeder extends Seeder
{
    public function run(): void
    {
        $studentQuestionnaire = Questionnaire::where('target_role', 'student')->first();
        $parentQuestionnaire = Questionnaire::where('target_role', 'parent')->first();

        if (!$studentQuestionnaire || !$parentQuestionnaire) {
            $this->command->warn('Student or parent questionnaire not found.');
            return;
        }

        $studentQuestions = [
            'Време на користење' => [
                'Колку често користиш телефон повеќе од 3 часа дневно?',
                'Дали користиш телефон непосредно пред спиење?',
                'Дали го користиш телефонот веднаш по будење?',
                'Дали користиш уреди за време на учење или домашна работа?',
            ],
            'Приватност и безбедност' => [
                'Дали користиш иста лозинка за повеќе профили?',
                'Дали споделуваш лични информации онлајн?',
                'Дали прифаќаш пријателства од непознати лица?',
                'Дали некогаш си кликнал/а на сомнителен линк?',
            ],
            'Онлајн комуникација' => [
                'Дали комуницираш со луѓе што не ги познаваш во реалниот живот?',
                'Дали си добил/а навредливи или непријатни пораки?',
                'Дали некогаш си се почувствувал/а под притисок од онлајн комуникација?',
                'Дали споделуваш лични фотографии со други лица онлајн?',
            ],
            'Самоконтрола и зависност' => [
                'Дали ти е тешко да го оставиш телефонот?',
                'Дали се нервираш кога немаш пристап до интернет?',
                'Дали поминуваш повеќе време онлајн отколку што планираш?',
                'Дали запоставуваш обврски поради интернет?',
            ],
            'Емоционално влијание' => [
                'Дали се чувствуваш лошо по користење на социјални мрежи?',
                'Дали се споредуваш со други онлајн?',
                'Дали социјалните мрежи ти влијаат на самодовербата?',
                'Дали се чувствуваш анксиозно кога не си онлајн?',
            ],
        ];

        $parentQuestions = [
            'Време на користење' => [
                'Дали вашето дете користи телефон повеќе од 3 часа дневно?',
                'Дали вашето дете користи телефон пред спиење?',
                'Дали вашето дете го користи телефонот веднаш по будење?',
                'Дали вашето дете користи уреди за време на учење или домашна работа?',
            ],
            'Приватност и безбедност' => [
                'Дали вашето дете користи иста лозинка за повеќе профили?',
                'Дали вашето дете споделува лични информации онлајн?',
                'Дали вашето дете прифаќа пријателства од непознати лица?',
                'Дали вашето дете некогаш кликнало на сомнителен линк?',
            ],
            'Онлајн комуникација' => [
                'Дали вашето дете комуницира со луѓе што не ги познава во реалниот живот?',
                'Дали вашето дете добило навредливи или непријатни пораки?',
                'Дали вашето дете делува под притисок поради онлајн комуникација?',
                'Дали вашето дете споделува лични фотографии со други лица онлајн?',
            ],
            'Самоконтрола и зависност' => [
                'Дали на вашето дете му е тешко да го остави телефонот?',
                'Дали вашето дете се нервира кога нема пристап до интернет?',
                'Дали вашето дете поминува повеќе време онлајн отколку што планира?',
                'Дали вашето дете запоставува обврски поради интернет?',
            ],
            'Емоционално влијание' => [
                'Дали вашето дете се чувствува лошо по користење на социјални мрежи?',
                'Дали вашето дете се споредува со други онлајн?',
                'Дали социјалните мрежи влијаат на самодовербата на вашето дете?',
                'Дали вашето дете делува анксиозно кога не е онлајн?',
            ],
        ];

        $this->seedQuestionsForQuestionnaire($studentQuestionnaire, $studentQuestions);
        $this->seedQuestionsForQuestionnaire($parentQuestionnaire, $parentQuestions);
    }

    private function seedQuestionsForQuestionnaire(Questionnaire $questionnaire, array $questionsByCategory): void
    {
        foreach ($questionsByCategory as $categoryName => $questions) {
            $category = Category::where('questionnaire_id', $questionnaire->id)
                ->where('name', $categoryName)
                ->first();

            if (!$category) {
                $this->command->warn("Category '{$categoryName}' not found for questionnaire '{$questionnaire->title}'.");
                continue;
            }

            foreach ($questions as $questionText) {
                $question = Question::updateOrCreate(
                    [
                        'questionnaire_id' => $questionnaire->id,
                        'question_text' => $questionText,
                    ],
                    [
                        'category_id' => $category->id,
                        'category' => $category->name,
                    ]
                );

                $this->createDefaultOptions($question);
            }
        }
    }

    private function createDefaultOptions(Question $question): void
    {
        $options = [
            ['option_text' => 'Никогаш', 'risk_points' => 0],
            ['option_text' => 'Ретко', 'risk_points' => 1],
            ['option_text' => 'Понекогаш', 'risk_points' => 2],
            ['option_text' => 'Често', 'risk_points' => 3],
            ['option_text' => 'Секогаш', 'risk_points' => 4],
        ];

        foreach ($options as $optionData) {
            Option::updateOrCreate(
                [
                    'question_id' => $question->id,
                    'option_text' => $optionData['option_text'],
                ],
                [
                    'risk_points' => $optionData['risk_points'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@safenet.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $student = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test Student',
                'role' => 'student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $parent = User::firstOrCreate(
            ['email' => 'parent@example.com'],
            [
                'name' => 'Test Parent',
                'role' => 'parent',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $parent->children()->syncWithoutDetaching([$student->id]);

        $studentQuestionnaire = Questionnaire::firstOrCreate(
            ['title' => 'Онлајн навики - Ученик'],
            [
                'description' => 'Прашалник за проценка на дигиталните навики на ученикот.',
                'target_role' => 'student',
            ]
        );

//        $this->seedStudentQuestions($studentQuestionnaire);

        $parentQuestionnaire = Questionnaire::firstOrCreate(
            ['title' => 'Онлајн навики - Родител'],
            [
                'description' => 'Прашалник за проценка на дигиталните навики на вашето дете.',
                'target_role' => 'parent',
            ]
        );

//        $this->seedParentQuestions($parentQuestionnaire);

        $this->call([
            CategorySeeder::class,
            QuestionnaireContentSeeder::class,
            RecommendationSeeder::class,
        ]);
    }

    private function seedStudentQuestions(Questionnaire $questionnaire): void
    {
        $questions = [
            [
                'text' => 'Колку часа дневно поминуваш на интернет?',
                'category' => 'screen_time',
                'options' => [
                    ['text' => 'Помалку од 1 час', 'points' => 0],
                    ['text' => '1-2 часа', 'points' => 5],
                    ['text' => '3-5 часа', 'points' => 15],
                    ['text' => 'Повеќе од 5 часа', 'points' => 25],
                ],
            ],
            [
                'text' => 'Дали споделуваш лични информации со непознати онлајн?',
                'category' => 'privacy',
                'options' => [
                    ['text' => 'Никогаш', 'points' => 0],
                    ['text' => 'Ретко', 'points' => 10],
                    ['text' => 'Понекогаш', 'points' => 20],
                    ['text' => 'Често', 'points' => 30],
                ],
            ],
            [
                'text' => 'Дали некогаш си се сретнал со непријатна содржина онлајн?',
                'category' => 'safety',
                'options' => [
                    ['text' => 'Никогаш', 'points' => 0],
                    ['text' => 'Еднаш или двапати', 'points' => 10],
                    ['text' => 'Неколку пати', 'points' => 20],
                    ['text' => 'Често', 'points' => 30],
                ],
            ],
            [
                'text' => 'Дали користиш социјални мрежи?',
                'category' => 'social_media',
                'options' => [
                    ['text' => 'Не користам', 'points' => 0],
                    ['text' => 'Ретко', 'points' => 5],
                    ['text' => 'Секој ден', 'points' => 15],
                    ['text' => 'Повеќе пати на ден', 'points' => 25],
                ],
            ],
            [
                'text' => 'Дали некој те вознемирувал или те малтретирал онлајн?',
                'category' => 'cyberbullying',
                'options' => [
                    ['text' => 'Никогаш', 'points' => 0],
                    ['text' => 'Еднаш', 'points' => 15],
                    ['text' => 'Неколку пати', 'points' => 25],
                    ['text' => 'Често', 'points' => 35],
                ],
            ],
        ];

        $this->createQuestions($questionnaire, $questions);
    }

    private function seedParentQuestions(Questionnaire $questionnaire): void
    {
        $questions = [
            [
                'text' => 'Колку часа дневно вашето дете поминува на интернет?',
                'category' => 'screen_time',
                'options' => [
                    ['text' => 'Помалку од 1 час', 'points' => 0],
                    ['text' => '1-2 часа', 'points' => 5],
                    ['text' => '3-5 часа', 'points' => 15],
                    ['text' => 'Повеќе од 5 часа', 'points' => 25],
                ],
            ],
            [
                'text' => 'Дали вашето дете споделува лични информации онлајн?',
                'category' => 'privacy',
                'options' => [
                    ['text' => 'Никогаш', 'points' => 0],
                    ['text' => 'Ретко', 'points' => 10],
                    ['text' => 'Понекогаш', 'points' => 20],
                    ['text' => 'Често', 'points' => 30],
                ],
            ],
            [
                'text' => 'Дали разговарате со детето за безбедност на интернет?',
                'category' => 'safety',
                'options' => [
                    ['text' => 'Редовно', 'points' => 0],
                    ['text' => 'Понекогаш', 'points' => 10],
                    ['text' => 'Ретко', 'points' => 20],
                    ['text' => 'Никогаш', 'points' => 30],
                ],
            ],
            [
                'text' => 'Дали го надгледувате користењето на интернет на вашето дете?',
                'category' => 'supervision',
                'options' => [
                    ['text' => 'Секогаш', 'points' => 0],
                    ['text' => 'Понекогаш', 'points' => 10],
                    ['text' => 'Ретко', 'points' => 20],
                    ['text' => 'Никогаш', 'points' => 30],
                ],
            ],
            [
                'text' => 'Дали вашето дете имало проблеми поврзани со интернет?',
                'category' => 'cyberbullying',
                'options' => [
                    ['text' => 'Никогаш', 'points' => 0],
                    ['text' => 'Еднаш', 'points' => 15],
                    ['text' => 'Неколку пати', 'points' => 25],
                    ['text' => 'Често', 'points' => 35],
                ],
            ],
        ];

        $this->createQuestions($questionnaire, $questions);
    }

    private function createQuestions(Questionnaire $questionnaire, array $questions): void
    {
        foreach ($questions as $q) {
            $question = Question::firstOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'question_text' => $q['text'],
                ],
                ['category' => $q['category']]
            );

            foreach ($q['options'] as $o) {
                Option::firstOrCreate(
                    [
                        'question_id' => $question->id,
                        'option_text' => $o['text'],
                    ],
                    ['risk_points' => $o['points']]
                );
            }
        }
    }
}

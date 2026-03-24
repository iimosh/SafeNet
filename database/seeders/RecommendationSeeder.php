<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use App\Models\Recommendation;
//use App\Models\Category;
//use App\Models\Questionnaire;
//
//class RecommendationSeeder extends Seeder
//{
//    public function run(): void
//    {
//        $questionnaire = Questionnaire::where('target_role', 'student')->first();
//
//        if (!$questionnaire) {
//            $this->command->warn('Student questionnaire not found!');
//            return;
//        }
//
//        $categories = Category::where('questionnaire_id', $questionnaire->id)->get();
//
//        foreach ($categories as $category) {
//
//            // 📱 ВРЕМЕ НА КОРИСТЕЊЕ
//            if ($category->name === 'Време на користење') {
//
//                $this->create($questionnaire, $category, 'low',
//                    'Има здрава рамнотежа во користењето на дигитални уреди. Продолжи со умерено користење и редовни паузи.'
//                );
//
//                $this->create($questionnaire, $category, 'medium',
//                    'Потребно е да се намали времето поминато пред екран. Обиди се да поставиш ограничувања и да воведеш паузи.'
//                );
//
//                $this->create($questionnaire, $category, 'high',
//                    'Постои ризик од прекумерно користење. Потребно е значително намалување на времето пред екран и воведување јасни правила.'
//                );
//            }
//
//            // 🔐 ПРИВАТНОСТ
//            if ($category->name === 'Приватност и безбедност') {
//
//                $this->create($questionnaire, $category, 'low',
//                    'Добро се грижиш за приватноста. Продолжи со сигурни лозинки и внимателно споделување податоци.'
//                );
//
//                $this->create($questionnaire, $category, 'medium',
//                    'Потребно е повеќе внимание на безбедноста. Користи посилни лозинки и избегнувај споделување лични информации.'
//                );
//
//                $this->create($questionnaire, $category, 'high',
//                    'Постои висок ризик за приватноста. Не споделувај лични податоци и избегнувај комуникација со непознати.'
//                );
//            }
//
//            // 💬 КОМУНИКАЦИЈА
//            if ($category->name === 'Онлајн комуникација') {
//
//                $this->create($questionnaire, $category, 'low',
//                    'Комуникацијата е безбедна. Продолжи внимателно да избираш со кого комуницираш.'
//                );
//
//                $this->create($questionnaire, $category, 'medium',
//                    'Потребно е поголемо внимание во комуникацијата. Избегнувај разговори со непознати лица.'
//                );
//
//                $this->create($questionnaire, $category, 'high',
//                    'Постои ризик од небезбедна комуникација. Ограничи контакти со непознати и побарај помош ако имаш проблем.'
//                );
//            }
//
//            // 🎮 ЗАВИСНОСТ
//            if ($category->name === 'Самоконтрола и зависност') {
//
//                $this->create($questionnaire, $category, 'low',
//                    'Имаш добра контрола врз користењето на технологија. Продолжи со балансирани навики.'
//                );
//
//                $this->create($questionnaire, $category, 'medium',
//                    'Постојат знаци на прекумерна употреба. Обиди се да поставиш граници и да правиш паузи.'
//                );
//
//                $this->create($questionnaire, $category, 'high',
//                    'Постои висок ризик од зависност. Потребно е да се намали користењето и да се воведат ограничувања.'
//                );
//            }
//
//            // 🧠 ЕМОЦИИ
//            if ($category->name === 'Емоционално влијание') {
//
//                $this->create($questionnaire, $category, 'low',
//                    'Дигиталната употреба нема негативно влијание. Продолжи со позитивни навики.'
//                );
//
//                $this->create($questionnaire, $category, 'medium',
//                    'Можно е дигиталната активност да влијае на расположението. Направи паузи од социјални мрежи.'
//                );
//
//                $this->create($questionnaire, $category, 'high',
//                    'Дигиталната употреба има негативно влијание. Потребно е ограничување и разговор со родител или стручно лице.'
//                );
//            }
//        }
//
//        // 🌍 GLOBAL RECOMMENDATIONS
//
//        $this->createGlobal($questionnaire, 'low',
//            'Дигиталните навики се на добро ниво. Продолжи со одговорно користење и внимавај на безбедноста.'
//        );
//
//        $this->createGlobal($questionnaire, 'medium',
//            'Постојат одредени ризици. Потребно е подобрување на навиките и зголемена внимателност.'
//        );
//
//        $this->createGlobal($questionnaire, 'high',
//            'Постои висок ризик. Потребни се промени во навиките и поголема контрола на дигиталната активност.'
//        );
//    }
//
//    private function create($questionnaire, $category, $risk, $text)
//    {
//        Recommendation::updateOrCreate(
//            [
//                'questionnaire_id' => $questionnaire->id,
//                'category_id' => $category->id,
//                'risk_level' => $risk,
//            ],
//            [
//                'audience' => 'student',
//                'is_global' => false,
//                'text' => $text,
//            ]
//        );
//    }
//
//    private function createGlobal($questionnaire, $risk, $text)
//    {
//        Recommendation::updateOrCreate(
//            [
//                'questionnaire_id' => $questionnaire->id,
//                'category_id' => null,
//                'risk_level' => $risk,
//                'is_global' => true,
//            ],
//            [
//                'audience' => 'student',
//                'text' => $text,
//            ]
//        );
//    }
//}
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recommendation;
use App\Models\Category;
use App\Models\Questionnaire;

class RecommendationSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedForRole('student');
        $this->seedForRole('parent');
    }

    private function seedForRole(string $role)
    {
        $questionnaire = Questionnaire::where('target_role', $role)->first();

        if (!$questionnaire) {
            $this->command->warn("{$role} questionnaire not found!");
            return;
        }

        $categories = Category::where('questionnaire_id', $questionnaire->id)->get();

        foreach ($categories as $category) {

            // 📱 ВРЕМЕ
            if ($category->name === 'Време на користење') {

                if ($role === 'student') {
                    $this->create($questionnaire, $category, $role, 'low', 'Има здрава рамнотежа во користењето на дигитални уреди.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Потребно е да се намали времето поминато пред екран.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои ризик од прекумерно користење.');
                } else {
                    $this->create($questionnaire, $category, $role, 'low', 'Вашето дете има добра рамнотежа во користењето на уреди.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Потребно е да се постават ограничувања за времето пред екран.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои ризик од прекумерна употреба. Потребна е контрола.');
                }
            }

            // 🔐 ПРИВАТНОСТ
            if ($category->name === 'Приватност и безбедност') {

                if ($role === 'student') {
                    $this->create($questionnaire, $category, $role, 'low', 'Добро се грижиш за приватноста.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Потребно е да обрнеш внимание на безбедноста.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои висок ризик за приватноста.');
                } else {
                    $this->create($questionnaire, $category, $role, 'low', 'Вашето дете има добра основа за безбедност.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Разговарајте со детето за безбедност онлајн.');
                    $this->create($questionnaire, $category, $role, 'high', 'Потребна е активна контрола и насочување.');
                }
            }

            // 💬 КОМУНИКАЦИЈА
            if ($category->name === 'Онлајн комуникација') {

                if ($role === 'student') {
                    $this->create($questionnaire, $category, $role, 'low', 'Комуникацијата е безбедна.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Биди повнимателен со непознати.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои ризик од небезбедна комуникација.');
                } else {
                    $this->create($questionnaire, $category, $role, 'low', 'Комуникацијата на детето е соодветна.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Разговарајте за комуникација со непознати.');
                    $this->create($questionnaire, $category, $role, 'high', 'Потребно е следење и поддршка.');
                }
            }

            // 🎮 ЗАВИСНОСТ
            if ($category->name === 'Самоконтрола и зависност') {

                if ($role === 'student') {
                    $this->create($questionnaire, $category, $role, 'low', 'Имаш добра контрола.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Постојат знаци на прекумерна употреба.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои ризик од зависност.');
                } else {
                    $this->create($questionnaire, $category, $role, 'low', 'Детето има добра контрола.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Поставете граници за користење.');
                    $this->create($questionnaire, $category, $role, 'high', 'Потребна е контрола и ограничување.');
                }
            }

            // 🧠 ЕМОЦИИ
            if ($category->name === 'Емоционално влијание') {

                if ($role === 'student') {
                    $this->create($questionnaire, $category, $role, 'low', 'Нема значително негативно влијание.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Можно е влијание врз расположението.');
                    $this->create($questionnaire, $category, $role, 'high', 'Постои негативно влијание.');
                } else {
                    $this->create($questionnaire, $category, $role, 'low', 'Не се забележува негативно влијание.');
                    $this->create($questionnaire, $category, $role, 'medium', 'Разговарајте за емоционалното влијание.');
                    $this->create($questionnaire, $category, $role, 'high', 'Потребна е поддршка и разговор.');
                }
            }
        }

        // 🌍 GLOBAL
        if ($role === 'student') {
            $this->createGlobal($questionnaire, $role, 'low', 'Дигиталните навики се на добро ниво.');
            $this->createGlobal($questionnaire, $role, 'medium', 'Постојат одредени ризици.');
            $this->createGlobal($questionnaire, $role, 'high', 'Постои висок ризик.');
        } else {
            $this->createGlobal($questionnaire, $role, 'low', 'Навиките на детето се на добро ниво.');
            $this->createGlobal($questionnaire, $role, 'medium', 'Потребно е повеќе внимание.');
            $this->createGlobal($questionnaire, $role, 'high', 'Потребна е контрола и промена.');
        }
    }

    private function create($questionnaire, $category, $role, $risk, $text)
    {
        Recommendation::updateOrCreate(
            [
                'questionnaire_id' => $questionnaire->id,
                'category_id' => $category->id,
                'risk_level' => $risk,
                'audience' => $role,
            ],
            [
                'is_global' => false,
                'text' => $text,
            ]
        );
    }

    private function createGlobal($questionnaire, $role, $risk, $text)
    {
        Recommendation::updateOrCreate(
            [
                'questionnaire_id' => $questionnaire->id,
                'category_id' => null,
                'risk_level' => $risk,
                'audience' => $role,
                'is_global' => true,
            ],
            [
                'text' => $text,
            ]
        );
    }
}

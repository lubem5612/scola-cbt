<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Transave\ScolaCbt\Http\Models\Exam;
use Transave\ScolaCbt\Http\Models\ExamSetting;

class ExamSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExamSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'exam_id' => Exam::factory(),
            'show_max_scores' => $this->faker->randomElement([1, 0]),
            'display_question_randomly' => $this->faker->randomElement([1, 0]),
            'allow_multiple_attempts' => $this->faker->randomElement([1, 0]),
            'is_public_access' => $this->faker->randomElement([1, 0]),
            'browser_warn_level' => $this->faker->randomElement([2, 1, 0]),
            'farewell_message' => $this->faker->sentence,
            'unordered_answering' => $this->faker->randomElement([1, 0]),
            'set_pass_mark' => $this->faker->randomElement([1, 0]),
            'pass_mark_value' => $this->faker->randomFloat(0, 40, 80),
            'pass_mark_unit' => $this->faker->randomElement(['points', 'percent']),
            'grade_with_points' => $this->faker->randomElement([1, 0]),
            'send_result_mail' => $this->faker->randomElement([1, 0]),
            'send_congratulatory_mail' => $this->faker->randomElement([1, 0]),
        ];
    }
}
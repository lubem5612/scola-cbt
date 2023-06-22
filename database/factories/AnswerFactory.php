<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Http\Models\Answer;
use Transave\ScolaCbt\Http\Models\Option;
use Transave\ScolaCbt\Http\Models\Question;

class AnswerFactory extends Factory
{
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => config('scola-cbt.auth_model')::factory(),
            'question_id' => Question::factory(),
            'option_id' => Option::factory(),
            'content' => $this->faker->sentence(10),
            'file' => UploadedFile::fake()->create('file.pdf', 2000, 'pdf')
        ];
    }
}
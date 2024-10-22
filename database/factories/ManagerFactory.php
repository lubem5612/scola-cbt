<?php

namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\User;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'phone' => $this->faker->phoneNumber,
            'photo' => UploadedFile::fake()->image('photo.jpg')
        ];
    }
}
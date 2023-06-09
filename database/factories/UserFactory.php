<?php


namespace Transave\ScolaCbt\Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Transave\ScolaCbt\Http\Models\Examiner;
use Transave\ScolaCbt\Http\Models\Manager;
use Transave\ScolaCbt\Http\Models\Staff;
use Transave\ScolaCbt\Http\Models\Student;
use Transave\ScolaCbt\Http\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'is_verified' => $this->faker->randomElement([0, 1]),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'role' => $this->faker->randomElement(['manager', 'examiner', 'staff', 'student'])
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if ($user->role == 'student') {
                Student::factory()->create(['user_id' => $user->id]);
            }elseif ($user->role == 'staff') {
                Staff::factory()->create(['user_id' => $user->id]);
            }elseif ($user->role == 'manager') {
                Manager::factory()->create(['user_id' => $user->id]);
            }elseif ($user->role == 'examiner') {
                Examiner::factory()->create(['user_id' => $user->id]);
            }
        });
    }
}
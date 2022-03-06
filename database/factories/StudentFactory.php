<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $image = $this->faker->image();
        $imageFile = new File($image);
        $classes = DB::table('classes')->pluck('id');
        $is_active = [0, 1];
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
            'image' => Storage::disk('public')->putFile('' , $imageFile),
            'class_id' => $this->faker->randomElement($classes),
            'is_active' => $this->faker->randomElement($is_active),
        ];
    }
}

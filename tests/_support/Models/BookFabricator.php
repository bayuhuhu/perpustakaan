<?php

namespace Tests\Support\Models;

use Faker\Factory;
use Faker\Generator;
use App\Models\BookModel;

class BookFabricator extends BookModel
{
    public function fake(Generator &$faker)
    {
        $faker = Factory::create('id_ID');
        $bookTitles = [
            "Bumi Manusia",
            "Laskar Pelangi",
            "Anak Semua Bangsa",
            "Ronggeng Dukuh Paruk",
            "Negeri 5 Menara",
            "Daun yang Jatuh Tak Pernah Membenci Angin",
            "Cantik Itu Luka",
            "Rumah Kaca",
            "A Tale of Two Cities",
            "The Lord of The Rings",
            "The Hobbit",
            "Odyssey",
            "Pondok Paman Tom (Uncle Tom's Cabin)",
            "Frankenstein",
            "Sembilan Belas Delapan Empat (Nineteen Eighty-Four)",
            "To Kill a Mockingbird",
            "Pride and Prejudice",
            "1984",
            "The Great Gatsby",
            "Moby Dick",
            "War and Peace",
            "The Catcher in the Rye",
            "The Little Prince",
            "Ulysses",
            "Don Quixote",
            "The Odyssey",
            "One Hundred Years of Solitude",
            "The Divine Comedy",
            "Les Misérables",
            "Anna Karenina"
        ];
        
        $title = $bookTitles[array_rand($bookTitles)];

        return [
            'slug'          => url_title($title, '-', true) . $faker->numberBetween(10000, 99999),
            'title'         => $title,
            'author'        => $faker->name,
            'publisher'     => $faker->company,
            'isbn'          => $faker->isbn13(),
            'year'          => $faker->numberBetween(2000, 2024),
            'rack_id'       => $faker->numberBetween(1, 10),
            'category_id'   => $faker->numberBetween(1, 5),
            'book_cover'    => "book-{$faker->numberBetween(1, 10)}.jpg",
        ];
    }
}

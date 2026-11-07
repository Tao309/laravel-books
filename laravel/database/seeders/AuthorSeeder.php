<?php

namespace Database\Seeders;

use App\DTOs\AuthorDTO;
use App\Models\Author;
use App\Repositories\AuthorRepository;

class AuthorSeeder extends AbstractSeeder
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
    ) {}

    public function run(): void
    {
        foreach ($this->getData() as $data) {
            $this->authorRepository->create(
                AuthorDTO::fromArray([
                    Author::PARAM_FIRST_NAME => $data[0],
                    Author::PARAM_LAST_NAME => $data[1],
                ])
            );
        }
    }

    private function getData(): array
    {
        return [
            ['Фёдор', 'Достоевский'],
            ['Рита', 'Хоффман'],
            ['Владимир', 'Торин'],
            ['Крис', 'Райт'],
            ['Майк', 'Брукс'],
            ['Робби', 'МакНивен'],
            ['Стивен', 'Кинг'],
            ['Катерина', 'Глинистая'],
            ['Саша', 'Бакушкина'],
            ['Аметисса', 'Грэсс'],
        ];

    }
}

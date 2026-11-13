<?php

namespace App\CQRS\Commands\BookSeries;

class DestroyBookSeriesCommand
{
    public function __construct(public int $id) {}
}

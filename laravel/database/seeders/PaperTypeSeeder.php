<?php

namespace Database\Seeders;

use App\Models\PaperType;
use Illuminate\Support\Facades\DB;

class PaperTypeSeeder extends AbstractSeeder
{
    public function run(): void
    {
        PaperType::unguard();

        foreach ($this->getSeedData() as $data) {
            DB::table(PaperType::TABLE_NAME)->insert([
                PaperType::PARAM_ID => $data[0],
                PaperType::PARAM_LABEL => $data[1],
                PaperType::PARAM_DESCRIPTION => $data[2],
            ]);
        }

        $this->updateLastId(PaperType::TABLE_NAME);
    }

    private function getSeedData(): array
    {
        return [
            [1, 'Типографская', 'Бумага средней плотности, светло-серая, слегка шероховатая'],
            [2, 'Офсет', 'Стандартная плотная бумага, белая и гладкая'],
            [3, 'Типографская пухлая', 'Пухлая бумага средней плотности, светло-серая'],
            [4, 'Офсет пухлый', 'Пухлая белая бумага'],
            [5, 'Газетная', null],
        ];
    }
}

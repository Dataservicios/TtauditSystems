<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CollectionExport implements FromCollection, WithHeadings
{
    use Exportable;
    protected $valores;
    protected $header;

    /**
     * CollectionExport constructor.
     */
    public function __construct($ValuesIntro,$heading)
    {
        $this->valores = $ValuesIntro;
        $this->header = $heading;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        /*return collect([
            [
                'name' => 'Povilas',
                'surname' => 'Korop',
                'email' => 'povilas@laraveldaily.com',
                'twitter' => '@povilaskorop'
            ],
            [
                'name' => 'Taylor',
                'surname' => 'Otwell',
                'email' => 'taylor@laravel.com',
                'twitter' => '@taylorotwell'
            ]
        ]);*/
        return collect($this->valores);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        /*return [
            'Name',
            'Surname',
            'Email',
            'Twitter',
        ];*/
        return $this->header;
    }
}

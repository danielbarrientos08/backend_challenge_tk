<?php

namespace App\Exports;


use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersReport implements FromCollection, WithHeadings,ShouldQueue
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    private $start_date;
    private $end_date;

    public function __construct($start_date,$end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return User::select('name','email','birth_date')
                    ->whereBetween('birth_date',[ $this->start_date, $this->end_date ])
                    ->orderBy('birth_date','ASC')
                    ->get();
    }

    public function headings(): array
    {
         return [
            'Nombre',
            'Email',
            'Fecha de nacimiento'
         ];
    }
}

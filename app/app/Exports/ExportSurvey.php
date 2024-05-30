<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Response;
use App\Models\FormSection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportSurvey implements FromCollection,WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $form_id;
    protected $response;

    public function __construct(int $form_id, array $response)
    {
        $this->form_id = $form_id;
        $this->response = $response;
    }

    public function collection()
    {
        // unset($this->response[0]);
        return collect($this->response);

    }

    public function headings(): array
    {
        $formSections = FormSection::where([['form_id',$this->form_id],['is_question',true]])->get();
        $columns = [
            'Employee Number',
            'Name',
            'Cluster',
            'Company',
            'Business Unit',
            'Department',
            'Position',
            'Area',
            'Location',
            'Hired Date',
            'Date Answered',
            'Time Answered'
        ];

        foreach ($formSections as $key=>$formSection) {
            $columns[] = 'Q'.($key+1);
        }

        return $columns;
    }

    public function map($responses): array
    {
        unset($responses['user_id']);
        return [
            $responses
        ];
    }
}

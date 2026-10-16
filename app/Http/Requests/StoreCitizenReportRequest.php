<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCitizenReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'region_id' => [
                'required',
                'exists:regions,id',
            ],

            'commodity_id' => [
                'required',
                'exists:commodities,id',
            ],

            'description' => [
                'required',
                'string',
                'max:5000',
            ],

            'photo' => [
                'nullable',
                'image',
                'max:5120',
            ],
        ];
    }
}

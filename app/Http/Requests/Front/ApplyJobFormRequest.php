<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class ApplyJobFormRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
            case 'POST': {
                    return [
                        //"cv_id" => "required",
                        //"current_salary" => "required|max:11",
                        "cover_letter" => "required|max:1000",
                        "expected_salary" => "required|max:11",
                        "salary_period_id" => "required"
                        //"salary_currency" => "required|max:5",
                    ];
                }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'cover_letter.required' => __('Please add Cover Letter'),
            'expected_salary.required' => __('Please enter expected budget'),
            'salary_period_id.required' => __('Please enter Period'),
        ];
    }

}

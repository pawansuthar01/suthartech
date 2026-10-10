<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ContactInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['nullable', 'string', 'max:40'],
            'company' => ['nullable', 'string', 'max:160'],
            'project_type' => ['required', 'string', 'max:100'],
            'budget' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,zip,png,jpg,jpeg,txt'],
            'form_type' => ['nullable', 'in:call_request'],
            'call_date' => ['required_if:form_type,call_request', 'nullable', 'date', 'after_or_equal:today'],
            'call_start' => ['required_if:form_type,call_request', 'nullable', 'date_format:H:i'],
            'call_end' => ['required_if:form_type,call_request', 'nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->input('form_type') !== 'call_request') {
                return;
            }

            $start = $this->minutesAfterMidnight($this->input('call_start'));
            $end = $this->minutesAfterMidnight($this->input('call_end'));

            if ($start !== null && ($start < 540 || $start >= 1080)) {
                $validator->errors()->add('call_start', 'Choose a start time between 9:00 AM and 6:00 PM.');
            }

            if ($end !== null && ($end <= ($start ?? 0) || $end > 1080)) {
                $validator->errors()->add('call_end', 'Choose an end time after the start time and no later than 6:00 PM.');
            }
        });
    }

    private function minutesAfterMidnight(mixed $time): ?int
    {
        if (! is_string($time) || ! preg_match('/^(\d{2}):(\d{2})$/', $time, $parts)) {
            return null;
        }

        return ((int) $parts[1] * 60) + (int) $parts[2];
    }
}

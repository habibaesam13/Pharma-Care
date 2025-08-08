<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Carbon\Carbon;
class StoreRequestServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    use ApiResponse;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nurse_id' => 'required|exists:nurses,id',
            'service_id' => 'required|exists:services,id',
            'status' => 'in:pending,accepted,rejected,completed',
            'notes' => 'nullable|string',
            'address' => 'required|string',
            'requested_date' => 'required|date|after_or_equal:today',
            'requested_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    try {
                        $time = Carbon::createFromFormat('H:i', $value);

                        $min = Carbon::createFromTime(7, 0); // 07:00 AM
                        $max = Carbon::createFromTime(1, 0)->addDay(); // 01:00 AM next day

                        // If time is between midnight and 03:00 AM, add a day for comparison
                        if ($time->lt(Carbon::createFromTime(3, 0))) {
                            $time->addDay();
                        }

                        if ($time->lt($min) || $time->gt($max)) {
                            $fail('Requested time must be between 07:00 AM and 01:00 AM.');
                        }

                    } catch (\Exception $e) {
                        $fail('Invalid time format.');
                    }
                }
            ],
            'nurse_type' => 'required|in:male,female,does_not_matter',
        ];
    }
    public function messages(): array
    {
        return [
            'nurse_id.required' => 'Please select a nurse.',
            'nurse_id.exists' => 'The selected nurse does not exist.',

            'service_id.required' => 'Please select a service.',
            'service_id.exists' => 'The selected service does not exist.',

            'status.in' => 'Status must be one of: pending, accepted, rejected, or completed.',

            'notes.string' => 'Notes must be text.',

            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a valid string.',

            'requested_date.required' => 'Please choose a requested date.',
            'requested_date.date' => 'Requested date must be in YYYY-MM-DD format.',
            'requested_date.after_or_equal' => 'Requested date must be today or in the future.',

            'requested_time.required' => 'Please select a time for the visit.',
            'requested_time.date_format' => 'Time must be in HH:MM format (e.g., 13:00).',

            'nurse_type.required' => 'Please select a nurse preference.',
            'nurse_type.in' => 'Nurse type must be male, female, or does not matter.',
        ];
    }

    /**
     * Handle failed validation and return API-friendly response.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            self::validationError($validator->errors())
        );
    }
}

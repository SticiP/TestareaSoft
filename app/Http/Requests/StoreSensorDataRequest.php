<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSensorDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mac_address' => 'required|exists:devices,mac_address',
            'sensors' => 'required|array|min:1',
            'sensors.*.sensor_type' => 'required|string|max:50',
            'sensors.*.sensor_name' => 'required|string|max:255',
            'sensors.*.pin_number' => 'required|integer|min:0|max:100',
            'sensors.*.parameters' => 'nullable|array',
            'sensors.*.value' => 'required|numeric'
        ];
    }
}

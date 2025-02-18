<?php

namespace My\User\Http\Requests\v1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check() === false;
    }

    /** {@inheritDoc} */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => ucwords($this->first_name),
            'last_name' => ucwords($this->last_name),
            'email' => strtolower($this->email)
        ]);
    }

    /** {@inheritDoc} */
    protected function passedValidation(): void
    {
        $this->replace([
            'password' => Hash::make($this->password),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            /**
             * User first name
             * @example John
             */
            'first_name' => ['string', 'required', 'max:' . config('my.user.field.first_name.max_length')],

            /**
             * User last name
             * @example Doe
             */
            'last_name' => ['string', 'required', 'max:' . config('my.user.field.first_name.max_length')],

            /**
             * User email
             * @example john.doe@example.com
             */
            'email' => ['string', 'email', 'required', 'unique:users', 'max:' . config('my.user.field.email.max_length')],

            /**
             * User password
             * @example Password1
             */
            'password' => ['string', 'required', 'confirmed', Password::defaults()],
        ];
    }

    /** {@inheritDoc} */
    public function messages(): array
    {
        return [
            'first_name.required' => __('users::register.first_name.required'),
            'first_name.max' => __('users::register.first_name.max'),
            'last_name.required' => __('users::register.last_name.required'),
            'last_name.max' => __('users::register.last_name.max'),
            'email.required' => __('users::register.email.required'),
            'email.max' => __('users::register.email.max'),
            'email.unique' => __('users::register.email.unique'),
            'password.required' => __('users::register.password.required'),
            'password.confirmed' => __('users::register.password.confirmed'),
        ];
    }

    /** {@inheritDoc} */
    public function attributes(): array
    {
        return [
            'first_name' => __('users::register.first_name'),
            'last_name' => __('users::register.last_name'),
            'email' => __('users::register.email'),
            'password' => __('users::register.password'),
            'password_confirmation' => __('users::register.password_confirmation'),
        ];
    }
}

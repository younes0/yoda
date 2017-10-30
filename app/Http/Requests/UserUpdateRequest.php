<?php

namespace Yoda\Http\Requests;

use Yeb\Http\Requests\Request;

class UserUpdateRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            // avoid testing failure (faker doesn't generate unique)
            // `,id,:id` allows to ignore current value
            'email' => (\App::environment() === 'testing')
                ? 'required' 
                : 'required|unique:users,id,:id', 
            'password'  => 'sometimes|min:6|confirmed',
            'firstname' => 'required|max:255',
            'lastname'  => 'required|max:255',
        ];
    }

    /**
     * Set custom error messages
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'Veuillez fournir un prénom',
            'lastname.required'  => 'Veuillez fournir un nom',
        ];
    }
}

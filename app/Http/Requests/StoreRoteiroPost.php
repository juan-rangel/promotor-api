<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreRoteiroPost extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return json_decode($this->getContent(), true) ?? $this->all();
    }

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
        return [
            'usuario_id' => 'required',
            'tarefa_id' => 'required',
            'cliente_id' => 'required',
            'data_execucao' => 'required',
            'ordem_execucao' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'usuario_id.required' => 'o campo :attribute deve ser preenchido',
            'cliente_id.required' => 'o campo :attribute deve ser preenchido',
            'tarefa_id.tarefa_id' => 'ao menos uma :attribute deve ser preenchida',
            'data_execucao.required' => 'o campo :attribute deve ser preenchido',
            'ordem_execucao.required' => 'o campo :attribute deve ser preenchido',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'usuario_id' => 'usuario',
            'tarefa_id' => 'tarefa',
            'cliente_id' => 'cliente',
            'data_execucao' => 'data de execução',
            'ordem_execucao' => 'ordem de execução',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}

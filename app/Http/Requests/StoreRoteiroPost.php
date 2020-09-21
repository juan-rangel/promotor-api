<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoteiroPost extends FormRequest
{
    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return json_decode($this->getContent(), true);
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
            'tarefa_id' => 'min:1',
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
            'tarefa_id.min' => 'ao menos uma :attribute deve ser preenchida',
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
}

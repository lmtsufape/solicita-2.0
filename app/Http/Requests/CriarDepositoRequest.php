<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CriarDepositoRequest extends FormRequest
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
        return [
            'autor_nome' => 'required|min:3|max:255',
            'titulo_tcc' => 'required|min:3|max:255',
            'registro_patente' => 'required', 'boolean',
            'anexo_tcc' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'anexo_comprovante_autorizacao' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'anexo_publicacao_parcial' => 'file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'autor_nome' => [
                'required' => 'O campo "Nome do Autor" é obrigatório.',
                'min' => 'O campo "Nome do Autor" deve ter pelo menos 3 caracteres.',
                'max' => 'O campo "Nome do Autor" deve ter no máximo 255 caracteres.',
            ],
            'titulo_tcc' => [
                'required' => 'O campo "Título do TCC" é obrigatório.',
                'min' => 'O campo "Título do TCC" deve ter pelo menos 3 caracteres.',
                'max' => 'O campo "Título do TCC" deve ter no máximo 255 caracteres.',
            ],
            'anexo_tcc' => [
                'required' => 'O campo "Anexo do TCC" é obrigatório.',
                'file' => 'O "Anexo do TCC" deve ser um arquivo válido.',
                'mimes' => 'O "Anexo do TCC" deve estar em um dos formatos: PDF, DOC ou DOCX.',
                'max' => 'O "Anexo do TCC" deve ter no máximo 10MB.',
            ],
            'anexo_comprovante_autorizacao' => [
                'required' => 'O campo "Comprovante de Autorização" é obrigatório.',
                'file' => 'O "Comprovante de Autorização" deve ser um arquivo válido.',
                'mimes' => 'O "Comprovante de Autorização" deve estar em um dos formatos: PDF, DOC ou DOCX.',
                'max' => 'O "Comprovante de Autorização" deve ter no máximo 10MB.',
            ],
            'anexo_publicacao_parcial' => [
                'file' => 'O campo "Publicação Parcial" deve ser um arquivo válido.',
                'mimes' => 'O campo "Publicação Parcial" deve estar em um dos formatos: PDF, DOC ou DOCX.',
                'max' => 'O campo "Publicação Parcial" deve ter no máximo 10MB.',
            ],
        ];
    }
}

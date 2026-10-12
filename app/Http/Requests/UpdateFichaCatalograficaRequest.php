<?php

namespace App\Http\Requests;

use App\Models\FichaCatalografica;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFichaCatalograficaRequest extends FormRequest
{
    public function authorize(): bool
    {
       return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'inclui_anexo' => $this->boolean('inclui_anexo'),
            'inclui_apendice' => $this->boolean('inclui_apendice'),
        ]);
    }

    public function rules(): array
    {
        $ficha = FichaCatalografica::findOrFail(
            $this->route('ficha_catalografica_id')
        );

        return [
            'autor_nome' => [
                'required',
                'string',
                'max:255',
            ],

            'autor_sobrenome' => [
                'required',
                'string',
                'max:255',
            ],

            'cutter' => [
                'required',
                'string',
                'max:50',
            ],

            'area_conhecimento' => [
                'required',
                'string',
                'max:255',
            ],

            'titulo' => [
                'required',
                'string',
                'max:255',
            ],

            'subtitulo' => [
                'nullable',
                'string',
                'max:255',
            ],

            'local' => [
                'required',
                'string',
                'max:255',
            ],

            'ano' => [
                'required',
                'integer',
                'digits:4',
            ],

            'folhas' => [
                'required',
                'integer',
                'min:1',
            ],

            'ilustracao' => [
                'required',
                'string',
                'max:255',
            ],

            'tipo_ficha_catalografica_id' => [
                'required',
                'integer',
                Rule::exists('tipos_fichas_catalograficas', 'id'),
            ],

            'inclui_anexo' => [
                'required',
                'boolean',
            ],

            'inclui_apendice' => [
                'required',
                'boolean',
            ],

            'anexo' => [
                Rule::requiredIf(empty($ficha->anexo)),
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'nome_orientador' => [
                'required',
                'string',
                'max:255',
            ],

            'sobrenome_orientador' => [
                'required',
                'string',
                'max:255',
            ],

            'nome_coorientador' => [
                'nullable',
                'string',
                'max:255',
                'required_with:sobrenome_coorientador',
            ],

            'sobrenome_coorientador' => [
                'nullable',
                'string',
                'max:255',
                'required_with:nome_coorientador',
            ],

            'nome_segundo_coorientador' => [
                'nullable',
                'string',
                'max:255',
                'required_with:sobrenome_segundo_coorientador',
            ],

            'sobrenome_segundo_coorientador' => [
                'nullable',
                'string',
                'max:255',
                'required_with:nome_segundo_coorientador',
            ],

            'tipo_curso' => [
                'required_if:tipo_ficha_catalografica_id,2',
                'nullable',
                'string',
                'max:255',
            ],

            'programa' => [
                Rule::requiredIf(function () {
                    return in_array(
                        (int) $this->input('tipo_ficha_catalografica_id'),
                        [1, 3, 4],
                        true
                    );
                }),
                'nullable',
                'string',
                'max:255',
            ],

            'produto' => [
                'required_if:tipo_ficha_catalografica_id,3',
                'nullable',
                'string',
                'max:255',
            ],

            'primeira_chave' => [
                'required',
                'string',
                'max:255',
            ],

            'segunda_chave' => [
                'required',
                'string',
                'max:255',
                'different:primeira_chave',
            ],

            'terceira_chave' => [
                'required',
                'string',
                'max:255',
                'different:primeira_chave',
                'different:segunda_chave',
            ],

            'quarta_chave' => [
                'nullable',
                'string',
                'max:255',
                'different:primeira_chave',
                'different:segunda_chave',
                'different:terceira_chave',
            ],

            'quinta_chave' => [
                'nullable',
                'string',
                'max:255',
                'different:primeira_chave',
                'different:segunda_chave',
                'different:terceira_chave',
                'different:quarta_chave',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'autor_nome.required' =>
                'O nome do autor é obrigatório.',

            'autor_nome.string' =>
                'O nome do autor deve ser um texto.',

            'autor_sobrenome.required' =>
                'O sobrenome do autor é obrigatório.',

            'autor_sobrenome.string' =>
                'O sobrenome do autor deve ser um texto.',

            'cutter.required' =>
                'O Cutter é obrigatório.',

            'area_conhecimento.required' =>
                'A área de conhecimento é obrigatória.',

            'titulo.required' =>
                'O título é obrigatório.',

            'local.required' =>
                'O local é obrigatório.',

            'ano.required' =>
                'O ano é obrigatório.',

            'ano.integer' =>
                'O ano deve ser um número inteiro.',

            'ano.digits' =>
                'O ano deve possuir 4 dígitos.',

            'folhas.required' =>
                'A quantidade de folhas é obrigatória.',

            'folhas.integer' =>
                'A quantidade de folhas deve ser um número inteiro.',

            'folhas.min' =>
                'A quantidade de folhas deve ser maior que zero.',

            'tipo_ficha_catalografica_id.required' =>
                'Selecione o tipo da ficha catalográfica.',

            'tipo_ficha_catalografica_id.integer' =>
                'O tipo selecionado é inválido.',

            'tipo_ficha_catalografica_id.exists' =>
                'O tipo selecionado não existe.',

            'nome_orientador.required' =>
                'O nome do orientador é obrigatório.',

            'sobrenome_orientador.required' =>
                'O sobrenome do orientador é obrigatório.',

            'nome_coorientador.required_with' =>
                'Informe o nome do coorientador.',

            'sobrenome_coorientador.required_with' =>
                'Informe o sobrenome do coorientador.',

            'nome_segundo_coorientador.required_with' =>
                'Informe o nome do segundo coorientador.',

            'sobrenome_segundo_coorientador.required_with' =>
                'Informe o sobrenome do segundo coorientador.',

            'tipo_curso.required_if' =>
                'O tipo de curso é obrigatório para monografia.',

            'programa.required' =>
                'O programa é obrigatório para este tipo de ficha.',

            'produto.required_if' =>
                'O produto é obrigatório para Programa Educacional.',

            'anexo.required' =>
                'O anexo é obrigatório.',

            'anexo.file' =>
                'O anexo enviado é inválido.',

            'anexo.mimes' =>
                'O anexo deve estar no formato PDF.',

            'anexo.max' =>
                'O anexo não pode ser maior que 10 MB.',

            'primeira_chave.required' =>
                'A primeira palavra-chave é obrigatória.',

            'segunda_chave.required' =>
                'A segunda palavra-chave é obrigatória.',

            'terceira_chave.required' =>
                'A terceira palavra-chave é obrigatória.',

            'segunda_chave.different' =>
                'As palavras-chave não podem ser iguais.',

            'terceira_chave.different' =>
                'As palavras-chave não podem ser iguais.',

            'quarta_chave.different' =>
                'As palavras-chave não podem ser iguais.',

            'quinta_chave.different' =>
                'As palavras-chave não podem ser iguais.',
        ];
    }

    public function attributes(): array
    {
        return [
            'autor_nome' => 'nome do autor',
            'autor_sobrenome' => 'sobrenome do autor',
            'cutter' => 'Cutter',
            'area_conhecimento' => 'área de conhecimento',
            'titulo' => 'título',
            'subtitulo' => 'subtítulo',
            'local' => 'local',
            'ano' => 'ano',
            'folhas' => 'quantidade de folhas',
            'ilustracao' => 'ilustração',
            'tipo_ficha_catalografica_id' => 'tipo da ficha catalográfica',
            'inclui_anexo' => 'inclusão de anexo',
            'inclui_apendice' => 'inclusão de apêndice',
            'anexo' => 'anexo',
            'nome_orientador' => 'nome do orientador',
            'sobrenome_orientador' => 'sobrenome do orientador',
            'nome_coorientador' => 'nome do coorientador',
            'sobrenome_coorientador' => 'sobrenome do coorientador',
            'nome_segundo_coorientador' => 'nome do segundo coorientador',
            'sobrenome_segundo_coorientador' => 'sobrenome do segundo coorientador',
            'tipo_curso' => 'tipo de curso',
            'programa' => 'programa',
            'produto' => 'produto',
            'primeira_chave' => 'primeira palavra-chave',
            'segunda_chave' => 'segunda palavra-chave',
            'terceira_chave' => 'terceira palavra-chave',
            'quarta_chave' => 'quarta palavra-chave',
            'quinta_chave' => 'quinta palavra-chave',
        ];
    }
}

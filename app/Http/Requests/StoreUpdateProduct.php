<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateProduct extends FormRequest
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('price')) {
            // Converte o preço do formato brasileiro (1.234,56) para o formato americano (1234.56)
            $price = $this->input('price');
            
            // Remove pontos (separadores de milhares) e substitui vírgula por ponto
            $price = str_replace('.', '', $price);
            $price = str_replace(',', '.', $price);
            
            $this->merge([
                'price' => $price
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->segment(3);

        $rules =  [
            'title' => ['required', 'min:3', 'max:255', "unique:products,title,{$id},id"],
            'description' => ['required', 'min:3', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['required', 'image'],
        ];

        if ($this->method() == 'PUT') {
            $rules['image'] = ['nullable', 'image'];
        }

        return $rules;
    }
    
    /**
     * Mensagens customizadas de validação
     */
    public function messages()
    {
        return [
            'title.required' => 'O título é obrigatório',
            'title.min' => 'O título deve ter pelo menos 3 caracteres',
            'title.max' => 'O título não pode ter mais de 255 caracteres',
            'title.unique' => 'Este título já está sendo usado',
            'description.required' => 'A descrição é obrigatória',
            'description.min' => 'A descrição deve ter pelo menos 3 caracteres',
            'description.max' => 'A descrição não pode ter mais de 10000 caracteres',
            'price.required' => 'O preço é obrigatório',
            'price.numeric' => 'O preço deve ser um número válido',
            'price.min' => 'O preço deve ser maior que zero',
            'image.required' => 'Por favor, selecione uma imagem para o produto',
            'image.image' => 'O arquivo deve ser uma imagem válida',
        ];
    }
}

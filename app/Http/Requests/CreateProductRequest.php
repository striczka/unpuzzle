<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class CreateProductRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
//	        'slug' => 'required|unique:products,slug',
	        'slug' => 'required',
	        'price' => ['regex:/[\d]+\.?[\d]+/','required'],
	        'discount' => 'integer',
	        'category_id' => 'integer',
//	        'article' => 'required|unique:products,article',
	        'article' => 'required',
	        'excerpt' => 'max:150',
			'flash_view' => 'mimes:swf'
        ];
    }

	public function messages()
	{
		return [
			'title.required' => 'Введите название товара',
			'slug.required' => 'Введите ссылку для товара',
			'slug.unique' => 'Ссылка должна быть уникальна, у вас уже есть товар с такой ссылкой',
			'price.required' => 'Укажите цену товара',
			'price.float' => 'Цена должна быть числом',
			'category_id.integer' => 'Укажите правильную категория из списка',
			'article.required' => 'Введите артикул товара',
			'article.unique' => 'Артикул должен быть уникален, у вас уже есть товар с таким артикулом',
			'discount.integer' => 'Скидка должна быть числом',
			'excerpt.max' => 'Краткое описание не должно превышать 150 символов',
			'flash_view.mimes' => '3D просмотр должен быть в формате SWF',
		];
	}
}

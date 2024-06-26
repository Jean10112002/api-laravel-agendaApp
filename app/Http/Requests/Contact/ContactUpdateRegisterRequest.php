<?php

namespace App\Http\Requests\Contact;

use App\Http\Responses\ApiResponses;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Auth;
use Illuminate\Support\Facades\Crypt;


/**
 * @OA\Schema(
 *     schema="ContactUpdateRegisterRequest",
 *     type="object",
 *     title="Contact Update Request",
 *     description="Request body for updating a contact",
 *     required={"name", "phone"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Contact name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="Contact phone number",
 *         example="123456789"
 *     )
 * )
 */

class ContactUpdateRegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        // Obtenemos el user_id del usuario autenticado
        $userId = Auth::guard('sanctum')->user()->id;

        // Desencriptamos el ID del contacto que viene en la ruta
        $id = Crypt::decrypt($this->route('id'));

        return [
            'name' => 'required|string|min:3|max:255',
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:10',
                Rule::unique('contacts')
                    ->where(function ($query) use ($userId) {
                        return $query->where('user_id', $userId)->where('id',$this->id)->whereNull('deleted_at');
                    })
                    ->ignore($id), // Ignoramos el registro actual para permitir la actualización
            ],
            'nickname' => 'nullable|string|min:3|max:255'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            ApiResponses::error('Error de validación',422,$errors)
        );
    }
}

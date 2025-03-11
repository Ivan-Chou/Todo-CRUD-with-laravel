<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserInfoRequest extends FormRequest
{
    protected const USERNAME_LEN_MIN = 3;
    protected const USERNAME_LEN_MAX = 20;
    protected const PASSWORD_LEN_MIN = 6;
    protected const PASSWORD_LEN_MAX = 20;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Todo: check if conflict with sanctum

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 必填, 要是字串, 只含字母和數字 3-20 字元
            "username" => [
                "required",
                "string",
                "regex:/^[a-zA-Z0-9]{" . self::USERNAME_LEN_MIN . ", " . self::USERNAME_LEN_MAX . "}$/"
            ],

            // 必填, 要是字串, 6-20 字元
            "password" => [
                "required",
                "string",
                "min:" . self::PASSWORD_LEN_MIN,
                "max:" . self::PASSWORD_LEN_MAX,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            "username.required" => "Username is required",
            "username.string" => "Username must be a string",
            "username.regex" => "Username must be " . self::USERNAME_LEN_MIN . "-" . self::USERNAME_LEN_MAX . " characters long and only contain letters and numbers",
            
            "password.required" => "Password is required",
            "password.string" => "Password must be a string",
            "password.min" => "Password must be " . self::PASSWORD_LEN_MIN . "-" . self::PASSWORD_LEN_MAX . " characters long",
            "password.max" => "Password must be " . self::PASSWORD_LEN_MIN . "-" . self::PASSWORD_LEN_MAX . " characters long",
        ];
    }

    // Todo: 可能需要自訂錯誤回應以符合公司規範
    public function failedValidation(Validator $validator)
    {
        // validator->errors() 會回傳 [有問題欄位名 => 上方 messages() 定義的錯誤訊息]
        // 422 代表資料有問題
        $response = response()->json([
            "message" => "The given data was invalid.",
            "errors" => $validator->errors(),
        ], 422);

        throw new HttpResponseException($response);
    }
}

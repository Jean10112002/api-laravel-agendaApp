<?php
namespace App\Repository\Contact;
use App\Interfaces\Contact\ContactInterface;
use App\Http\Responses\ApiResponses;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ContactRepository implements ContactInterface{

    public function index(){
        $user = Auth::guard('sanctum')->user();
        $contacts = Contact::where(['user_id' => $user->id])->get();
        return ApiResponses::succes('Lista de contactos de un usuario.', 200, $contacts);
    }

    public function store(Request $request){
        Contact::create($request->all());
        return ApiResponses::succes('Se ha creado exitosamente el contacto', 201);
    }

    public function show($id){
        $contact = Contact::where(['id' => $id])->firstOrFail();
        return ApiResponses::succes('Mostrando Contacto', 200, $contact);
    }

    public function update(Request $request, $id){
        $contacto = Contact::where(['id' => $id])->firstOrFail();
        $contacto->update($request->all());
        return ApiResponses::succes('Se actualizó correctamente el contacto', 202, $contacto);
    }

    public function delete($id){

    }
}
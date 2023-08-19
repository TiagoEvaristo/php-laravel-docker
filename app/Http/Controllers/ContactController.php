<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return all user contacts
        $contacts = Contact::where('user_id', auth()->user()->id)->get()->load('user');

        return $contacts;
    }

    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'telefone' => ['required', 'string', 'max:9', 'unique:contacts,telefone'],
            'dd' => ['required', 'string', 'max:2'],
            'status' => ['required', 'boolean']
        ]);

        //create contact
        $contact = Contact::create([
            'user_id' => $request->user_id,
            'telefone' => $request->telefone,
            'dd' => $request->dd,
            'status' => $request->status
        ]);

        //if the contact is created return the response and the contact
        if ($contact) {
            return response()->json([
                'success' => true,
                'message' => 'Contact created successfully',
                'contact' => $contact
            ], 201);
        }

        //if the contact is not created return the response
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 400);
    }

    public function show(Contact $contact)
    {
        //return the contact with the user
        $contact = $contact->load('user');
        
        return $contact;
    }


    public function update(Request $request, Contact $contact)
    {
        //validate the request the fields are not required
        $request->validate([
            'user_id' => ['exists:users,id'],
            'telefone' => ['string', 'max:9', 'unique:contacts,telefone'],
            'dd' => ['string', 'max:2'],
            'status' => ['boolean']
        ]);

        //if the request field is isset update the contact if not use the old data
        $contact->user_id = isset($request->user_id) ? $request->user_id : $contact->user_id;
        $contact->telefone = isset($request->telefone) ? $request->telefone : $contact->telefone;
        $contact->dd = isset($request->dd) ? $request->dd : $contact->dd;
        $contact->status = isset($request->status) ? $request->status : $contact->status;

        $contact->save();

        //if the contact is updated return the response and the contact
        if ($contact) {
            return response()->json([
                'success' => true,
                'message' => 'Contact updated successfully',
                'contact' => $contact
            ], 200);
        }

        //if the contact is not updated return the response
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        //delete the contact
        $contact->delete();

        //if the contact is deleted return the response
        if ($contact) {
            return response()->json([
                'success' => true,
                'message' => 'Contact deleted successfully'
            ], 200);
        }

        //if the contact is not deleted return the response
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong'
        ], 400);
    }
}

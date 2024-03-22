<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactsController extends Controller
{

    public function index(Request $request)
    {
        $contacts = Contact::with('user')->latest();
        if ($request->query('id')) {
            $contacts->where('id','=', $request->query('id'));
        }
        if ($request->query('contact_name')) {
            $contacts->where('name','like','%'.$request->query('contact_name').'%');
        }
        if($request->query('user')){
            $contacts->whereHas('user',function($query) use ($request){
                $query->whereRaw('concat_ws(" ",first_name,last_name) like ?',['%'.$request->query('user').'%']);
            });
        }
        if ($request->query('email')) {
            $contacts->where('email', 'like','%'.$request->query('email').'%');
        }
        $contacts = $contacts->paginate(20);

        return view('contacts.index', compact('contacts'));
    }

}

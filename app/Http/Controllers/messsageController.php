<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class messsageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


    // Récupérer les messages échangés entre les deux utilisateurs
    $messages = Message::where(function($query) use ($request) {
        $query->where('id_emetteur', Auth()->id())
              ->where('id_reception', $request->id_reception);
    })
    ->orWhere(function($query) use ($request) {
        $query->where('id_emetteur', $request->id_reception)
              ->where('id_reception', Auth()->id());
    })
    ->orderBy('created_at', 'asc')
    ->get();

    return response()->json($messages);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = new Message();
        $message->message = $request->message;
        $message->id_reception = $request->id_reception;
        $message->id_emetteur = Auth::id(); //id de l'utilisateur connecter
        $message->save();
        return response()->json(['success' => true, 'message' => $message], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

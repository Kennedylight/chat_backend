<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MailCodeForRegister;
use App\Models\Code;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        User::find($id)->delete();
    }
    public function allEtudiants(){
        $users = User::select("*")->orderBy('name','ASC')->where("type",0)->get();
        return response()->json(["profs"=>$users]);  
    }
    public function allProfs(){
        $users = User::select("*")->orderBy('name','ASC')->where("type",1)->get();
        return response()->json(["profs"=>$users]);  
    }
    public function index()
    {
        $users = User::select("*")->orderBy('id','DESC')->get();
        return response()->json($users);
    }
    public function sendCodeViaEmail(Request $request){
        $code = new Code();
        $code->email = $request->email;
        $code->code =rand(1542 ,9999);
        Mail::to($code->email)->send(new MailCodeForRegister($code->code));
       $code->save();
       return response()->json("mail parti");

    }
    public function verifiedCodeUserWithCodeEmail(Request $request){
        $code = Code::where('email', $request->email)->first();
        if($code->code == $request->code)
        return true;
    return false;

    }
    public function login(Request $request){
        $validator  = Validator::make($request->all(), [
            "email" => "required|string|email|max:255",
            "password" => "required|string|max:160",
        ]);
        if ($validator->fails()) {
            return response(["errors" => $validator->errors()->all()], 422);
        }
        $user = User::where("email", $request->email)->first();
        if($user){
            if(Hash::check($request->password , $user->password)){
                $token = $user->createToken("Laravel Password Grant Client")->accessToken;
                $response = ["token" => $token];
                return response(['access_token' => $token , "user"=>$user]);
            }
            else{
                $response = ["errors"=>["mot de passe incorrect"]];
                return Response()->json($response,422);
            }

        }
        else{
            $response = ["errors" => ["ce compte n\'existe pas"]];
            return Response()->json($response, 422);
        }
        $response = ["errors" => ["mot de passe incorrect"]];
        return Response()->json($response, 200);
    }
    public function changeImage(Request $request,$id){

        if ($request->hasFile("image")) {
            $file = $request->file("image");
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $file->move(public_path("avatars"), $filename);

            User::find($id)->update(["image" => $filename]);
        }
        $user = User::find($id);
        return response()->json($user);
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
    // public function logout()
    // {
    //     $token = Auth()->user();
    //     $token->token()->revoke();

    //     $response = ['message' => 'You have been successfully logged out!'];
    //     return response($response, 200);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function store(Request $request)
    {

        $validator  = Validator::make($request->all(),[
            "name"=>"required|string|max:160",
            "email"=>"required|string|email|max:255|unique:users",
            "password"=>"required|string|max:160",
        ]);
        if($validator->fails()){
            return response(["errors"=>$validator->errors()->all()],422);
        }
        $request['password'] = Hash::make($request["password"]);
        $request["remember_token"] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken("Laravel Password Grant Client")->accessToken;

        return response(["access_token" =>$token , "user" =>$user]);
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
 
}

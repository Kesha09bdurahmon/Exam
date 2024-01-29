<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AppUserController extends Controller
{
    //
     /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"AppUser API"},
     *     summary="Register",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="phone", type="string", example="998905711816"),
     *              @OA\Property(property="fullname", type="string", example="Abdurahmon"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          ),
     *     ),
     *     @OA\Response(
     *     response="200",
     *     description="Check user **token** and added new task",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="true",
     *                  description="success",
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  format="object",
     *                  description="data",
     *                  property="data",
     *                  example=null
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="Qo'shildi",
     *                  description="message",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  format="integer",
     *                  default="0",
     *                  description="error_code",
     *                  property="error_code"
     *              ),
     *          ),
     *     ),
     * )
     */
    public function register(Request $request){
        if(strlen($request->password) < 6){
            return $this->sendResponse(null,false,"Parol kamida 6 belgidan iborat bo'lsin!");
        }

        AppUser::create([
            'phone'=>$request->phone,
            'fullname'=>$request->fullname,
            'password'=>Hash::make($request->password),
        ]);
        return $this->sendResponse(null,true,"Qo'shildi");
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"AppUser API"},
     *     summary="Login",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="phone", type="string", example="998905711816"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *          ),
     *     ),
     *     @OA\Response(
     *     response="200",
     *     description="Check user **token** and added new task",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="true",
     *                  description="success",
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  format="object",
     *                  description="data",
     *                  property="data",
     *                  example= {
     *                          "id": 17,
     *                          "phone": "998905711816",
     *                          "fullname": "Abdurahmon",
     *                          "token": "htg387htv73gw3yg8v9g8yrfg923f4",
     *                          "password": "$2y$12$lkQctPgpsD2bYXkgHPJLt.DLpAJwjS.uXlCpc8Bt39pGv4G8tcNTK",
     *                          "created_at": "2023-08-29T04:39:24.000000Z",
     *                          "updated_at": null
     *          }
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="Xush Kelibsiz!",
     *                  description="message",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  format="integer",
     *                  default="0",
     *                  description="error_code",
     *                  property="error_code"
     *              ),
     *          ),
     *     ),
     * )
     */
    public function login(Request $request){
        $AppUser_find = AppUser::where('phone',$request->phone)->first();
        if($AppUser_find == null){
            return $this->sendResponse(null,false,"AppUser topilmadi!");
        }
        if(!Hash::check( $request->password,$AppUser_find->password)){
            return $this->sendResponse(null,false,"Parol Xato!");
        }
        $token = Str::random(30);
        $AppUser_find->update([
            'token'=>$token
        ]);
        $AppUser_find = AppUser::where('phone',$request->phone)->first();
        return $this->sendResponse($AppUser_find,true,"Xush Kelibsiz!");
    }
    /**
     * @OA\Get(
     *     path="/api/get",
     *     tags={"AppUser API"},
     *     summary="GetAppUser",
     *   @OA\Parameter(
     *           name="Token",
     *           in="header",
     *           description="User token",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *     @OA\Response(
     *     response="200",
     *     description="Check user **token** and added new task",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="true",
     *                  description="success",
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  format="object",
     *                  description="data",
     *                  property="data",
     *                  example={
     *                          "id": 17,
     *                          "phone": "998905711816",
     *                          "fullname": "Abdurahmon",
     *                          "token": "htg387htv73gw3yg8v9g8yrfg923f4",
     *                          "password": "$2y$12$lkQctPgpsD2bYXkgHPJLt.DLpAJwjS.uXlCpc8Bt39pGv4G8tcNTK",
     *                          "created_at": "2023-08-29T04:39:24.000000Z",
     *                          "updated_at": null
     *          }
     *
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="",
     *                  description="message",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  format="integer",
     *                  default="0",
     *                  description="error_code",
     *                  property="error_code"
     *              ),
     *          ),
     *     ),
     * )
     */
    public function getAppUser(Request $request){
        $check_AppUser = $request->check_AppUser;
        return $this->sendResponse($check_AppUser,true,"");

    }
    /**
     * @OA\Get(
     *     path="/api/list",
     *     tags={"AppUser API"},
     *     summary="GetUserlist",
     *   @OA\Parameter(
     *           name="Token",
     *           in="header",
     *           description="User token",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *     @OA\Response(
     *     response="200",
     *     description="Check user **token** and added new task",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="true",
     *                  description="success",
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  format="object",
     *                  description="data",
     *                  property="data",
     *                  example={
     *                         {
     *                          "id": 17,
     *                          "phone": "998905711816",
     *                          "fullname": "Abdurahmon",
     *                          "token": "htg@htg387htv73gw3yg8v9g#%@8yr3f4",
     *                          "password": "$2y$12$lkQctPgpsD2bYXkgHPJLt.DLpAJwjS.uXlCpc8Bt39pGv4G8tcNTK",
     *                          "created_at": "2023-08-29T04:39:24.000000Z",
     *                          "updated_at": null
     *                          },
     * {
     *                          "id": 19,
     *                          "phone": "998905711816",
     *                          "fullname": "Abdurahmon",
     *                          "token": "htg387htv73^%@gw3yg#$%^28v9g8yrfg9",
     *                          "password": "$2y$12$lkQctPgpsD2bYXkgHPJLt.DLpAJwjS.uXlCpc8Bt39pGv4G8tcNTK",
     *                          "created_at": "2023-08-29T04:39:24.000000Z",
     *                          "updated_at": null
     *                          },}
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="Here is your list",
     *                  description="message",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  format="integer",
     *                  default="0",
     *                  description="error_code",
     *                  property="error_code"
     *              ),
     *          ),
     *     ),
     * )
     */
    public function list(){
        
       
        $headers = getallheaders();
        $token = isset($headers['Token']) ? $headers['Token'] : "";
        $post_all = Post::all();
        if(empty($token) ){
            return $this->sendResponse($post_all,true,"");
        }else{
            $check_AppUser = AppUser::where("token",$this->getToken())->first();
            $post = Post::where("user_id",$check_AppUser->id)->get();
        if($check_AppUser == null){
            return $this->sendResponse(null,false,"User topilmadi");
        }else{
            return $this->sendResponse($post,true,"");
        }
        }

       
    }
    /**
         * @OA\Post(
         *     path="/api/create",
         *     tags={"AppUser API"},
         *     summary="Add AppUser",
         *     @OA\Parameter(
         *         name="Token",
         *         in="header",
         *         description="User token",
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\RequestBody(
         *              @OA\MediaType(
         *                       mediaType="multipart/form-data",
         *                       @OA\Schema(
         *                           @OA\Property(
         *                               property="image",
         *                               type="file",
         *                               format="file"
         *                           ),
         *              @OA\Property(property="content", type="string", example="Content"),
         *              @OA\Property(property="title", type="string", example="Title"),
         *                       )
         *                  ),
         *     ),
         *     @OA\Response(
         *     response="200",
         *     description="Add AppUser.",
         *     @OA\JsonContent(
         *              type="object",
         *              @OA\Property(
         *                  format="boolean",
         *                  default="true",
         *                  description="success",
         *                  property="success"
         *              ),
         *              @OA\Property(
         *                  type="object",
         *                  description="data",
         *                  property="data",
         *                  example="null",
         *              ),
         *              @OA\Property(
         *                  format="string",
         *                  default="Qo'shildi!",
         *                  description="message",
         *                  property="message"
         *              ),
         *              @OA\Property(
         *                  format="integer",
         *                  default="0",
         *                  description="error_code",
         *                  property="error_code"
         *              ),
         *          ),
         *     ),
         * )
         */
    public function add(Request $request){  
        $check_AppUser = AppUser::where("token",$this->getToken())->first();
        if($check_AppUser == null){
            return $this->sendResponse(null,false,"User topilmadi");
        }
        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'upload';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path . $image_full_name;
        $success = $image->move($upload_path, $image_full_name);
        Post::create([
            "image" => $save_url_image,
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $check_AppUser->id,
        ]);
        return $this->sendResponse(null,true,"Qo'shildi");
        
    }
     /**
         * @OA\Post(
         *     path="/api/update/{id}",
         *     tags={"AppUser API"},
         *     summary="Update AppUser",
         *     @OA\Parameter(
         *         name="Token",
         *         in="header",
         *         description="User token",
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         description="Post id",
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *     @OA\RequestBody(
         *              @OA\MediaType(
         *                       mediaType="multipart/form-data",
         *                       @OA\Schema(
         *                           @OA\Property(
         *                               property="image",
         *                               type="file",
         *                               format="file"
         *                           ),
         *              @OA\Property(property="content", type="string", example="Content"),
         *              @OA\Property(property="title", type="string", example="Title"),
         *                       )
         *                  ),
         *     ),
         *     @OA\Response(
         *     response="200",
         *     description="Add AppUser.",
         *     @OA\JsonContent(
         *              type="object",
         *              @OA\Property(
         *                  format="boolean",
         *                  default="true",
         *                  description="success",
         *                  property="success"
         *              ),
         *              @OA\Property(
         *                  type="object",
         *                  description="data",
         *                  property="data",
         *                  example="null",
         *              ),
         *              @OA\Property(
         *                  format="string",
         *                  default="O'zgartirildi!",
         *                  description="message",
         *                  property="message"
         *              ),
         *              @OA\Property(
         *                  format="integer",
         *                  default="0",
         *                  description="error_code",
         *                  property="error_code"
         *              ),
         *          ),
         *     ),
         * )
         */
    public function new(Request $request,$id){
        $check_AppUser = AppUser::where("token",$this->getToken())->first();
        if($check_AppUser == null){
            return $this->sendResponse(null,false,"User topilmadi");
        }
        $user = Post::find($id);
        if(!$user){
            return $this->sendResponse(null,false,"Post topilmadi");
        }else{
            if($user->user_id !== $check_AppUser->id){
                return $this->sendResponse(null,false,"Post sizga tegishlik emas");
            }else{
                
        $image = $request->file('image');

        $image_name = Str::random(20);
        $ext = strtolower($image->getClientOriginalExtension()); // You can use also getClientOriginalName()
        $image_full_name = $image_name . '.' . $ext;
        $upload_path = 'upload';    //Creating Sub directory in Public folder to put image
        $save_url_image = $upload_path . $image_full_name;
        $success = $image->move($upload_path, $image_full_name);
                 $user->update([
            "image" => $save_url_image,
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $check_AppUser->id,
        ]);
            }
            
        }
        return $this->sendResponse(null,true,"O'zgartirildi!");
    }
        /**
     * @OA\Delete(
     *     path="/api/delete/{id}",
     *     tags={"AppUser API"},
     *     summary="Delete post",
     *   @OA\Parameter(
     *           name="Token",
     *           in="header",
     *           description="User token",
     *           @OA\Schema(
     *               type="string"
     *           )
     *       ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="id",
     *          required=true,
     *       ),
     *     @OA\Response(
     *     response="200",
     *     description="Check user **token** and delete this task",
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  format="boolean",
     *                  default="true",
     *                  description="success",
     *                  property="success"
     *              ),
     *              @OA\Property(
     *                  format="object",
     *                  description="data",
     *                  property="data",
     *                  example=null
     *              ),
     *              @OA\Property(
     *                  format="string",
     *                  default="O'chirildi",
     *                  description="message",
     *                  property="message"
     *              ),
     *              @OA\Property(
     *                  format="integer",
     *                  default="0",
     *                  description="error_code",
     *                  property="error_code"
     *              ),
     *          ),
     *     ),
     * )
     */
    public function destroy($id){
        $check_AppUser = AppUser::where("token",$this->getToken())->first();
        if($check_AppUser == null){
            return $this->sendResponse(null,false,"User topilmadi");
        }
        $user = Post::find($id);
        if(!$user){
            return $this->sendResponse(null,false,"Post topilmadi");
        }else{
            $user = Post::find($id);
            $check_AppUser = AppUser::where("token",$this->getToken())->first();
            if($user->user_id !== $check_AppUser->id){
                return $this->sendResponse(null,false,"Post sizga tegishik emas");
            }else{
                $user->delete(); 
            }
            
        }
        return $this->sendResponse(null,true,"O'chirildi!");
    }

}

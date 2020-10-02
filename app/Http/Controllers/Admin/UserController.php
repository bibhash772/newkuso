<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserRegistration;
use App\Mail\SendGiftCardToUsers;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\Giftcard;
use Carbon\Carbon;
use Session;
use Response;

class UserController extends Controller
{

	/**
	 * display user list
	*/
   function __construct(){
        $this->middleware(function ($request, $next) {
          $this->user= Auth::user();
          if($this->user->user_type!=1){
            return redirect('/admin/home');
          }
          return $next($request);
        });
    }
    
	  public function index(){
      return  view('admin.user.list');
    }

    public function sendGiftCards(){

      $client   = new \GuzzleHttp\Client(); 
      $res      = $client->request('GET', 'https://cudos.herokuapp.com/api/products', [
                          'query' => [
                              'page'     => 1,
                              'per_page' => 2,
                          ],
                          'headers' => [
                                          'app-bundle-id' => '214',
                                          'app-version' => '0.2.0',
                                          'app-key' => '508C7286-C3D5-47C3-9E09-D382653DBE125']
                                        ]);
      $repositories   = json_decode($res->getBody()->getContents(), true);
      $giftcards      = $repositories['Data']['GiftCards'] ?? [];
      $Merchants      = $repositories['Data']['Merchants'] ?? [];
      /*$Promos         = $repositories['Data']['Promos'] ?? [];
      $Categories     = $repositories['Data']['Categories'] ?? [];*/

      return  view('admin.user.send_gift_cards')->with(['giftcards'=> $giftcards, 'Merchants'=> $Merchants]);
    }

    public function sendGiftCardsToUsers(Request $request){


        $giftcardsdata      = explode(",",$request->giftcarddetails);
        $allgiftcardarrdata = [];

        if($giftcardsdata){

          foreach($giftcardsdata as $key => $giftcard){

            $cardgiftid                           = explode("%-%", $giftcard);
            $cardgiftimg                          = explode("%#%", $cardgiftid[1]);
            $cardgiftname                         = explode("%*%", $cardgiftimg[1]);

            $allgiftcardarrdata[$key]['giftid']   = $cardgiftid[0];
            $allgiftcardarrdata[$key]['img']      = $cardgiftimg[0];
            $allgiftcardarrdata[$key]['name']     = $cardgiftname[0];
            $allgiftcardarrdata[$key]['category'] = $cardgiftname[1];
          }
        }else{
          return response()->json([
                    "status" => false,
                    "message" => "Gift card data is missing.Please try again later."
                ], 200);
        }

        $sender_name      = $request->sender_name;
        $email_addresses  = explode(',', $request->email_address);
        $message          = $request->message;
        $company_logo_url = url('/');
        
        if($request->hasFile('company_logo')){
          
          $imageName        = time().'.'.request()->company_logo->getClientOriginalExtension();
          $request->company_logo->move(public_path('images'), $imageName);
          $company_logo_url = url('/').'/images/'.$imageName;

        }

        $finalinsertdata =  [];

        if(count($email_addresses) > 0){

          foreach($email_addresses as $key => $val){
              
              if($val != null){

                  $data['details_json']       = json_encode($allgiftcardarrdata);
                  $data['sender_name']        = $request->sender_name;
                  $data['message']            = $request->message;
                  $data['email']              = $val;
                  $data['is_sent']            = 1;
                  $data['company_logo']       = $company_logo_url;
                  $data['created_at']         = \Carbon\Carbon::now()->toDateTimeString();
                  $data['updated_at']         = \Carbon\Carbon::now()->toDateTimeString();
                  
                  array_push($finalinsertdata, $data);
              }
          }
          Giftcard::insert($finalinsertdata);

          /* Send Email to users here */
          $when                   = now()->addMinutes(1);
          Mail::to($email_addresses)->later($when, new SendGiftCardToUsers([
              'sender_name'  =>  $request->sender_name,
              'subject'  =>  "Kudos API Gift Card",
              'message'  =>  $request->message,
              'company_logo'  =>  $company_logo_url,
              'details_json'  =>  $allgiftcardarrdata
          ]));
          /* Send Email to users here */
          return response()->json([
                    "status" => true,
                    "message" => "success."
                ], 200);
        }else{
          return response()->json([
                    "status" => false,
                    "message" => "No email address found to send."
                ], 200);
        }
    }
    /**
     * [Addnew user]
    */
   	/**
    * [Addnew user list]
    */
   	public  function userlist(Request $request){


      try {

            if ($request->ajax()) {

                $model = Giftcard::where([
                                        'is_sent'=> 1,
                                ]);
                if($request->has('order')){
                    $orderColumn = $request->columns[$request->order[0]["column"]]["data"];
                    $model->orderBy($orderColumn,$request->order[0]['dir']);
                }

                return datatables()->eloquent($model)
                    ->filter(function ($query) use ($request) {

                      if ($search = strtolower(trim(request()->search['value']))) {

                        $query->where(function ($query) use ($search) {
                            $query->where('sender_name', 'like', "%" . $search . "%")
                            ->orWhere('email', 'like', "%" . $search . "%");
                        });
                      }
                    })
                    ->addColumn('gift_category', function (Giftcard $Giftcard) {
                        return '';
                    })
                    ->addColumn('gift_name', function (Giftcard $Giftcard) {
                      return '';
                    })
                    ->addColumn('gift_img', function (Giftcard $Giftcard) {
                      return '';
                    })
                    ->toJson();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /**
     * Chnage user status
     */
    public function ChnageUserStatus(Request $request){
        try {
              if ($request->ajax()) {

                $user = User::where('user_id',$request->id)->first();
                if($user->status==0){
                    $user->status=1;
                    if($user->save()){
                        return response()->json(['user_status' => 'Active', 'status_class' => 'label-success label label-default ustatus']);
                    }
                }else{
                  $user->status=0;
                    if($user->save()){
                        return response()->json(['user_status' => 'Inactive', 'status_class' => 'label-danger label label-default ustatus']);
                    }
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function Addnew(){
    	return  view('admin.user.add-new');
    }
    /**
     * [Adduser add new user]
     * @param Request $request [get user detail]
    */
    public function Adduser(Request $request){
    	$user= new User;
    	$user->first_name = $request->input('first_name');
  		$user->last_name = $request->input('last_name');
  		$user->email = $request->input('email');
  		$user->phone_no = $request->input('phone_no');
  		$user->city = $request->input('city');
  		$user->state = $request->input('state');
  		$user->country = $request->input('country');
  		$user->zip_code = $request->input('zip_code');
  		$user->address = $request->input('address');
  		$user->created_by =Auth::user()->user_id;
  		$user->updated_by =Auth::user()->user_id;
  		$user->is_deleted =0;
  		$user->user_type =3;
  		$user->status =0;
  		$user->account_activation_token =md5(Carbon::now().$request->input('email'));
  		$user->is_account_activated =0;
  		$user->created_at =Carbon::now();
  		if($user->save()){
  			    $email = new \stdClass();
         		$email->receiver =$user->first_name.' '.$user->last_name;
          	$email->account_type = 'user';
          	$email->email_id = $user->email;
          	$email->url = route('generate-password',$user->account_activation_token);
   			    Mail::to($user->email)->send(new UserRegistration($email));

      		    Session::flash('message', 'User added successfully please check email id!'); 
              Session::flash('alert-class', 'alert alert-success alert-dismissible fade show');
              return  view('admin.user.add-new');
      	}else{
      		Session::flash('message', 'There are some error try again !'); 
              Session::flash('alert-class', 'alert alert-danger alert-dismissible fade show');
              return  view('admin.user.add-new', ['user' => $user]);
      	}

	}
      /**
     * [Adduser add new user]
     * @param Request $request [get user detail]
    */
    public function Edituser(Request $request){
      $user = User::where('user_id',$request->user_id)->first();
      if(isset($request->first_name)){
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone_no = $request->input('phone_no');
        $user->city = $request->input('city');
        $user->state = $request->input('state');
        $user->country = $request->input('country');
        $user->zip_code = $request->input('zip_code');
        $user->address = $request->input('address');
        $user->created_by =Auth::user()->user_id;
        $user->updated_by =Auth::user()->user_id;
        $user->created_at =Carbon::now();
        $user->updated_at =Carbon::now();
        if($user->save()){
                Session::flash('message', 'User Updated successfully'); 
                Session::flash('alert-class', 'alert alert-success alert-dismissible fade show');
               return  view('admin.user.edit-user', ['user' => $user]);
          }else{
            Session::flash('message', 'There are some error try again !'); 
                Session::flash('alert-class', 'alert alert-danger alert-dismissible fade show');
                return  view('admin.user.edit-user', ['user' => $user]);
          }
      }
    return  view('admin.user.edit-user', ['user' => $user]);  
  }
}

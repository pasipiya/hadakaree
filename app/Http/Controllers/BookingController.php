<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Booking;
use App\Saloon;
use Auth;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
      public function __construct()
{
    $this->middleware('auth');
}  
    
    
    public function index()
    {
        //$booking=Booking::all();
        $id=Auth::user()->id;
        $booking = DB::table('booking')->select('*')->where('saloon_id',$id)->get();
        
        $booking_user = DB::table('booking')->join('users', 'booking.saloon_id', '=', 'users.id')
            ->select('booking.*','users.name as saloonName')->where('user_id',$id)->get(); 
        
        
        //$booking = DB::table('booking')->where('user_id', '35');
        //return view('admin/booking',compact('booking'));
        return view('admin/booking')->with('booking',$booking)->with('booking_user',$booking_user);
    }
    
    
    public function acceptBooking(Request $request, $id)
    {
        $accept = Booking::find($id);
        $accept->status = '1';
        $accept->save();
        $request->session()->flash('success','Booking Accepted Successfully');
        return redirect('booking_admin');
    }
    
    
        public function deactiveBooking(Request $request, $id)
    {
        $accept = Booking::find($id);
        $accept->status = '0';
        $accept->save();
        $request->session()->flash('success','Booking Deactive Successfully');
        return redirect('booking_admin');
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->validate($request,[
            'customer_name'=>'required',
            'catergory'=>'required',
            'time_from'=>'nullable',
            'time_to'=>'nullable',
            'date'=>'nullable',
            'description'=>'nullable'

        ]);

        //Create post
        $booking = new Booking;
        $booking->user_id=Auth::user()->id;
        $booking->saloon_id=Auth::user()->id;
        $booking->customer_name=$request->input('customer_name');
        $booking->catergory=$request->input('catergory');
        $booking->save();
        return redirect('/main_salon_account');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        //
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $saloon = Booking::find($id);
        $saloon->delete();
         $request->session()->flash('delete','Booking Deleted Successfully');
        return redirect('booking_admin');
    }

    public function main_index()
    {
        $id=Auth::user()->id;
        $allSaloon = Saloon::all();
        $saloon_data = DB::table('saloon')->select('*')->where('user_id',$id)->get();
         return view('admin/edit_saloon')->with('saloon_data',$saloon_data)->with('allSaloon',$allSaloon);
        //return view('admin/edit_saloon',compact('saloon_data'));
    }

    public function show_booking_form()
    {
        $id = $_GET['salon'];
        $salons = Saloon::where ( 'id', 'LIKE', '%' . $id . '%' )->get ();
        return view ( 'main.make_appoinment' )->with('salons',$salons)->withQuery ( $id );
    }
    
    
}

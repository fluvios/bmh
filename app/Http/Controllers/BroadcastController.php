<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Broadcast;
use App\Models\User;
use Mail;

use Auth;

class BroadcastController extends Controller
{
    public function index(Request $request)
    {
        $broadcasts = Broadcast::paginate(10);
        $broadcasts->appends(['page']);
        return view('admin.broadcast', [
            'broadcasts' => $broadcasts
        ]);
    }

    public function detail($id)
    {
        $broadcast = Broadcast::findOrFail($id);
        $action = route('admin-broadcast-detail');
        $title  = trans('misc.add_new');
        return view('admin.form-broadcast', compact('broadcast', 'action', 'title'));
    }

    public function add()
    {
        $broadcast = new Broadcast();
        $action = route('admin-broadcast-store');
        $title  = trans('misc.add_new');
        return view('admin.form-broadcast', compact('broadcast', 'action', 'title'));
    }

    public function store(Request $request)
    {
        # save broadcast data
        $broadcast = new Broadcast();
        $broadcast->sender = Auth::user()->email;
        $broadcast->reciever = implode(",", $request->input('reciever', []));
        $broadcast->content = $request->content;

        #send data to each user
        $titleSite = 'Berbagikebaikan.org';
        $sender = Auth::user()->email;
        $recievers = $request->input('reciever', []);
        foreach ($recievers as $reciever) {
			$user = User::where('email', $reciever)->first();
            Mail::send(
                'emails.broadcast-message',
                array( 'data' => $request->content, 'fullname' => Auth::user()->name, 'title_site' => 'Berbagikebaikan.org' ),
                function ($message) use ($sender, $user, $titleSite, $reciever) {
                    $message->from($sender, $titleSite)
                  ->to($user->email, $user->name)
                  ->subject('Broadcast Message - '.$titleSite);
                }
              );
        }

        #redirect after save
        if ($broadcast->save()) {
            return redirect(route('admin-broadcast-index'))->with('success_message', trans('admin.success_add'));
        } else {
            return redirect()->back();
        }
    }
}

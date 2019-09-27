<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Guest;
use App\Ticket;
use Carbon\Carbon;

class IndexController extends Controller
{
	public function register() {
		return view('register');
	}

	public function dashboard() {
		$guests = Guest::orderBy('updated_at', 'asc')->paginate(10);
		$total = Guest::all();
		$tickets = Ticket::all();
		return view('dashboard', [
			'guests' => $guests,
			'tickets' => $tickets,
			'total' => $total,
		]);
	}

	public function raffle() {
		return view('raffle');
	}

	public function fillup() {
		return view('fillup');
	}

	public function login() {
		if (Auth::user()) {
			return redirect('dashboard');
		} else {
			$response = array();
			$response[0] = '';
			$response[1] = 0;
			return view('login', [
				'response' => $response,
			]);
		}
	}

	public function validation($ticket) {
		$duplicates = Ticket::where('ticket_no', '=', $ticket)->get();
		if (count($duplicates) > 0) {
			return '1';
		} else {
			return '0';
		}
	}

	public function guest_id($ticket) {
		$ticket = Ticket::where('ticket_no', '=', $ticket)->get();
		$guest = Guest::where('id', '=', $ticket[0]->guest_id)->get();
		if ($guest[0]->birth_date != null) {
			$guest[0]->birth_date = Carbon::parse($guest[0]->birth_date)->isoFormat('MMMM D, YYYY');
		}
		return $guest[0];
	}
}

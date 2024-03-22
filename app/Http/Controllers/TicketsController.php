<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\NewTicket;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketsController extends Controller
{
    /**
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $tickets = Ticket::where(function ($query) use ($request) {
            if ($request->id) {
                $query->find($request->id);
            }

            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->order_id) {
                $query->where('order_id', $request->order_id);
            }

            if ($request->user) {
                $query->whereHas('user', function ($q) use ($request) {
                    if (substr($request->user, 0, 1) == '@') {
                        $q->where('username', str_replace('@', '', $request->user));
                    } elseif (is_numeric($request->user)) {
                        $q->where('mobile', $request->user);
                    } else {
                        $q->where('email', $request->user);
                    }
                });
            }
        })->with(['user'])->latest('status_update')->paginate(12);

        return view('tickets.index', compact('tickets'));
    }

    public function replies($ticket_id)
    {
        $ticket = Ticket::with('replies')->find($ticket_id);
        return view('tickets.partials.reply', compact('ticket'));
    }

    /**
     * @param Ticket $ticket
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeReply(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($request->get('body')) {
            $ticket->replies()->create([
                'body' => $request->input('body'),
                'admin' => true,
                'user_id' => $this->user->id,
            ]);
        }

        $this->info('Your reply added to the ticket.');

        if ($request->has('close')) {
            $ticket->close();
            $this->warning("The ticket has been closed");
        } else {
            $ticket->pending();
        }

        $notification = new NewTicket($ticket);

        $ticket->user->notify($notification->onQueue('account'));

        return back();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        return view('tickets.form', compact('user'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required',
            'subject' => 'required',
            'user_id' => ['required', 'exists:users,id'],
        ]);

        DB::beginTransaction();
        $ticket = Ticket::query()->create([
            'user_id' => $request->get('user_id'),
            'status' => 'pending',
            'subject' => $request->get('subject'),
        ]);

        /** @var Ticket $ticket */
        $ticket->replies()->create([
            'body' => $request->get('body'),
            'admin' => true,
            'user_id' => $this->user->id,
        ]);
        DB::commit();

        $notification = new NewTicket($ticket);

        User::query()->find($request->get('user_id'))->notify(
            $notification->onQueue('account')
        );

        return response()->json(['status' => JsonResponse::HTTP_OK, 'msg' => 'Ticket stored', 'url' => route('tickets')]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class DashboardController extends Controller implements HasMiddleware
{
    protected $clientRepository;
    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return array<int,\Illuminate\Routing\Controllers\Middleware|\Closure|string>
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /*public function __construct(ClientRepository $clientRepository)
    {
        //$this->middleware(['auth']);
        $this->clientRepository = $clientRepository;
    }*/

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function users()
    {
        $users = User::all();
        return Inertia::render('Users', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_user(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', "User `` $user->name `` added succesfully");
    }

    /**
     * Display the specified resource.
     */
    public function show_user(Request $request, User $user)
    {
        return Inertia::render('User', [
            'user' => $user,
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => $request->session()->get('status'),
        ]);
    }

    public function update_user(ProfileUpdateRequest $request,User $user)
    {
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->back()->with('status','User updated.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function clients()
    {
        $clients = Client::get();
        return Inertia::render('Clients', [
            'clients' => $clients
        ]);
    }

    public function show_client(Client $client)
    {
        return Inertia::render('Client', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function create_client(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_url' => 'required|url',
            'confidential' => 'required|boolean',
            'type' => 'required'
        ]);

        /*$client = $this->clientRepository->create(
            user:auth()->id(), // Or null if clients are not user-specific
            name:$request->name,
            redirectUris: [$request->redirect],
            provider:null, // provider
            personalAccessClient:false, // personalAccessClient
            passwordClient:false, // passwordClient
            confidential:$request->boolean('confidential') //false  // confidential (set to true if secret is used)
        );*/
        $client = app(ClientRepository::class)->createAuthorizationCodeGrantClient(
            user: auth()->user(),
            name: $request->name,
            redirectUris: [$request->redirect_url],
            confidential: $request->boolean('confidential'),
            enableDeviceFlow: true
        );
        $results = [
            'message' => 'Client created successfully!',
            'client_id' => $client->id, // Passport's client ID is the UUID
            'client_secret' => $client->plainSecret, // Only show this once!
        ];
        return redirect()->back()->with('success', $results);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_client(Client $client)
    {
        $client->delete();
        return redirect()->back()->with('success', 'Client deleted successfully.');
    }
}

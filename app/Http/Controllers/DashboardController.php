<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Http\Resources\ClientResource;
use App\Jobs\ProcessUserImportJob;
use App\Models\Group;
use App\Models\Passport\Client;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Laravel\Passport\ClientRepository;

class DashboardController extends Controller implements HasMiddleware
{
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

    public function update_user(ProfileUpdateRequest $request, User $user)
    {
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->back()->with('status', 'User updated.');
    }

    public function delete_user(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);
        $user->delete();
        return redirect()->route('users')->with('success', 'User deleted successfully.');
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
        ClientResource::withoutWrapping();
        return Inertia::render('Client', [
            'client' => new ClientResource($client),
            'secret' => $client->plainSecret,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function create_client(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|array',
            'redirect_uris.*' => 'required|string',
            'confidential' => 'required|boolean',
            //'type' => 'required' oW2m4Y8iZbGLeLIGJSMw3EspD1UfJwv2H9Yu2K0P BACaYISwFYagWs6Oi99bhTjjkUHfJ6TKdBc9t73q
        ]);


        /*$client = app(ClientRepository::class)->createAuthorizationCodeGrantClient(
            name: $request->name,
            redirectUris: $request->redirect_uris, //explode(',', $request->redirect_uris),
            confidential: (bool) $request->input('confidential', true),
            user: null, //$request->user(),
            enableDeviceFlow: true
        );*/

        $client = app(ClientRepository::class)->create(
            userId: null,
            name: $request->name,
            redirect: implode(',', $request->redirect_uris),
            confidential: $request->boolean('confidential'),
            personalAccess: true,
            password: true,
        );

        $plainSecret = $client->plainSecret;
        if ($plainSecret) {
            ClientResource::withoutWrapping();
            return Inertia::render('Client', [
                'client' => new ClientResource($client),
                'secret' => $plainSecret,
            ]);
        }
        return redirect()->back()->with('success', "Client created succussfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_client(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'redirect_uris' => 'required|array',
            'redirect_uris.*' => 'required|string'
            //'confidential' => 'required|boolean',
            //'type' => 'required'
        ]);

        $attributes = [
            ...$validated,
            'redirect' => $validated['redirect_uris'],
        ];
        $client->update($attributes);
        return redirect()->back()->with('success', 'Client updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_client(Client $client)
    {
        $client->delete();
        return redirect()->back()->with('success', 'Client deleted successfully.');
    }

    public function authorized_clients()
    {
        return Inertia::render('auth/oauth/AuthorizedClients');
    }

    public function personal_access_tokens()
    {
        return Inertia::render('auth/oauth/PersonalAccessTokens');
    }

    public function import_users(Request $request)
    {
        $request->validate([
            'file' => 'required','file','mimes:csv' ]);

        $path = $request->file('file')->store('imports'); // Store file and get path
        
        // Dispatch job, only passing the path
        dispatch(new ProcessUserImportJob($path));
        
        return to_route('users')->with('success', 'File submitted to import queue.');
    }

    public function groups()
    {
        $groups = Group::get();
        return Inertia::render('Groups',[
            'groups' => $groups,
        ]);
    }

    public function store_group(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Group::create([
            'name' => $request->name,
        ]);
        return redirect()->back()->with('success', 'Group created successfully.');
    }

    public function show_group(Group $group)
    {
        $users = $group->users()->get();
        return Inertia::render('Group',[
            'group' => $group,
            'users' => $users,
        ]);
    }

    public function update_group(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $group->update([
            'name' => $request->name,
        ]);
        return redirect()->back()->with('success', 'Group updated successfully.');
    }

    public function destroy_group(Group $group)
    {
        $group->delete();
        return redirect()->back()->with('success', 'Group deleted successfully.');
    }   
}

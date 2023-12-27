<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::where('isUser', '=', 1)->paginate(5);

        $data_gen = [
            'prev_url' => "/home",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        return view('users.index', compact('data', 'data_gen'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $data_gen = [
            'prev_url' => "/mantenice/users",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles', 'data_gen'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.same' => 'La contraseña y la confirmación de la contraseña deben coincidir.',
            'password.min' => 'La contraseña y la confirmación de la contraseña deben tener minimo 8 caracteres.',
            'password.required' => 'Debe seleccionar al menos un rol',
        ];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ], $messages);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['isUser'] = 1;
        $input['email_verified_at'] = date('Y-m-d H:i:s');

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $data_gen = [
            'prev_url' => "/mantenice/users",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        $user = User::find($id);
        return view('users.show', compact('user', 'data_gen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $data_gen = [
            'prev_url' => "/mantenice/users",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole', 'data_gen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {

        $messages = [
            'name.required' => 'El campo nombre es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.same' => 'La contraseña y la confirmación de la contraseña deben coincidir.',
            'password.min' => 'La contraseña y la confirmación de la contraseña deben tener minimo 8 caracteres.',
            'password.required' => 'La contraseña es requerido'
        ];

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|same:confirm-password|min:8',
        ], $messages);
        $input = $request->all();
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }


    public function profile($id)
    {
        $user = User::find($id);

        $data_gen = [
            'prev_url' => "/home",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        return view('users.profile', compact('user', 'data_gen'));
    }

    //actualizar profile

    /**
     * $user = User::find($id);
     *$input = $request->all();
     *$user->update($input);
     *return redirect()->route('users.profile', $id)
     *   ->with('success', 'Usuario actualizado con exito');
     */
}

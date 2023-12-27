<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::where('isUser', '=', 0)->paginate(5);

        $data_gen = [
            'prev_url' => "/home",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        return view('clients.index', compact('data', 'data_gen'))
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
            'prev_url' => "/mantenice/clients",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        return view('clients.create', compact('data_gen'));
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
        ];


        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
        ], $messages);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['isUser'] = 0;
        $input['discounts'] = $request->discounts;



        $user = User::create($input);
        $user->assignRole('client');
        event(new Registered($user));

        return redirect()->route('clients.index')
            ->with('success', 'Cliente creado con exito');
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
            'prev_url' => "/mantenice/clients",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];
        $user = User::find($id);
        return view('clients.show', compact('user', 'data_gen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $data_gen = [
            'prev_url' => "/mantenice/clients",
            'title' => 'Sube tu fotos o videos y publica con nosotros.'

        ];

        return view('clients.edit', compact('user', 'data_gen'));
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
        ];


        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required|same:confirm-password|min:8',
        ], $messages);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->assignRole('client');
        $user->update($input);
        // DB::table('model_has_roles')->where('model_id', $id)->delete();

        // $user->assignRole($request->input('roles'));

        return redirect()->route('clients.index')
            ->with('success', 'Usuario actualizado con exito');
    }


    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['status' => 1, 'message' => 'exito'], 200);
    }


    public static function discount($id)
    {

        return User::find($id);
    }
}

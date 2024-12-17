<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\JsonResponder;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $users = User::all();
            if ($request->mode == "datatable") {
                return DataTables::of($users)
                    ->addColumn('action', function ($user) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/user/' . $user->id . '`, [`id`, `name`,`email`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/user/' . $user->id . '`, `user-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addIndexColumn()
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return $this->successResponse($user, 'Data user ditemukan.');
        }

        return view('pages.user.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return $this->successResponse($user, 'Data Buku Disimpan!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        return $this->successResponse($user, 'Data Buku Ditemukan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => '',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data Buku Tidak Ada!');
        }

        if ($request->password) {
            $updateUser = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ];
        } else {
            $updateUser = [
                'nama' => $request->name,
                'email' => $request->email,
            ];
        }

        $user->update($updateUser);

        return $this->successResponse($user, 'Data User Diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse(null, 'Data User Tidak Ada!');
        }

        $user->delete();

        return $this->successResponse(null, 'Data User Dihapus!');
    }
}

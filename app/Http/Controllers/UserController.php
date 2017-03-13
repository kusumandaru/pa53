<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use App\Repositories\UserRepository;
use Auth;
use Flash;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Response;
use File;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(UserDataTable $userDataTable)
    {
        return $userDataTable->render('users.index');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $roles = ['' => ''] + Role::pluck('name', 'id')->all();
        $departments = ['' => ''] + Department::pluck('name', 'id')->all();
        $positions = ['' => ''] + Position::pluck('name', 'id')->all();
        return view('users.create', compact('roles', 'departments', 'positions'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        if (!empty($request['password'])) {
            $request['password'] = bcrypt($request['password']);
        }

        $input = $request->all();

        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->with('departments')->with('roles')->with('positions')->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        $roles = ['' => ''] + Role::pluck('name', 'id')->all();
        $departments = ['' => ''] + Department::pluck('name', 'id')->all();
        $positions = ['' => ''] + Position::pluck('name', 'id')->all();
        return view('users.edit', compact('user', 'roles', 'departments', 'positions'));
        //return view('users.edit')->with('user', $user);
    }

    /**
     * Show the form for editing password the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function change($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.change_password', compact('user'));
        //return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();

            $path = public_path('profilepics/' . $filename);

            Image::make($image->getRealPath())->resize(200, 200)->save($path);
            $user->image = $filename;
            $user->save();
        }

        if (!empty($request['password'])) {
            if (empty($request['confirm_password'])) {
                Flash::error('Confirm password must be filled');
                return redirect()->back();
            }

            if ($request['confirm_password'] != $request['password']) {
                Flash::error('Password not same');
                return redirect()->back();
            }
            $request['password'] = Hash::make($request['password']);
        }

        $user = $this->userRepository->update($request->all(), $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Show the form for editing the specified User.
     *
     * @return Response
     */
    public function profile()
    {
        $id = Auth::User()->id;
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        $roles = ['' => ''] + Role::pluck('name', 'id')->all();
        $departments = ['' => ''] + Department::pluck('name', 'id')->all();
        $positions = ['' => ''] + Position::pluck('name', 'id')->all();
        return view('users.profile', compact('user', 'roles', 'departments', 'positions'));
    }

    /*
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function profileUpdate($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('home'));
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = uniqid(). '.' . $image->getClientOriginalExtension();

            $path = public_path('profilepics/' . $filename);

            Image::make($image->getRealPath())->resize(200, 200)->save($path);
            $user['image'] = $filename;
            $user->save();
        }

        if (!empty($request['password'])) {
            if (empty($request['confirm_password'])) {
                Flash::error('Confirm password must be filled');
                return redirect()->back();
            }

            if ($request['confirm_password'] != $request['password']) {
                Flash::error('Password not same');
                return redirect()->back();
            }
            $request['password'] = Hash::make($request['password']);
        }
        else{
            $request['password'] = $user['password'];
        }
        
        $user = $this->userRepository->update($request->all(), $id);
        Flash::success('User updated successfully.');

        return redirect(route('home'));
    }
}

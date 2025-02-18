<?php

namespace My\User\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use My\User\Http\Requests\v1\UserRegisterRequest;
use My\User\Models\User;

/**
 * @tags User
 */
class RegisteredUserController extends Controller
{
    /**
     * Register
     *
     * Handle an incoming registration request.
     *
     * @unauthenticated
     * @param UserRegisterRequest $request
     *
     * @return JsonResponse|RedirectResponse
     */
    public function store(UserRegisterRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $user = tap(User::create($data));

            DB::commit();

            event(new Registered($user));

            /**
             * Registration was successful
             *
             * @status 201
             */
            return response()->json(['type' => 'success', 'code' => 'users::register.success', 'message' => __('users::register.success')], 201);
        } catch (Exception $e) {
            DB::rollBack();

            /**
             * An error occurred during registration
             *
             * @status 500
             */
            return abort(500, 'users::register.error');
        }
    }

}
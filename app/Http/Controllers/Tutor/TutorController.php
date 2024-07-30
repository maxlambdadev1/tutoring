<?php

namespace App\Http\Controllers\Tutor;

use App\Models\User;
use App\Http\Controllers\TutoringController;
use Illuminate\Http\Request;

class TutorController extends TutoringController
{
    //
    public function checkUser(Request $request) {
        try {
            $user_email = $request->get('email');
            $result = true;
            if (!empty($user_email)) {
                $user = User::where('email', $user_email)->first();
                if(!empty($user)) $result = true;
                else $result = false;

                return response()->json([
                    'status' => 'ok',
                    'result' => $result
                ]);
            } throw new \Exception("Input the valid email");
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Constants\SignerType;
use App\Http\Requests\ValidateSigner;
use App\Jobs\NotifySigner;
use App\Jobs\RequestOTP;
use App\Models\Document;
use App\Models\Signer;
use App\Repositories\SignerRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SignerController extends Controller
{
    private SignerRepository $signerRepository;

    public function __construct(SignerRepository $signerRepository)
    {
        $this->signerRepository = $signerRepository;
    }

    public function show(Signer $signer): JsonResponse
    {
        return response()->json(['name' => $signer->name, 'email' => $signer->email, 'type' => $signer->type]);
    }

    public function sendOTP(Signer $signer): JsonResponse
    {
       try{

           $data = [
               'otp' => generateOtp(),
               'last_sent_otp' =>  Carbon::now()
           ];

           $this->signerRepository->update($signer, $data);

           dispatch(new RequestOTP($data['otp'], $signer->email));

           return response()->json(['message' => 'OTP sent! Please check your email']);

       }catch (\Exception $e){
           Log::error($e->getMessage());
           Log::error($e);
           return response()->json(['message' => 'An error occurred while processing your request'], 500);
       }
    }

    public function history(Signer $signer): JsonResponse
    {
        $signatureHistory = $this->signerRepository->history($signer->name, $signer->email, $signer->type);

        return response()->json(['data' => $signatureHistory]);
    }
}

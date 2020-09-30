<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entrace;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EntraceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($walletUuid, $categoryUUid = null)
    {
        $wallet = Wallet::where('uuid', $walletUuid)->first();
        $conditions['wallet_id'] = $wallet->id;
        if ($categoryUUid) {
            $category = Category::where('uuid', $categoryUUid)->first();
            $conditions['category_id'] = $category->id;
        }
        return response()->json(Entrace::where($conditions)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'wallet_id' => 'required|string|exists:wallets,id',
            'category_id' => 'required|string|exists:categories,id',
            'ticker' => 'max:15',
            'type' => 'max:20',
            'description' => 'string|required|max:255',
            'observation' => 'string|max:255',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        DB::beginTransaction();
        $walletUpdate = $this->entraceUpdateValues($request->wallet_id, $request->category_id, $request->value);
        if ($walletUpdate) {
            $newEntry = Entrace::create([
                'uuid' => Str::uuid(),
                'wallet_id' => $request->wallet_id,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'value' => $request->value,
                'observation' => $request->observation ?? '',
                'ticker' => $request->ticker ?? '',
                'type' => $request->type ?? ''
            ]);
    
            if ($newEntry) {
                DB::commit();
                return response()->json($newEntry);
            }
        }
        DB::rollBack();
        return response()->json('Error to create entry');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Entrace $entrace
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        return response()->json(Entrace::where('uuid', $uuid)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Entrace $entrace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $entraceUuid)
    {
        $validator = validator()->make($request->all(), [
            'wallet_id' => 'required|string|exists:wallets,id',
            'category_id' => 'required|string|exists:categories,id',
            'ticker' => 'max:15',
            'type' => 'max:20',
            'description' => 'string|max:255',
            'observation' => 'string|max:255',
            'value' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        
        $entrace = Entrace::where('uuid', $entraceUuid)->first();
        if (!$entrace) {
            return response()->json('Entrace not found', 400);
        }

        DB::beginTransaction();
        $newValue = isset($request->value) ?? 0;
        /** validate if value if different then update in wallet table */
        if ($newValue && $entrace->value != $newValue) {
            if ($request->wallet_id && $request->category_id) {
                $walletUpdate = $this->entraceUpdateValues($request->wallet_id, $request->category_id, $newValue);
                if ($walletUpdate) {
                    $entrace->update($request->all());
                    DB::commit();
                    return response()->json($entrace);
                }
            }
        }
        return response()->json('Error to update entrace');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Entrace $entrace
     * @return \Illuminate\Http\Response
     */
    public function destroy($entraceUuid)
    {
        $entrace = Entrace::where('uuid', $entraceUuid)->first();
        if ($entrace) {
            $walletUpdate = $this->entraceUpdateValues(
                $entrace->wallet_id,
                $entrace->category_id,
                -$entrace->value
            );
            if ($walletUpdate) {
                $entrace->delete();
                return response()->json('Entrace deleted successfully');
            }
        }
        return response()->json('Error to delete entrace');
    }

    /**
     * Update wallet current value
     *
     * @param string $walletId
     * @param string $categoryId
     * @param float $entraceValue
     * @return bool
     */
    public function entraceUpdateValues(string $walletId, string $categoryId, float $entraceValue) : bool
    {
        /** update wallet value with type of category */
        $wallet = Wallet::find($walletId);
        if (in_array($categoryId, [1, 2, 3, 4])) {
            $newValue = $wallet->current_value + $entraceValue;
        } else {
            $newValue = $wallet->current_value - $entraceValue;
        }
        if (!$wallet->update(['current_value' => $newValue])) {
            DB::rollBack();
            return false;
        }
        return true;
    }
}

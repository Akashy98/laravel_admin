<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\WalletOfferResource;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletOffer;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends BaseController
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Get user's wallet balance and basic info
     */
    public function getBalance(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $wallet = $user->wallet;

            if (!$wallet) {
                // Create wallet if it doesn't exist
                $wallet = $user->wallet()->create(['balance' => 0]);
            }

            $data = [
                'balance' => (float) $wallet->balance,
                'wallet_id' => $wallet->id,
                'currency' => 'INR', // Assuming Indian Rupees
                'last_updated' => $wallet->updated_at->toISOString()
            ];

            return $this->successResponse($data, 'Wallet balance retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving wallet balance: ' . $e->getMessage());
        }
    }

    /**
     * Get available wallet offers
     */
    public function getOffers(Request $request)
    {
        try {
            $offers = WalletOffer::where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->orderBy('amount', 'asc')
                ->get();

            $data = WalletOfferResource::collection($offers);

            return $this->successResponse($data, 'Wallet offers retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving offers: ' . $e->getMessage());
        }
    }

    /**
     * Calculate offer for a specific amount
     */
    public function calculateOffer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1|max:100000'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $amount = (float) $request->amount;

            // Find the best offer for this amount
            $offer = WalletOffer::where('status', 'active')
                ->where('amount', $amount)
                ->first();

            if (!$offer) {
                return $this->successResponse([
                    'amount' => $amount,
                    'bonus_amount' => 0,
                    'total_amount' => $amount,
                    'offer_applied' => false,
                    'message' => 'No offer available for this amount'
                ], 'No offer available for this amount');
            }

            $bonusAmount = ($amount * $offer->extra_percent) / 100;
            $totalAmount = $amount + $bonusAmount;

            $data = [
                'amount' => $amount,
                'bonus_amount' => $bonusAmount,
                'total_amount' => $totalAmount,
                'offer_applied' => true,
                'offer_details' => [
                    'id' => $offer->id,
                    'extra_percent' => $offer->extra_percent,
                    'is_popular' => $offer->is_popular,
                    'label' => $offer->label
                ],
                'message' => "You'll get ₹{$bonusAmount} bonus on ₹{$amount} recharge"
            ];

            return $this->successResponse($data, 'Offer calculated successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error calculating offer: ' . $e->getMessage());
        }
    }

    /**
     * Add money to wallet (direct success payment flow)
     */
    public function addMoney(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1|max:100000',
                'payment_method' => 'required|string|in:card,upi,netbanking,wallet',
                'offer_id' => 'nullable|exists:wallet_offers,id',
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->validationErrorResponse($validator->errors());
            }

            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $amount = (float) $request->amount;
            $paymentMethod = $request->payment_method;
            $offerId = $request->offer_id;
            $razorpayPaymentId = $request->razorpay_payment_id;
            $razorpayOrderId = $request->razorpay_order_id;
            $razorpaySignature = $request->razorpay_signature;

            // Verify Razorpay payment signature
            if (!$this->verifyRazorpaySignature($razorpayPaymentId, $razorpayOrderId, $razorpaySignature)) {
                return $this->errorResponse('Invalid payment signature', 400);
            }

            // Calculate offer if provided
            $bonusAmount = 0;
            $offer = null;

            if ($offerId) {
                $offer = WalletOffer::where('id', $offerId)
                    ->where('status', 'active')
                    ->first();

                if ($offer && $offer->amount == $amount) {
                    $bonusAmount = ($amount * $offer->extra_percent) / 100;
                }
            }

            // Add money to wallet using common function
            $result = $this->addMoneyToWallet($user, $amount, $bonusAmount, $paymentMethod, $offer, [
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_signature' => $razorpaySignature
            ]);

            if (!$result['success']) {
                return $this->errorResponse($result['message'], 400);
            }

            return $this->successResponse($result['data'], 'Money added to wallet successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error adding money to wallet: ' . $e->getMessage());
        }
    }

    /**
     * Common function to add money to wallet
     */
    protected function addMoneyToWallet($user, $amount, $bonusAmount, $paymentMethod, $offer = null, $paymentMeta = [])
    {
        try {
            return DB::transaction(function () use ($user, $amount, $bonusAmount, $paymentMethod, $offer, $paymentMeta) {
                $wallet = $user->wallet ?: $user->wallet()->create(['balance' => 0]);

                // Create main transaction
                $mainTransaction = $wallet->transactions()->create([
                    'amount' => $amount,
                    'type' => 'credit',
                    'description' => "Wallet recharge via {$paymentMethod}",
                    'meta' => json_encode(array_merge([
                        'payment_method' => $paymentMethod,
                        'offer_id' => $offer ? $offer->id : null,
                        'status' => 'completed',
                        'completed_at' => now()->toISOString()
                    ], $paymentMeta))
                ]);

                // Credit the main amount
                $wallet->balance += $amount;

                // Create bonus transaction if applicable
                $bonusTransaction = null;
                if ($bonusAmount > 0) {
                    $bonusTransaction = $wallet->transactions()->create([
                        'amount' => $bonusAmount,
                        'type' => 'bonus',
                        'description' => "Bonus from offer: {$offer->extra_percent}% extra",
                        'meta' => json_encode([
                            'offer_id' => $offer->id,
                            'main_transaction_id' => $mainTransaction->id,
                            'status' => 'completed',
                            'completed_at' => now()->toISOString()
                        ])
                    ]);

                    // Credit bonus amount
                    $wallet->balance += $bonusAmount;
                }

                $wallet->save();

                return [
                    'success' => true,
                    'data' => [
                        'transaction_id' => $mainTransaction->id,
                        'amount_credited' => $amount,
                        'bonus_credited' => $bonusAmount,
                        'total_credited' => $amount + $bonusAmount,
                        'new_balance' => $wallet->balance,
                        'payment_method' => $paymentMethod,
                        'offer_applied' => $bonusAmount > 0,
                        'offer_details' => $offer ? [
                            'id' => $offer->id,
                            'extra_percent' => $offer->extra_percent,
                            'is_popular' => $offer->is_popular,
                            'label' => $offer->label
                        ] : null
                    ]
                ];
            });
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error processing wallet transaction: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify Razorpay payment signature
     */
    protected function verifyRazorpaySignature($paymentId, $orderId, $signature)
    {
        try {
            // Get Razorpay secret from config
            $secret = config('services.razorpay.secret');

            if (!$secret) {
                // For development, you can skip signature verification
                return true;
            }

            // Verify signature
            $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $secret);

            return hash_equals($expectedSignature, $signature);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get transaction history
     */
    public function getTransactions(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $wallet = $user->wallet;

            if (!$wallet) {
                return $this->successResponse([], 'No transactions found');
            }

            $perPage = $request->get('per_page', 15);
            $type = $request->get('type'); // credit, debit, bonus
            $status = $request->get('status'); // pending, completed, failed

            $query = $wallet->transactions()
                ->orderBy('created_at', 'desc');

            if ($type) {
                $query->where('type', $type);
            }

            if ($status) {
                $query->whereRaw("JSON_EXTRACT(meta, '$.status') = ?", [$status]);
            }

            $transactions = $query->paginate($perPage);

            $formattedTransactions = $transactions->getCollection()->map(function ($transaction) {
                $meta = json_decode($transaction->meta, true) ?: [];

                return [
                    'id' => $transaction->id,
                    'amount' => (float) $transaction->amount,
                    'type' => $transaction->type,
                    'description' => $transaction->description,
                    'status' => $meta['status'] ?? 'completed',
                    'created_at' => $transaction->created_at->toISOString(),
                    'completed_at' => $meta['completed_at'] ?? null,
                    'payment_method' => $meta['payment_method'] ?? null,
                    'offer_id' => $meta['offer_id'] ?? null,
                    'razorpay_payment_id' => $meta['razorpay_payment_id'] ?? null,
                    'razorpay_order_id' => $meta['razorpay_order_id'] ?? null
                ];
            });

            $data = [
                'transactions' => $formattedTransactions,
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total()
                ]
            ];

            return $this->successResponse($data, 'Transaction history retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving transactions: ' . $e->getMessage());
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction($id, Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return $this->unauthorizedResponse('User not authenticated');
            }

            $transaction = WalletTransaction::with('wallet')->find($id);

            if (!$transaction || $transaction->wallet->owner_id !== $user->id) {
                return $this->notFoundResponse('Transaction not found');
            }

            $meta = json_decode($transaction->meta, true) ?: [];

            $data = [
                'id' => $transaction->id,
                'amount' => (float) $transaction->amount,
                'type' => $transaction->type,
                'description' => $transaction->description,
                'status' => $meta['status'] ?? 'completed',
                'created_at' => $transaction->created_at->toISOString(),
                'completed_at' => $meta['completed_at'] ?? null,
                'payment_method' => $meta['payment_method'] ?? null,
                'offer_id' => $meta['offer_id'] ?? null,
                'razorpay_payment_id' => $meta['razorpay_payment_id'] ?? null,
                'razorpay_order_id' => $meta['razorpay_order_id'] ?? null,
                'razorpay_signature' => $meta['razorpay_signature'] ?? null
            ];

            return $this->successResponse($data, 'Transaction details retrieved successfully');
        } catch (\Exception $e) {
            return $this->serverErrorResponse('Error retrieving transaction details: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // For admin dashboard, maybe we want all orders.
        if ($request->user()->role === 'admin') {
            $orders = Order::with(['items.service', 'user'])->orderBy('created_at', 'desc')->get();
        } else {
            $orders = Order::with(['items.service', 'user'])->where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
        }
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
        ]);

        // Determine user_id (customer)
        $userId = $request->user()->id;

        if ($request->filled('customer_id')) {
            $userId = $validated['customer_id'];
        } elseif ($request->filled('customer_name')) {
            $phone = $validated['customer_phone'] ?? null;
            
            if ($phone) {
                $user = \App\Models\User::firstOrCreate(
                    ['phone' => $phone],
                    ['name' => $validated['customer_name'], 'role' => 'user', 'password' => bcrypt('12345678')]
                );
            } else {
                $user = \App\Models\User::create([
                    'name' => $validated['customer_name'],
                    'role' => 'user',
                    'password' => bcrypt('12345678')
                ]);
            }
            $userId = $user->id;
        }

        $totalPrice = 0;
        $orderItemsData = [];

        foreach ($validated['items'] as $itemData) {
            $service = \App\Models\Service::findOrFail($itemData['service_id']);
            $qty = $itemData['quantity'];
            $subtotal = 0;

            if ($service->pricing_model === 'package' && $service->package_qty > 0) {
                $subtotal = ($qty / $service->package_qty) * $service->price;
            } else {
                $subtotal = $qty * $service->price;
            }

            $totalPrice += $subtotal;
            $orderItemsData[] = [
                'service_id' => $service->id,
                'quantity' => $qty,
                'subtotal' => $subtotal
            ];
        }

        $order = Order::create([
            'user_id' => $userId,
            'customer_name' => collect([$validated['customer_name'] ?? null, $validated['customer_phone'] ?? null])->filter()->join(' - ') ?: null,
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        foreach ($orderItemsData as $item) {
            $item['order_id'] = $order->id;
            \App\Models\OrderItem::create($item);
        }

        return response()->json($order->load(['items.service']), 201);
    }

    public function show(string $id, Request $request)
    {
        $query = Order::with(['items.service', 'user'])->where('id', $id);
        if ($request->user()->role !== 'admin') {
             $query->where('user_id', $request->user()->id);
        }
        $order = $query->firstOrFail();
        return response()->json($order);
    }

    public function update(Request $request, string $id)
    {
        // Usually only admins or staff change status. For demo, we just allow updating status.
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,processing,completed,delivered',
            'payment_status' => 'sometimes|in:unpaid,paid',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(string $id, Request $request)
    {
        $order = Order::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $order->delete();
        return response()->json(null, 204);
    }
}

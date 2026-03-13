<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function updateWashingStatus(Request $request, $id)
    {
        $item = \App\Models\OrderItem::with('service')->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $item->washing_status = $validated['status'];
        
        if ($validated['status'] === 'completed') {
            $item->washer_id = $request->user()->id;
            
            $multiplier = $item->quantity;
            if ($item->service->pricing_model === 'package' && $item->service->package_qty > 0) {
                $multiplier = $item->quantity / $item->service->package_qty;
            }
            $item->washer_wage_amount = $item->service->washer_wage * $multiplier;
        } else {
            // Revert wage if changed back to pending
            if ($item->washer_id === $request->user()->id || $request->user()->role === 'admin') {
                $item->washer_id = null;
                $item->washer_wage_amount = 0;
            } else {
                return response()->json(['message' => 'Unauthorized to revert'], 403);
            }
        }

        $item->save();
        return response()->json($item);
    }

    public function updateIroningStatus(Request $request, $id)
    {
        $item = \App\Models\OrderItem::with('service')->findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $item->ironing_status = $validated['status'];
        
        if ($validated['status'] === 'completed') {
            $item->ironer_id = $request->user()->id;
            
            $multiplier = $item->quantity;
            if ($item->service->pricing_model === 'package' && $item->service->package_qty > 0) {
                $multiplier = $item->quantity / $item->service->package_qty;
            }
            $item->ironer_wage_amount = $item->service->ironer_wage * $multiplier;
        } else {
            if ($item->ironer_id === $request->user()->id || $request->user()->role === 'admin') {
                $item->ironer_id = null;
                $item->ironer_wage_amount = 0;
            } else {
                return response()->json(['message' => 'Unauthorized to revert'], 403);
            }
        }

        $item->save();
        return response()->json($item);
    }

    public function myWages(Request $request)
    {
        $user = $request->user();
        
        $washingWages = \App\Models\OrderItem::where('washer_id', $user->id)
            ->where('washing_status', 'completed')
            ->sum('washer_wage_amount');
            
        $ironingWages = \App\Models\OrderItem::where('ironer_id', $user->id)
            ->where('ironing_status', 'completed')
            ->sum('ironer_wage_amount');
            
        return response()->json([
            'total_wage' => $washingWages + $ironingWages,
            'washing_wage' => $washingWages,
            'ironing_wage' => $ironingWages
        ]);
    }
}

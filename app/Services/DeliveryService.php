<?php

namespace App\Services;

class DeliveryService
{
    /**
     * Calculate straight-line distance in kilometers using the Haversine formula.
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Earth's mean radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Calculate floor delivery charge based on floor number and admin rules.
     * Floor 0 = Ground floor.
     * Up to free_floor_limit: 0. Above free_floor_limit: (floor - free_floor_limit) * extra_floor_charge.
     */
    public function calculateFloorCharge(int $floor): float
    {
        $floor = max(0, $floor);
        $freeFloorLimit = (int) setting('free_floor_limit', 1);
        $extraFloorCharge = (float) setting('extra_floor_charge', 10.00);

        if ($floor <= $freeFloorLimit) {
            return 0.00;
        }

        $extraFloors = $floor - $freeFloorLimit;
        return round($extraFloors * $extraFloorCharge, 2);
    }

    /**
     * Evaluate delivery zone, distance, delivery fee and eligibility.
     */
    public function evaluateDelivery(float $customerLat, float $customerLng, int $floor = 0, float $subtotal = 0.00): array
    {
        $isDeliveryEnabled = (bool) setting('delivery_enabled', true);
        if (!$isDeliveryEnabled) {
            return [
                'eligible' => false,
                'zone' => 'unavailable',
                'distance_km' => 0.00,
                'delivery_charge' => 0.00,
                'floor_charge' => 0.00,
                'total_shipping' => 0.00,
                'message' => 'Delivery service is currently paused.',
            ];
        }

        $minOrderValue = (float) setting('min_order_value', 50.00);
        if ($subtotal > 0 && $subtotal < $minOrderValue) {
            return [
                'eligible' => false,
                'zone' => 'under_minimum',
                'distance_km' => 0.00,
                'delivery_charge' => 0.00,
                'floor_charge' => 0.00,
                'total_shipping' => 0.00,
                'message' => 'Minimum order value is ' . format_currency($minOrderValue) . '.',
            ];
        }

        $storeLat = (float) setting('store_latitude', 3.1390); // Default Kuala Lumpur
        $storeLng = (float) setting('store_longitude', 101.6869);
        $freeRadius = (float) setting('free_delivery_radius', 5.0);
        $paidRadius = (float) setting('paid_delivery_radius', 2.0);
        $maxDeliveryRadius = $freeRadius + $paidRadius;

        $distance = $this->calculateDistance($storeLat, $storeLng, $customerLat, $customerLng);
        $floorCharge = $this->calculateFloorCharge($floor);

        // Zone 1: Free Delivery
        if ($distance <= $freeRadius) {
            return [
                'eligible' => true,
                'zone' => 'free',
                'distance_km' => $distance,
                'delivery_charge' => 0.00,
                'floor_charge' => $floorCharge,
                'total_shipping' => $floorCharge,
                'message' => 'Eligible for Free Delivery! (' . $distance . ' km from store)',
            ];
        }

        // Zone 2: Paid Delivery
        if ($distance <= $maxDeliveryRadius) {
            $mode = setting('paid_delivery_charge_mode', 'fixed'); // 'fixed' or 'per_km'
            $baseCharge = (float) setting('paid_delivery_charge', 15.00);

            if ($mode === 'per_km') {
                $kmBeyondFree = $distance - $freeRadius;
                $deliveryCharge = round($kmBeyondFree * $baseCharge, 2);
            } else {
                $deliveryCharge = round($baseCharge, 2);
            }

            $totalShipping = round($deliveryCharge + $floorCharge, 2);

            return [
                'eligible' => true,
                'zone' => 'paid',
                'distance_km' => $distance,
                'delivery_charge' => $deliveryCharge,
                'floor_charge' => $floorCharge,
                'total_shipping' => $totalShipping,
                'message' => 'Paid Delivery (' . $distance . ' km from store): ' . format_currency($deliveryCharge),
            ];
        }

        // Zone 3: Beyond Serviceable Area
        return [
            'eligible' => false,
            'zone' => 'unavailable',
            'distance_km' => $distance,
            'delivery_charge' => 0.00,
            'floor_charge' => $floorCharge,
            'total_shipping' => 0.00,
            'message' => 'Delivery not available in your area. Maximum delivery radius is ' . $maxDeliveryRadius . ' km.',
        ];
    }
}


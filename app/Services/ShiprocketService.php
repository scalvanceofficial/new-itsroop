<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ShiprocketService
{
    protected $base_url;
    protected $email;
    protected $password;
    protected $token;

    public function __construct()
    {
        $this->base_url = "https://apiv2.shiprocket.in/v1/external";
        $this->email = env('SHIPROCKET_EMAIL');
        $this->password = env('SHIPROCKET_PASSWORD');
        $this->token = $this->authenticate();
    }

    /**
     * Authenticate and get the API token
     */
    private function authenticate()
    {
        $response = Http::post("{$this->base_url}/auth/login", [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        return $response->json()['token'] ?? null;
    }

    /**
     * Create a Shiprocket Order
     */
    public function createOrder($order_data)
    {
        $response = Http::withToken($this->token)
            ->post("{$this->base_url}/orders/create/adhoc", $order_data);

        return $response->json();
    }

    public function updateOrder($order_data)
    {
        $response = Http::withToken($this->token)
            ->post("{$this->base_url}/orders/update/adhoc", $order_data);

        return $response->json();
    }

    /**
     * Track a Shiprocket Order
     */
    public function trackOrder($shipment_id)
    {
        $response = Http::withToken($this->token)
            ->get("{$this->base_url}/courier/track/shipment/{$shipment_id}");

        return $response->json();
    }

    /**
     * Cancel a Shiprocket Order
     */
    public function cancelOrder($order_id)
    {
        $response = Http::withToken($this->token)
            ->post("{$this->base_url}/orders/cancel", ['ids' => [$order_id]]);

        return $response->json();
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\EmailService;
use Illuminate\Console\Command;
use App\Services\ShiprocketService;

class ShiprocketOrderTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shiprocket:track-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Order tracking for Shiprocket orders.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::all();

        if ($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                $shiprocket = new ShiprocketService();
                $response = $shiprocket->trackOrder($order->shiprocket_shipment_id);
                $current_status = $response['tracking_data']['shipment_track'][0]['current_status'] ?? null;
                $previous_status = $order->shiprocket_status;

                if (!empty($response) && isset($response['tracking_data'])) {
                    $order->update([
                        'shiprocket_tracking_response' => $response,
                        'awb_code' => $response['tracking_data']['shipment_track'][0]['awb_code'],
                        'shiprocket_status' => $response['tracking_data']['shipment_track'][0]['current_status'],
                    ]);
                }

                // Check if the status has changed and is one of the target statuses
                if ($current_status !== $previous_status && in_array($current_status, ['Shipped', 'Out for Delivery', 'Delivered'])) {
                    $this->sendStatusChangeSms($order, $current_status);
                }
            }

            $this->info('Order tracking updated successfully.');
        } else {
            $this->info('No orders found for tracking.');
        }
    }

    /**
     * Send SMS notification for status change.
     *
     * @param Order $order
     * @param string $current_status
     * @return void
     */
    protected function sendStatusChangeSms($order, $current_status)
    {
        if ($current_status == 'Shipped') {
            $sms_template_id = 185968;
            $email_view = 'emails.order-shipped';
            $subject = 'Your Order #' . $order->order_number . ' Has Been Shipped 📦';
        } elseif ($current_status == 'Out for Delivery') {
            $sms_template_id = 185966;
            $email_view = 'emails.order-out-for-delivery';
            $subject = 'Your Order #' . $order->order_number . ' Is Out for Delivery 🚚';
        } elseif ($current_status == 'Delivered') {
            $sms_template_id = 185965;
            $email_view = 'emails.order-delivered';
            $subject = 'Your Order #' . $order->order_number . ' Has Been Delivered 🎉';
        }

        $data = [
            'subject' => $subject,
            'order_number' => $order->order_number,
            'customer_name' => $order->user->full_name,
            'total_amount' => $order->total_amount,
            'order_products' => $order->products,
        ];

        $variable = $order->order_number . '|' . config('app.url');

        send_sms($order->user->mobile, $variable, $sms_template_id);

        EmailService::sendEmail($order->user->email, $email_view, $data);
    }
}

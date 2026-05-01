# Stripe Integration Guide

This project has been transitioned from Razorpay to Stripe Checkout. Follow these steps to complete the setup.

## 1. Environment Configuration

Add your Stripe keys to the `.env` file. We have already added the placeholders for you.

```bash
# Stripe Keys
STRIPE_KEY=pk_test_... (Your Publishable Key)
STRIPE_SECRET=sk_test_... (Your Secret Key)
STRIPE_WEBHOOK_SECRET=whsec_... (Optional, for webhooks)
```

## 2. Stripe Dashboard Configuration

- Log in to your [Stripe Dashboard](https://dashboard.stripe.com/).
- Navigate to **Developers > API keys**.
- Copy the **Publishable key** and **Secret key** to your `.env` file.

## 3. How the Integration Works

- **Checkout**: When a user clicks "Place Order", they are redirected to a Stripe-hosted checkout page.
- **Support for Multiple Currencies**: Stripe automatically handles currency based on the session. The integration sends the currency selected by the user (GBP, USD, INR) to Stripe.
- **Order Creation**: Orders are created in the database only after a successful payment redirect from Stripe.
- **Conversion logic**: All prices in the database are assumed to be in **GBP** (Base Currency). Conversion happens dynamically during checkout.

## 4. Testing

Use Stripe's [test cards](https://stripe.com/docs/testing) to verify the flow:
- Card Number: `4242 4242 4242 4242`
- Expiry: Any future date
- CVC: Any 3 digits

## 5. Webhooks (Optional but Recommended)

If you want to handle payment fulfillment even if the user closes their browser before redirecting back, you can set up a Webhook pointing to `/stripe/webhook` (Note: This route is currently not implemented but recommended for production robustness).

---
*Developed by Its Roop LTD Tech Team*

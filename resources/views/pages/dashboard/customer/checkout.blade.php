@extends('layouts.customer.app')
@section('contents')
  <h1>Checkout</h1>

  <!-- Display cart items and total amount -->


  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script>
    var totalAmount = {{ $totalAmount }};

    var options = {
      key: `{{ env('RAZORPAY_KEY') }}`,
      amount: totalAmount * 100, // Amount in paisa
      currency: 'INR',
      name: 'Your Company Name',
      description: 'Payment for Order',
      image: 'path/to/your/logo.png',
      order_id: '{{ $order->id }}', // Use the order ID obtained from the server
      handler: function(response) {
        // Handle the Razorpay response
        console.log(response);

        $.ajax({
          url: `/checkout/payment-callback`,
          method: 'POST',
          data: {
            razorpay_order_id: response.razorpay_order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature: response.razorpay_signature,
            discount: '{{ $hasDiscount }}',
            _token: document.querySelector('meta[name="csrf-token"]').content
            // Add any additional data needed for processing
          },
          success: function(data) {
            console.log(data);

            // Check the server response and take appropriate action
            if (data.success) {
              // Redirect to a success page or show a success message
              window.location.href = '/dashboard';
            } else {
              // Show an error message or handle other scenarios
              alert('Payment failed. Please try again.');
            }
          },
          error: function(xhr, status, error) {
            console.error(JSON.stringify(error));
            // Handle error, show a message, or redirect accordingly
            alert('An error occurred. Please try again.');
          }
        });
      }
    };

    var rzp = new Razorpay(options);


    rzp.open();
  </script>
@endsection

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import { useState, useEffect } from 'react';
import CheckoutForm from './CheckoutForm';
import '../../css/stripe.css';


export default function Checkout({ auth, csrf_token, pkey }) {
    const [clientSecret, setClientSecret] = useState("");
    const stripePromise = loadStripe(pkey);

    useEffect(() => {

        fetch(route('stripe.create'), {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-Token": csrf_token
            },
            body: JSON.stringify({ items: [{ id: "id" }] }),
        })
        .then((res) => res.json())
        .then((data) => setClientSecret(data.clientSecret));

    }, []);

    const appearance = {
        theme: 'stripe',
    };
    const options = {
        clientSecret,
        appearance,
    };
    
    
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-8">
                <div id="payment-form" className="flex justify-center max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {clientSecret && (
                        <Elements options={options} stripe={stripePromise}>
                            <CheckoutForm />
                        </Elements>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
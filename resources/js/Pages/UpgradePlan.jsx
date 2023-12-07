import { PricingCard } from '@/Components/PricingCard';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function UpgradePlan ({ auth }) {

    const plans = [
        {
            label: "Free",
            color: "blue-gray",
            price: 0,
            buffs: [
                { name: "5 videos", value: true },
                { name: "Backup", value: false },
                { name: "life time technical support", value: false },
            ],
            btnName: "In Use"
        },
        {
            label: "Standard",
            color: "indigo",
            price: 29,
            buffs: [
                { name: "25 videos", value: true },
                { name: "Backup", value: true },
                { name: "life time technical support", value: true },
            ]
        },
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Upgrade Plan</h2>}
        >
            <Head title="Upgrade Plan" />

            <div className="p-4 sm:pt-24 flex gap-8 flex-wrap justify-center">
                { plans.map((p, i) => <PricingCard {...p} key={i} />) }
            </div>
        </AuthenticatedLayout>
    );
}
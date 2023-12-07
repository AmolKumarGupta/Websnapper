import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function UpgradePlan ({ auth }) {

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Upgrade Plan</h2>}
        >
            <Head title="Upgrade Plan" />

            <div className="py-8">
                
            </div>
        </AuthenticatedLayout>
    );
}
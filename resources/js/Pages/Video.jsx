import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';


export default function Video({ auth }) {
    return <AuthenticatedLayout 
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Video</h2>}
        >
            <Head title="Video" />
        </AuthenticatedLayout>
}
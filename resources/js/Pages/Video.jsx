import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';


export default function Video({ auth, videoHash }) {
    return <AuthenticatedLayout 
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Video</h2>}
        >
        <Head title="Video" />

        <div className="py-8">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="relative bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                    <div className="p-6 text-gray-900">Video</div>
                </div>

                <main className="p-6 pt-3 bg-white shadow-sm sm:rounded-b-lg">
                    <video controls preload="auto" src={route('video.play', videoHash)} width="100%"></video>'
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
}
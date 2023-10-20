import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Dashboard({ auth, usedVideos, totalVideos }) {

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="relative bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                        <div className="p-6 text-gray-900">My Library</div>

                        <div className="absolute top-1/2 -translate-y-1/2 right-4 flex items-center justify-center">
                            <button
                                type="button"
                                className="rounded-2xl bg-indigo-400 bg-opacity-80 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-70 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                            >
                                {usedVideos} / {totalVideos} Videos
                            </button>
                        </div>
                    </div>

                    <div className="relative p-6 pt-3 bg-white shadow-sm sm:rounded-b-lg">
                        {!usedVideos
                            ? <div className="my-16 text-gray-400 font-medium text-center text-2xl font-sans">No videos</div>
                            : <></>}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

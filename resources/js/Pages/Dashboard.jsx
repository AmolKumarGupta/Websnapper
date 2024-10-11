import CreateFolder from '@/Components/Folders/CreateFolder';
import Folder from '@/Components/Folders/Folder';
import Video from '@/Components/Video';
import { slugify } from '@/helpers';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { useEffect, useMemo } from 'react';

export default function Dashboard({ 
    auth, 
    usedVideos, 
    totalVideos, 
    videos, 
    folder,
    folders, 
    error 
}) {
    useEffect(() => {
        if (error) {
            alert(error);
        }
    }, [error])
    
    const videoList = useMemo(() => {
        return videos.map((v) => <Video key={v.id} data={v} />)
    }, [videos]);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-8">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="relative bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                        <div className="p-6 text-gray-900">
                            { 
                                (folder && folder.parent) 
                                ? <Link href={`?folder=${slugify(folder.parent.name)}~${folder.parent.hash}`}>{folder.parent.name}</Link> 
                                : <Link href='dashboard'>My Library</Link>
                            }

                            { folder ? ` / ${folder.name}` : `` }
                        </div>

                        <div className="absolute top-1/2 -translate-y-1/2 right-4 flex gap-2 items-center justify-center">
                            <button
                                type="button"
                                className="rounded-2xl bg-indigo-400 bg-opacity-80 px-4 py-2 text-sm font-medium text-white hover:bg-opacity-70 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
                            >
                                {usedVideos} / {totalVideos} Videos
                            </button>
                        </div>
                    </div>

                    <main className="relative p-6 pt-3 bg-white shadow-sm sm:rounded-b-lg">
                        <div className='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 grid-flow-row gap-4'>
                            <CreateFolder userId={auth.user.id} parentId={folder?.id} />
                            { folders.map((f) => <Folder model={f} key={f.id} />) }
                        </div>

                        <hr className='my-8' />

                        {
                            !usedVideos
                            ? <div className="my-16 text-gray-400 font-medium text-center text-2xl font-sans">No videos</div>
                            : <div className='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 grid-flow-row gap-4'>{videoList}</div>
                        }
                    </main>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

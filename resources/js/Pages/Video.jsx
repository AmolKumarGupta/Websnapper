import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useRef } from 'react';


export default function Video({ auth, videoHash, video }) {
    const titleInput = useRef(null)
    
    function update(ev) {
        let title = ev.target.value;
        if (title == video.title || title == '') {
            titleInput.current.value = video.title;
            return;
        }

        router.post(route('video.title'), {
            _method: 'post',
            videoId: video.id,
            title
        })
    }

    return <AuthenticatedLayout 
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Video</h2>}
        >
        <Head title="Video" />

        <div className="py-8">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="relative bg-white overflow-hidden shadow-sm sm:rounded-t-lg">
                    <div className="px-6 py-3 text-gray-900">
                        <input ref={titleInput} onBlur={update} className="w-1/2 border-0 outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md focus:shadow-sm block" defaultValue={video.title} />
                    </div>
                </div>

                <main className="p-6 pt-3 bg-white shadow-sm sm:rounded-b-lg">
                    <video controls preload="auto" src={route('video.play', videoHash)} width="100%"></video>'
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
}
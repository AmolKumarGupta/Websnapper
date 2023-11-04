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
                    <div className="px-6 py-3 text-gray-900 flex justify-between">
                        <input id="title-section-input" ref={titleInput} onBlur={update} className="w-1/2 border-0 outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md focus:shadow-sm block" defaultValue={video.title} />

                        <div className='flex items-center'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="feather feather-share-2 cursor-pointer"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        </div>
                    </div>
                </div>

                <main className="p-6 pt-3 bg-white shadow-sm sm:rounded-b-lg">
                    <video controls preload="auto" src={route('video.play', videoHash)} width="100%"></video>
                </main>
            </div>
        </div>
    </AuthenticatedLayout>
}
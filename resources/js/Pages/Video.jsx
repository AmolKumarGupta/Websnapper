import PrimaryButton from '@/Components/PrimaryButton';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Dialog, Transition } from '@headlessui/react';
import { Head, router, useForm } from '@inertiajs/react';
import { Fragment, useRef, useState } from 'react';


export default function Video({ auth, can, videoHash, video, view_count }) {
    const titleInput = useRef(null);
    const [isOpen, setIsOpen] = useState(false);
    const [addedAsView, setView] = useState(false);

    const { data, setData, post, processing, errors } = useForm({
        videoId: video.id,
        userEmail: '',
    })
      

    function update(ev) {
        if (! can.edit) {
            return;
        }

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

    function copyLink(e) {
        
        window.navigator.clipboard.writeText(route('videos.show', videoHash));

        let prev = e.target.innerHTML;
        e.target.innerHTML = 'Copied!';

        let timer = setTimeout(() => {
            e.target.innerHTML = prev;
            clearTimeout(timer);
        }, 2000);
    }

    function giveAccessAndCopy(e) {
        if (! can.edit) {
            return;
        }

        post(route('video.access'), { onSuccess: () => copyLink(e) })
    }

    function handlePlay(e) {
        if (addedAsView) {
            return;
        }

        router.post(route('video.views'), { _method: 'post', videoId: video.id }, {
            preserveScroll: true,
            only: [],
            onSuccess: () => setView(true)
        })
    }

    function sync(e) {
        if (! can.sync) {
            return;
        }

        router.post(route('video.sync'), { _method: 'post', videoId: video.id }, {
            preserveScroll: true,
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
                        {
                            can.edit 
                            ? <input id="title-section-input" ref={titleInput} onBlur={update} className="w-2/3 sm:w-1/2 border-0 outline-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md focus:shadow-sm block" defaultValue={video.title} /> 
                            : <div className="px-3 py-2 w-2/3 sm:w-1/2 block">{video.title}</div>
                        }

                        { can.edit && <div className='flex items-center' role='button' tabIndex='0' onClick={() => setIsOpen(true)} onKeyDown={(e) => e.key=='Enter' && setIsOpen(true)}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className="feather feather-share-2 cursor-pointer"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        </div> }
                    </div>
                </div>

                <main className="flex flex-wrap gap-4 p-6 bg-white shadow-sm sm:rounded-b-lg">
                    <video onPlay={handlePlay} className='rounded max-w-[850px]' width="100%" controls preload="auto" src={route('video.play', videoHash)} ></video>

                    <div className='p-4 pt-0'>
                        <div>
                            <span className='font-medium'>Created at:</span> {video.formattedCreatedAt}
                        </div>
                        <div>
                            <span className='font-medium'>Video by:</span> <span className='capitalize'>{video.user.name}</span>
                        </div>
                        <div>
                            <span className='font-medium'>Views:</span> <span className='capitalize'>{view_count}</span>
                        </div>

                        {can.sync && <PrimaryButton onClick={e => sync(e)} className='mt-4'>Upload in my Drive</PrimaryButton>}
                        
                    </div>
                </main>
            </div>
        </div>

        <Transition appear show={isOpen} as={Fragment}>
            <Dialog as="div" className="relative z-10" onClose={() => setIsOpen(false)}>
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/25" />
                </Transition.Child>

                <div className="fixed inset-0 overflow-y-auto">
                    <div className="flex min-h-full items-center justify-center p-4 text-center">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 scale-95"
                            enterTo="opacity-100 scale-100"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 scale-100"
                            leaveTo="opacity-0 scale-95"
                        >
                            <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                                <Dialog.Title
                                    as="h3"
                                    className="text-lg font-medium leading-6 text-gray-900"
                                >
                                    Share
                                </Dialog.Title>
                                <div className="mt-2">
                                    <p>User's Email</p>
                                    <input value={data.userEmail} onChange={e => setData('userEmail', e.target.value)} type="email" id="share-email-input" className="w-full mt-1 focus:border-indigo-500 focus:ring-indigo-500 rounded-md focus:shadow-sm block" placeholder='example.com' />
                                    { errors.userEmail && <div className='text-red-500'>{errors.userEmail}</div> }
                                </div>

                                <div className="mt-4 flex justify-end gap-2">
                                    <button
                                        type="button"
                                        className="inline-flex justify-center rounded-md border border-transparent bg-green-300 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-green-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2"
                                        onClick={giveAccessAndCopy} 
                                        disabled={processing} 
                                    >
                                        Give Access & Copy
                                    </button>

                                    <button
                                        type="button"
                                        className="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                                        onClick={() => setIsOpen(false)}
                                    >
                                        Close
                                    </button>
                                </div>
                            </Dialog.Panel>
                        </Transition.Child>
                    </div>
                </div>
            </Dialog>
        </Transition>
    </AuthenticatedLayout>
}